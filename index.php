<?php
require_once 'includes/functions.php';

// Get all moods
$moods = getMoods();

// Get default podcasts (feel-good mood)
$defaultMoodId = 1; // feel-good
$podcasts = getPodcastsByMood($defaultMoodId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FeelCast - Podcasts That Understand Your Feelings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
                    <li><a href="index.php" class="active">Home</a></li>
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

    <main class="container">
        <section class="hero" style="background-color: #f7f7f7;">
            <div class="hero-content">
                <h1>Welcome To FeelCast!</h1>
                <p>Podcasts That Understand Your Feelings.</p>
                <div class="btn-group">
                    <a href="#mood-section" class="btn btn-primary">Start</a>
                    <a href="about.php" class="btn btn-secondary">FAQs</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/images/microphone_1.jpg" alt="Podcast Microphone">
            </div>
        </section>

        <section id="mood-section" class="mood-section">
            <h2>How Are You Feeling Today?</h2>
            <div class="mood-grid">
                <?php foreach ($moods as $mood): ?>
                <div class="mood-card" data-mood-id="<?php echo $mood['id']; ?>">
                    <div class="mood-icon"><?php echo $mood['icon']; ?></div>
                    <div class="mood-name"><?php echo $mood['name']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="podcast-section">
            <h2>Feel-Good!</h2>
            <p class="podcast-message">You're in a great mood — let's keep that energy going!<br>Here's a podcast to boost the vibe even more.</p>
            
            <div class="podcast-list">
                <?php foreach ($podcasts as $podcast): ?>
                <div class="podcast-card">
                    <img src="<?php echo $podcast['image_url']; ?>" alt="<?php echo $podcast['title']; ?>" class="podcast-image">
                    <?php if (!empty($podcast['youtube_id'])): ?>
                    <div class="youtube-badge">
                        <i class="fab fa-youtube"></i>
                    </div>
                    <?php endif; ?>
                    <div class="podcast-details">
                        <div class="podcast-info">
                            <h3><?php echo $podcast['title']; ?></h3>
                            <p><?php echo $podcast['description']; ?></p>
                        </div>
                        <div class="podcast-controls">
                            <button class="favorite-btn <?php echo (isLoggedIn() && isFavorite($_SESSION['user_id'], $podcast['id'])) ? 'active' : ''; ?>" data-podcast-id="<?php echo $podcast['id']; ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                            <a href="player.php?id=<?php echo $podcast['id']; ?>" class="play-btn">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <button class="view-all-btn">View All</button>
        </section>
    </main>

    <script src="assets/js/main.js"></script>
</body>
</html> 