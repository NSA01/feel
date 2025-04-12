<?php
require_once 'includes/functions.php';

// Get podcast ID from URL
$podcast_id = $_GET['id'] ?? 0;

// Get podcast details
$podcast = getPodcastById($podcast_id);

// If podcast not found, redirect to home
if (!$podcast) {
    header('Location: index.php');
    exit;
}

// Check if podcast is favorited by user
$isFavorite = false;
if (isLoggedIn()) {
    $isFavorite = isFavorite($_SESSION['user_id'], $podcast_id);
}

// Determine if we should show YouTube
$hasYouTube = !empty($podcast['youtube_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $podcast['title']; ?> - FeelCast</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .player-favorite-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #ccc;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .player-favorite-btn.active {
            color: #ff6b6b;
        }
        
        .player-favorite-btn:hover {
            transform: scale(1.1);
        }
        
        .podcast-cover {
            position: relative;
            margin-bottom: 20px;
        }
        
        .youtube-container {
            width: 100%;
            max-width: 640px;
            margin: 0 auto 30px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .youtube-embed {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
        }
        
        .youtube-embed iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
        
        .podcast-title {
            margin-top: 20px;
            text-align: center;
        }
        
        .podcast-category {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="assets/images/logo.png" alt="FeelCast Logo">
                    <span>FeelCast</span>
                </div>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="favorites.php">Favorites</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="auth-btn">Login/Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="player-container">
            <div class="player-header">
                <a href="javascript:history.back()" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <span>Now Playing</span>
            </div>
            
            <div class="player-content">
                <?php if ($hasYouTube): ?>
                <!-- YouTube Video -->
                <div class="youtube-container">
                    <?php if (isLoggedIn()): ?>
                    <button class="player-favorite-btn <?php echo $isFavorite ? 'active' : ''; ?>" data-podcast-id="<?php echo $podcast['id']; ?>">
                        <i class="fas fa-heart"></i>
                    </button>
                    <?php endif; ?>
                    <div class="youtube-embed">
                        <iframe src="https://www.youtube.com/embed/<?php echo $podcast['youtube_id']; ?>" 
                                title="<?php echo $podcast['title']; ?>" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen></iframe>
                    </div>
                </div>
                <?php else: ?>
                <!-- Podcast Cover Image -->
                <div class="podcast-cover">
                    <img src="<?php echo $podcast['image_url']; ?>" alt="<?php echo $podcast['title']; ?>">
                    <?php if (isLoggedIn()): ?>
                    <button class="player-favorite-btn <?php echo $isFavorite ? 'active' : ''; ?>" data-podcast-id="<?php echo $podcast['id']; ?>">
                        <i class="fas fa-heart"></i>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <h2 class="podcast-title"><?php echo $podcast['title']; ?></h2>
                <p class="podcast-category"><?php echo $podcast['category']; ?></p>
            </div>
        </div>
    </main>

    <script src="assets/js/main.js"></script>
    <script>
        // Add favorite functionality for the player page
        document.addEventListener('DOMContentLoaded', function() {
            const favoriteBtn = document.querySelector('.player-favorite-btn');
            if (favoriteBtn) {
                favoriteBtn.addEventListener('click', function() {
                    const podcastId = this.getAttribute('data-podcast-id');
                    
                    fetch(`ajax/toggle_favorite.php?podcast_id=${podcastId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update all instances of favorite buttons for this podcast
                                document.querySelectorAll(`.player-favorite-btn[data-podcast-id="${podcastId}"]`).forEach(btn => {
                                    if (data.is_favorite) {
                                        btn.classList.add('active');
                                    } else {
                                        btn.classList.remove('active');
                                    }
                                });
                            } else if (data.error === 'not_logged_in') {
                                // Redirect to login page
                                window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
                            }
                        })
                        .catch(error => console.error('Error toggling favorite:', error));
                });
            }
        });
    </script>
</body>
</html> 