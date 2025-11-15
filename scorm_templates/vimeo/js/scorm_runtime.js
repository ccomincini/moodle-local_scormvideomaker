/**
 * SCORM Runtime API
 * Handles communication with Moodle SCORM API
 */

var SCORMAPIWrapper = {
    isInitialized: false,
    isTerminated: false,
    sessiontime: 0,
    sessionstarttime: Date.now(),

    /**
     * Initialize SCORM API
     */
    initialize: function() {
        if (typeof scorm_init === 'function') {
            scorm_init();
            this.isInitialized = true;
            console.log('SCORM initialized');
        }
    },

    /**
     * Set SCORM data value
     */
    setvalue: function(element, value) {
        if (typeof scorm_set === 'function') {
            scorm_set(element, value);
        }
    },

    /**
     * Get SCORM data value
     */
    getvalue: function(element) {
        if (typeof scorm_get === 'function') {
            return scorm_get(element);
        }
        return null;
    },

    /**
     * Mark as completed
     */
    setcompleted: function() {
        this.setvalue('cmi.core.lesson_status', 'completed');
        this.setvalue('cmi.core.score.raw', 100);
        if (typeof scorm_status === 'function') {
            scorm_status(1);
        }
    },

    /**
     * Commit data to server
     */
    commit: function() {
        if (typeof scorm_commit === 'function') {
            scorm_commit();
        }
    },

    /**
     * Terminate SCORM session
     */
    terminate: function() {
        if (!this.isTerminated) {
            this.sessiontime = Math.floor((Date.now() - this.sessionstarttime) / 1000);
            this.setvalue('cmi.core.session_time', this.formattime(this.sessiontime));
            this.commit();
            this.isTerminated = true;
        }
    },

    /**
     * Format time to SCORM format (HH:MM:SS)
     */
    formattime: function(seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds % 3600) / 60);
        var secs = seconds % 60;

        return ('0' + hours).slice(-2) + ':' +
               ('0' + minutes).slice(-2) + ':' +
               ('0' + secs).slice(-2);
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    SCORMAPIWrapper.initialize();
});

// Handle page unload
window.addEventListener('beforeunload', function() {
    SCORMAPIWrapper.terminate();
});

