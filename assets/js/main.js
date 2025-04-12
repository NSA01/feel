document.addEventListener('DOMContentLoaded', function() {
    // Mood selection functionality
    const moodCards = document.querySelectorAll('.mood-card');
    if (moodCards.length > 0) {
        moodCards.forEach(card => {
            card.addEventListener('click', function() {
                const moodId = this.getAttribute('data-mood-id');
                
                // Remove active class from all cards
                moodCards.forEach(c => c.classList.remove('active'));
                
                // Add active class to selected card
                this.classList.add('active');
                
                // Fetch podcasts for selected mood via AJAX
                fetchPodcastsByMood(moodId);
            });
        });
    }
    
    // Favorite button functionality
    const favoriteButtons = document.querySelectorAll('.favorite-btn');
    if (favoriteButtons.length > 0) {
        favoriteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const podcastId = this.getAttribute('data-podcast-id');
                toggleFavorite(podcastId, this);
            });
        });
    }
    
    // Player functionality
    const audioPlayer = document.getElementById('audio-player');
    const playPauseBtn = document.querySelector('.play-pause-btn');
    const progressBar = document.querySelector('.progress');
    const currentTimeEl = document.querySelector('.current-time');
    const durationEl = document.querySelector('.duration');
    
    if (audioPlayer && playPauseBtn) {
        playPauseBtn.addEventListener('click', function() {
            if (audioPlayer.paused) {
                audioPlayer.play();
                this.innerHTML = '<i class="fas fa-pause"></i>';
            } else {
                audioPlayer.pause();
                this.innerHTML = '<i class="fas fa-play"></i>';
            }
        });
        
        // Update progress bar
        audioPlayer.addEventListener('timeupdate', function() {
            const progress = (audioPlayer.currentTime / audioPlayer.duration) * 100;
            if (progressBar) progressBar.style.width = progress + '%';
            
            // Update current time
            if (currentTimeEl) {
                const minutes = Math.floor(audioPlayer.currentTime / 60);
                const seconds = Math.floor(audioPlayer.currentTime % 60);
                currentTimeEl.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }
        });
        
        // Set duration when metadata is loaded
        audioPlayer.addEventListener('loadedmetadata', function() {
            if (durationEl) {
                const minutes = Math.floor(audioPlayer.duration / 60);
                const seconds = Math.floor(audioPlayer.duration % 60);
                durationEl.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }
        });
    }
});

// Fetch podcasts by mood
function fetchPodcastsByMood(moodId) {
    fetch(`ajax/get_podcasts.php?mood_id=${moodId}`)
        .then(response => response.json())
        .then(data => {
            updatePodcastSection(data.podcasts, data.mood);
        })
        .catch(error => console.error('Error fetching podcasts:', error));
}

// Update podcast section with fetched podcasts
function updatePodcastSection(podcasts, mood) {
    const podcastSection = document.querySelector('.podcast-section');
    const podcastList = document.querySelector('.podcast-list');
    const podcastMessage = document.querySelector('.podcast-message');
    
    // Update message based on mood
    if (podcastMessage) {
        if (mood === 'feel-good') {
            podcastMessage.innerHTML = "You're in a great mood — let's keep that energy going!<br>Here's a podcast to boost the vibe even more.";
        } else if (mood === 'sad') {
            podcastMessage.innerHTML = "It's okay to feel down sometimes. These podcasts might help lift your spirits.";
        } else if (mood === 'Angry') {
            podcastMessage.innerHTML = "Let's channel that energy into something positive with these podcasts.";
        } else if (mood === 'Bored') {
            podcastMessage.innerHTML = "These fascinating podcasts will surely spark your interest!";
        } else if (mood === 'Anxious') {
            podcastMessage.innerHTML = "Take a deep breath. These calming podcasts might help ease your mind.";
        } else if (mood === 'Inspired') {
            podcastMessage.innerHTML = "Feed that inspiration with these thought-provoking podcasts!";
        }
    }
    
    // Clear existing podcasts
    if (podcastList) {
        podcastList.innerHTML = '';
        
        if (podcasts.length === 0) {
            podcastList.innerHTML = '<p>No podcasts found for this mood. Try another one!</p>';
        } else {
            // Add podcasts to the list
            podcasts.forEach(podcast => {
                const isFavorite = podcast.is_favorite ? 'active' : '';
                
                const podcastCard = `
                    <div class="podcast-card">
                        <img src="${podcast.image_url}" alt="${podcast.title}" class="podcast-image">
                        <div class="podcast-details">
                            <div class="podcast-info">
                                <h3>${podcast.title}</h3>
                                <p>${podcast.description}</p>
                            </div>
                            <div class="podcast-controls">
                                <button class="favorite-btn ${isFavorite}" data-podcast-id="${podcast.id}">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <a href="player.php?id=${podcast.id}" class="play-btn">
                                    <i class="fas fa-play"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                
                podcastList.innerHTML += podcastCard;
            });
            
            // Re-attach event listeners to new favorite buttons
            const newFavoriteButtons = podcastList.querySelectorAll('.favorite-btn');
            newFavoriteButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const podcastId = this.getAttribute('data-podcast-id');
                    toggleFavorite(podcastId, this);
                });
            });
        }
    }
    
    // Show podcast section
    if (podcastSection) {
        podcastSection.style.display = 'block';
    }
}

// Toggle favorite status
function toggleFavorite(podcastId, button) {
    fetch(`ajax/toggle_favorite.php?podcast_id=${podcastId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.is_favorite) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            } else if (data.error === 'not_logged_in') {
                // Redirect to login page
                window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
            }
        })
        .catch(error => console.error('Error toggling favorite:', error));
} 