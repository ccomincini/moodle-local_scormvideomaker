/**
 * Vimeo Video Player Handler
 * Tracks video progress and manages SCORM completion
 */

var VimeoVideoPlayer = {
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
        var iframe = document.getElementById('vimeo-player');
        if (!iframe) {
            console.error('Vimeo player iframe not found');
            return;
        }

        this.player = new Vimeo.Player(iframe);

        // Get video duration
        this.player.getDuration().then(function(duration) {
            VimeoVideoPlayer.videoduration = duration;
        });

        // Setup event listeners
        this.setupeventlisteners();

        // Auto-play if configured
        if (videoConfig.autoplay) {
            this.player.play();
        }
    },

    /**
     * Setup video event listeners
     */
    setupeventlisteners: function() {
        var self = this;

        // Play event
        this.player.on('play', function() {
            self.isplaying = true;
            self.updateui();
        });

        // Pause event
        this.player.on('pause', function() {
            self.isplaying = false;
        });

        // Time update event
        this.player.on('timeupdate', function(data) {
            self.videocurrenttime = data.seconds;
            self.updateprogress();
            self.checkcompletion();
        });

        // Ended event
        this.player.on('ended', function() {
            self.handlevideoended();
        });

        // Seek event - check seekbar restrictions
        this.player.on('seeked', function(data) {
            self.handleseek(data.seconds);
        });

        // Error event
        this.player.on('error', function(error) {
            console.error('Vimeo player error:', error);
        });
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
                this.player.setCurrentTime(this.videocurrenttime);
                console.log('Seeking disabled - video incomplete');
                break;

            case 'backward':
                // Only allow seeking backward
                if (seektime > this.videocurrenttime) {
                    this.player.setCurrentTime(this.videocurrenttime);
                    console.log('Forward seeking disabled');
                }
                break;

            case 'free':
                // Allow all seeking
                break;
        }
    },

    /**
     * Update UI state
     */
    updateui: function() {
        // Update play/pause button if needed
    }
};

// Initialize player when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    VimeoVideoPlayer.init();
});

