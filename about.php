<?php
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - FeelCast</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .about-section {
            background-color: white;
            border-radius: 15px;
            padding: 40px;
            margin: 30px 0;
        }
        
        .about-section h2 {
            color: #6b9080;
            margin-bottom: 20px;
        }
        
        .about-section p {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .faq-item {
            margin-bottom: 20px;
        }
        
        .faq-question {
            font-weight: bold;
            color: #6b9080;
            margin-bottom: 5px;
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
                    <li><a href="about.php" class="active">About</a></li>
                    <?php if (isLoggedIn()): ?>
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
        <section class="about-section">
            <h2>About FeelCast</h2>
            <p>FeelCast is a unique podcast platform that understands your emotions and recommends podcasts based on how you're feeling. We believe that the right content at the right time can significantly impact your mood and overall well-being.</p>
            <p>Our curated collection of podcasts spans various genres and topics, carefully selected to resonate with different emotional states. Whether you're feeling happy, sad, anxious, or inspired, FeelCast has something that will either complement or help shift your current mood.</p>
        </section>
        
        <section class="about-section">
            <h2>Frequently Asked Questions</h2>
            
            <div class="faq-item">
                <div class="faq-question">How does FeelCast work?</div>
                <div class="faq-answer">FeelCast asks you how you're feeling today and then recommends podcasts that are tailored to your current emotional state. Our algorithm matches content that either complements your positive mood or helps improve a negative one.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Do I need an account to use FeelCast?</div>
                <div class="faq-answer">You can browse and listen to podcasts without an account, but creating an account allows you to save your favorite podcasts and get personalized recommendations over time.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Are the podcasts free to listen to?</div>
                <div class="faq-answer">Yes, all podcasts on FeelCast are completely free to listen to.</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">Can I submit my own podcast to FeelCast?</div>
                <div class="faq-answer">We're currently working on a submission system for podcast creators. Stay tuned for updates!</div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">How often is new content added?</div>
                <div class="faq-answer">We add new podcasts weekly to ensure there's always fresh content that matches your mood.</div>
            </div>
        </section>
    </main>
</body>
</html> 