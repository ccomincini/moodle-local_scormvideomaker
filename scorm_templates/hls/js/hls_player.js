/**
 * HLS Video Player Handler
 * Tracks video progress and manages SCORM completion using Video.js
 */

var HLSVideoPlayer = {
    player: null,
    videoduration: 0,
    videocurrenttime: 0,
    lasttrackposition: 0,
    isplaying: false,
    completion: {
        type: videoConfig.completiontype,
        percentage: videoConfig.completionpercentage,
        reached: false,
        triggeredon: null
    },

    /**
     * Initialize player
     */
    init: function() {
        var videoelement = document.getElementById('hls-player');
        if (!videoelement) {
            console.error('HLS player element not found');
            return;
        }

        // Initialize Video.js
        this.player = videojs('hls-player', {
            controls: true,
            autoplay: videoConfig.autoplay,
            preload: 'auto',
            responsive: true,
            plugins: {
                httpSourceSelector: {}
            }
        });

        // Setup event listeners
        this.setupeventlisteners();

        console.log('HLS player initialized');
    },

    /**
     * Setup video event listeners
     */
    setupeventlisteners: function() {
        var self = this;

        // Loadstart event
        this.player.on('loadstart', function() {
            console.log('Loading video...');
        });

        // Loadedmetadata event
        this.player.on('loadedmetadata', function() {
            self.videoduration = self.player.duration();
            console.log('Video metadata loaded. Duration: ' + self.videoduration + 's');
        });

        // Play event
        this.player.on('play', function() {
            self.isplaying = true;
            self.starttracking();
        });

        // Pause event
        this.player.on('pause', function() {
            self.isplaying = false;
        });

        // Timeupdate event
        this.player.on('timeupdate', function() {
            self.videocurrenttime = self.player.currentTime();
            self.updateprogress();
            self.checkcompletion();
        });

        // Seeking event
        this.player.on('seeking', function() {
            self.handleseek(self.player.currentTime());
        });

        // Ended event
        this.player.on('ended', function() {
            self.handlevideoended();
        });

        // Error event
        this.player.on('error', function() {
            var errorcode = self.player.error().code;
            console.error('HLS player error code: ' + errorcode);
        });
    },

    /**
     * Start tracking video progress
     */
    starttracking: function() {
        // Tracking happens via timeupdate event
    },

    /**
     * Update progress display
     */
    updateprogress: function() {
        if (this.videoduration <= 0) return;

        var percentage = (this.videocurrenttime / this.videoduration) * 100;
        var progressfill = document.getElementById('progress-fill');
        var percentagetext = document.getElementById('percentage-text');

        if (progressfill) {
            progressfill.style.width = percentage + '%';
        }
        if (percentagetext) {
            percentagetext.textContent = Math.round(percentage) + '%';
        }
    },

    /**
     * Check if completion criteria met
     */
    checkcompletion: function() {
        if (this.completion.reached) return;

        var ismetbycriteria = false;

        if (this.completion.type === 'end') {
            // Check if video has reached end (90% of duration)
            ismetbycriteria = (this.videocurrenttime / this.videoduration) >= 0.90;
        } else if (this.completion.type === 'percentage') {
            // Check if watched percentage met
            var watchedpercentage = (this.videocurrenttime / this.videoduration) * 100;
            ismetbycriteria = watchedpercentage >= this.completion.percentage;
        }

        if (ismetbycriteria) {
            this.markascompleted();
        }
    },

    /**
     * Mark activity as completed
     */
    markascompleted: function() {
        this.completion.reached = true;
        this.completion.triggeredon = new Date().toISOString();

        var message = document.getElementById('completion-message');
        if (message) {
            message.textContent = 'Activity completed!';
            message.classList.add('completed');
        }

        SCORMAPIWrapper.setcompleted();
        SCORMAPIWrapper.commit();

        console.log('Activity marked as completed at ' + this.completion.triggeredon);
    },

    /**
     * Handle video ended
     */
    handlevideoended: function() {
        console.log('Video ended');
        if (this.completion.type === 'end') {
            this.markascompleted();
        }
    },

    /**
     * Handle seek - enforce restrictions if video incomplete
     */
    handleseek: function(seektime) {
        if (this.completion.reached) return; // Allow full seeking when completed

        switch (videoConfig.seekbar) {
            case 'locked':
                // Revert to current position
                this.player.currentTime(this.videocurrenttime);
                console.log('Seeking disabled - video incomplete');
                break;

            case 'backward':
                // Only allow seeking backward
                if (seektime > this.videocurrenttime) {
                    this.player.currentTime(this.videocurrenttime);
                    console.log('Forward seeking disabled');
                }
                break;

            case 'free':
                // Allow all seeking
                break;
        }
    }
};

// Initialize player when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    HLSVideoPlayer.init();
});

// Handle page unload
window.addEventListener('beforeunload', function() {
    SCORMAPIWrapper.terminate();
});

