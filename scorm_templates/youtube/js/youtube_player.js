/**
 * YouTube Video Player Handler
 * Tracks video progress and manages SCORM completion
 */

var YoutubeVideoPlayer = {
    player: null,
    videoduration: 0,
    videocurrenttime: 0,
    lasttrackposition: 0,
    isplaying: false,
    lastseektime: 0,
    completion: {
        type: videoConfig.completiontype,
        percentage: videoConfig.completionpercentage,
        reached: false,
        triggeredon: null
    },

    /**
     * Initialize player - called when YouTube API is ready
     */
    init: function() {
        this.player = new YT.Player('youtube-player', {
            height: '600',
            width: '100%',
            videoId: videoConfig.videoid,
            playerVars: {
                autoplay: videoConfig.autoplay,
                controls: 1,
                modestbranding: 1,
                rel: 0,
                fs: 1
            },
            events: {
                'onReady': this.onplayerready.bind(this),
                'onStateChange': this.onplayerstatechange.bind(this),
                'onError': this.onplayererror.bind(this)
            }
        });

        // Start tracking playback
        this.starttracking();
    },

    /**
     * YouTube API ready callback
     */
    onplayerready: function(event) {
        this.videoduration = this.player.getDuration();
        console.log('YouTube player ready. Duration: ' + this.videoduration + 's');
    },

    /**
     * YouTube state change callback
     */
    onplayerstatechange: function(event) {
        if (event.data === YT.PlayerState.PLAYING) {
            this.isplaying = true;
            console.log('Video playing');
        } else if (event.data === YT.PlayerState.PAUSED) {
            this.isplaying = false;
        } else if (event.data === YT.PlayerState.ENDED) {
            this.handlevideoended();
        }
    },

    /**
     * YouTube error callback
     */
    onplayererror: function(event) {
        console.error('YouTube player error:', event.data);
    },

    /**
     * Start tracking video progress
     */
    starttracking: function() {
        var self = this;
        setInterval(function() {
            if (!self.isplaying) return;

            self.videocurrenttime = self.player.getCurrentTime();
            self.updateprogress();
            self.checkcompletion();
        }, 500);

        // Monitor for seeking
        this.monitorseek();
    },

    /**
     * Monitor for seek events
     */
    monitorseek: function() {
        var self = this;
        var lastposition = 0;

        setInterval(function() {
            if (!self.player) return;

            var currentposition = self.player.getCurrentTime();

            // Detect seek (jump of more than 2 seconds)
            if (Math.abs(currentposition - lastposition) > 2 && self.isplaying) {
                self.handleseek(currentposition);
            }

            lastposition = currentposition;
        }, 200);
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
                this.player.seekTo(this.videocurrenttime);
                console.log('Seeking disabled - video incomplete');
                break;

            case 'backward':
                // Only allow seeking backward
                if (seektime > this.videocurrenttime) {
                    this.player.seekTo(this.videocurrenttime);
                    console.log('Forward seeking disabled');
                }
                break;

            case 'free':
                // Allow all seeking
                break;
        }
    }
};

// YouTube API callback
function onYouTubeIframeAPIReady() {
    YoutubeVideoPlayer.init();
}

// Initialize on page load if YouTube API already loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof YT !== 'undefined' && YT.Player) {
        YoutubeVideoPlayer.init();
    }
});

