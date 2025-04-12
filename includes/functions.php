<?php
session_start();
require_once 'db.php';

// User registration
function registerUser($name, $email, $password) {
    $conn = connectDB();
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed_password);
    
    $result = $stmt->execute();
    
    $stmt->close();
    closeDB($conn);
    
    return $result;
}

// User login
function loginUser($email, $password) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Password is correct, create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            $stmt->close();
            closeDB($conn);
            return true;
        }
    }
    
    $stmt->close();
    closeDB($conn);
    return false;
}

// Get all moods
function getMoods() {
    $conn = connectDB();
    
    $result = $conn->query("SELECT * FROM moods ORDER BY id");
    $moods = $result->fetch_all(MYSQLI_ASSOC);
    
    closeDB($conn);
    return $moods;
}

// Get podcasts by mood
function getPodcastsByMood($mood_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("
        SELECT p.* FROM podcasts p
        JOIN mood_podcast_mapping m ON p.id = m.podcast_id
        WHERE m.mood_id = ?
    ");
    $stmt->bind_param("i", $mood_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $podcasts = $result->fetch_all(MYSQLI_ASSOC);
    
    $stmt->close();
    closeDB($conn);
    return $podcasts;
}

// Get podcast by ID
function getPodcastById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM podcasts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $podcast = $result->fetch_assoc();
    
    $stmt->close();
    closeDB($conn);
    return $podcast;
}

// Check if podcast is favorited by user
function isFavorite($user_id, $podcast_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND podcast_id = ?");
    $stmt->bind_param("ii", $user_id, $podcast_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $isFavorite = ($result->num_rows > 0);
    
    $stmt->close();
    closeDB($conn);
    return $isFavorite;
}

// Toggle favorite status
function toggleFavorite($user_id, $podcast_id) {
    $conn = connectDB();
    
    if (isFavorite($user_id, $podcast_id)) {
        // Remove from favorites
        $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND podcast_id = ?");
        $stmt->bind_param("ii", $user_id, $podcast_id);
        $stmt->execute();
        $stmt->close();
        closeDB($conn);
        return false;
    } else {
        // Add to favorites
        $stmt = $conn->prepare("INSERT INTO favorites (user_id, podcast_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $podcast_id);
        $stmt->execute();
        $stmt->close();
        closeDB($conn);
        return true;
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Logout user
function logoutUser() {
    session_unset();
    session_destroy();
}
?> 