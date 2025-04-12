<?php
require_once 'includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Get user's favorite podcasts
$conn = connectDB();
$stmt = $conn->prepare("
    SELECT p.* FROM podcasts p
    JOIN favorites f ON p.id = f.podcast_id
    WHERE f.user_id = ?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$favorites = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
closeDB($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites - FeelCast</title>
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="favorites.php" class="active">Favorites</a></li>
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
        <section class="hero">
            <div class="hero-content">
                <h1>My Favorite Podcasts</h1>
                <p>All your saved podcasts in one place.</p>
            </div>
            <div class="hero-image">
                <i class="fas fa-heart" style="font-size: 5rem; color: #ff6b6b;"></i>
            </div>
        </section>

        <section class="podcast-section">
            <?php if (empty($favorites)): ?>
            <div class="empty-state" style="text-align: center; padding: 50px 0;">
                <i class="far fa-heart" style="font-size: 3rem; color: #ccc; margin-bottom: 20px;"></i>
                <h2>No Favorites Yet</h2>
                <p>You haven't added any podcasts to your favorites yet.</p>
                <p>Go to the <a href="index.php">home page</a> to discover podcasts and add them to your favorites.</p>
            </div>
            <?php else: ?>
            <div class="podcast-list">
                <?php foreach ($favorites as $podcast): ?>
                <div class="podcast-card">
                    <img src="<?php echo $podcast['image_url']; ?>" alt="<?php echo $podcast['title']; ?>" class="podcast-image">
                    <div class="podcast-details">
                        <div class="podcast-info">
                            <h3><?php echo $podcast['title']; ?></h3>
                            <p><?php echo $podcast['description']; ?></p>
                        </div>
                        <div class="podcast-controls">
                            <button class="favorite-btn active" data-podcast-id="<?php echo $podcast['id']; ?>">
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
            <?php endif; ?>
        </section>
    </main>

    <script src="assets/js/main.js"></script>
</body>
</html> 