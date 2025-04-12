<?php
require_once '../includes/functions.php';
require_once '../includes/youtube_converter.php';

// Get YouTube ID from request
$youtube_id = $_GET['youtube_id'] ?? '';

if (empty($youtube_id)) {
    echo json_encode(['success' => false, 'message' => 'YouTube ID is required']);
    exit;
}

// Convert YouTube video to MP3
$result = convertYoutubeToMp3($youtube_id);

// Return result as JSON
echo json_encode($result);
?> 