<?php
require_once '../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

// Get podcast ID from request
$podcast_id = $_GET['podcast_id'] ?? 0;

if (!$podcast_id) {
    echo json_encode(['error' => 'Podcast ID is required']);
    exit;
}

// Toggle favorite status
$is_favorite = toggleFavorite($_SESSION['user_id'], $podcast_id);

// Return result
echo json_encode([
    'success' => true,
    'is_favorite' => $is_favorite
]);
?> 