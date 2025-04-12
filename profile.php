<?php
require_once 'includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Get user's favorite podcasts count
$conn = connectDB();
$stmt = $conn->prepare("
    SELECT COUNT(*) as count FROM favorites WHERE user_id = ?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$favCount = $result->fetch_assoc()['count'];
$stmt->close();
closeDB($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - FeelCast</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .profile-section {
            background-color: white;
            border-radius: 15px;
            padding: 40px;
            margin: 30px 0;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #6b9080;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            margin-right: 20px;
        }
        
        .profile-info h2 {
            color: #333;
            margin-bottom: 5px;
        }
        
        .profile-info p {
            color: #666;
        }
        
        .profile-stats {
            display: flex;
            margin-top: 20px;
        }
        
        .stat-item {
            background-color: #f5f5f5;
            border-radius: 10px;
            padding: 15px;
            margin-right: 15px;
            text-align: center;
            min-width: 120px;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #6b9080;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
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
                        <li><a href="profile.php" class="active">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="auth-btn">Login/Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="profile-section">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </div>
                <div class="profile-info">
                    <h2><?php echo $_SESSION['user_name']; ?></h2>
                    <p><?php echo $_SESSION['user_email']; ?></p>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $favCount; ?></div>
                            <div class="stat-label">Favorites</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 30px; text-align: center;">
                <a href="favorites.php" class="btn btn-primary">
                    <i class="fas fa-heart"></i> View My Favorites
                </a>
            </div>
        </section>
        
        <section class="profile-section">
            <h3 style="color: #6b9080; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Account Settings</h3>
            
            <p style="color: #666; margin-bottom: 20px;">
                Account management features coming soon! In the future, you'll be able to update your profile information and manage your account settings here.
            </p>
        </section>
    </main>

    <script src="assets/js/main.js"></script>
</body>
</html> 