<?php
require_once 'includes/functions.php';

// Check if user is already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// Process signup form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        // Check if email already exists
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email already exists. Please use a different email or login.';
        } else {
            if (registerUser($name, $email, $password)) {
                // Auto login after registration
                loginUser($email, $password);
                
                // Redirect to home page
                header('Location: index.php');
                exit;
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        
        $stmt->close();
        closeDB($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - FeelCast</title>
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
                    <li><a href="favorites.php">Favorites</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="login.php" class="auth-btn active">Login/Sign Up</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="auth-container">
            <div class="auth-sidebar">
                <h2>Sign Up To Your Account</h2>
                <div class="auth-icon">
                    <i class="fas fa-headphones-alt"></i>
                </div>
            </div>
            <div class="auth-form">
                <h3>Get Started Now</h3>
                
                <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form action="signup.php" method="post">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="auth-btn-submit">Sign Up</button>
                </form>
                
                <p class="auth-link">Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </main>
</body>
</html> 