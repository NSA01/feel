CREATE DATABASE feelcast;
USE feelcast;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE moods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    icon VARCHAR(50) NOT NULL
);

CREATE TABLE podcasts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    audio_url VARCHAR(255) NOT NULL,
    duration TIME,
    category VARCHAR(100),
    youtube_id VARCHAR(20) NULL
);

CREATE TABLE mood_podcast_mapping (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mood_id INT,
    podcast_id INT,
    FOREIGN KEY (mood_id) REFERENCES moods(id),
    FOREIGN KEY (podcast_id) REFERENCES podcasts(id)
);

CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    podcast_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (podcast_id) REFERENCES podcasts(id)
);

-- Add conversions table
CREATE TABLE conversions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    youtube_id VARCHAR(20) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert default moods
INSERT INTO moods (name, icon) VALUES 
('feel-good', '😊'),
('sad', '😔'),
('Angry', '😠'),
('Bored', '😐'),
('Anxious', '😰'),
('Inspired', '⭕');

-- Insert sample podcasts
INSERT INTO podcasts (title, description, image_url, audio_url, duration, category) VALUES
('How to build your personal resilience', 'Learn techniques to build resilience in challenging times', 'assets/images/happy_podcast.jpg', 'assets/audio/resilience.mp3', '00:45:00', 'Self-help'),
('Imperfect hands don\'t reduce creativity', 'Embracing imperfection in creative pursuits', 'assets/images/new_happy.jpg', 'assets/audio/creativity.mp3', '00:32:15', 'Creativity'),
('Sunday Vibes: Rift', 'Relaxing music to enhance your Sunday mood', 'assets/images/sunday_vibes.jpg', 'assets/audio/sunday_vibes.mp3', '00:28:30', 'Entertainment');

-- Map podcasts to moods
INSERT INTO mood_podcast_mapping (mood_id, podcast_id) VALUES
(1, 1), -- feel-good -> resilience podcast
(1, 2), -- feel-good -> creativity podcast
(1, 3), -- feel-good -> Sunday Vibes
(6, 1), -- Inspired -> resilience podcast
(6, 2); -- Inspired -> creativity podcast

-- Update sample podcasts with YouTube IDs
UPDATE podcasts SET youtube_id = 'dQw4w9WgXcQ' WHERE id = 1; -- Example YouTube ID
UPDATE podcasts SET youtube_id = 'jNQXAC9IVRw' WHERE id = 2; -- Example YouTube ID
UPDATE podcasts SET youtube_id = 'QH2-TGUlwu4' WHERE id = 3; -- Example YouTube ID 