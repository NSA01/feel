<?php
require_once '../includes/functions.php';

// Get mood ID from request
$mood_id = $_GET['mood_id'] ?? 0;

if (!$mood_id) {
    echo json_encode(['error' => 'Mood ID is required']);
    exit;
}

// Get podcasts for the selected mood
$podcasts = getPodcastsByMood($mood_id);

// Get mood name
$conn = connectDB();
$stmt = $conn->prepare("SELECT name FROM moods WHERE id = ?");
$stmt->bind_param("i", $mood_id);
$stmt->execute();
$result = $stmt->get_result();
$mood = $result->fetch_assoc();
$mood_name = $mood ? $mood['name'] : '';
$stmt->close();
closeDB($conn);

// Add favorite status to podcasts if user is logged in
if (isLoggedIn()) {
    foreach ($podcasts as &$podcast) {
        $podcast['is_favorite'] = isFavorite($_SESSION['user_id'], $podcast['id']);
    }
}

// Return podcasts as JSON
echo json_encode([
    'podcasts' => $podcasts,
    'mood' => $mood_name
]);
?> 