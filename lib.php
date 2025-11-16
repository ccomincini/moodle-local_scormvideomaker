<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Library functions for SCORM Video Maker.
 *
 * @package local_scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Extend navigation to add plugin link.
 *
 * @param global_navigation $navigation
 */
function local_scormvideomaker_extend_navigation(global_navigation $navigation) {
    // This function can be used to add custom navigation items if needed.
}

/**
 * Hook to pre-fill SCORM mod form with our generated package.
 * This is called when the SCORM activity form is being created.
 *
 * @param MoodleQuickForm $formwrapper The form wrapper
 * @param array $data The form data
 */
function local_scormvideomaker_coursemodule_edit_post_actions($data, $course) {
    global $SESSION;
    
    // Check if we have SCORM Video Maker data in session.
    if (isset($SESSION->local_scormvideomaker) && !empty($SESSION->local_scormvideomaker)) {
        // Data will be used by JavaScript or by form defaults.
        // Clean up after use.
        $scormdata = $SESSION->local_scormvideomaker;
        unset($SESSION->local_scormvideomaker);
    }
    
    return $data;
}

/**
 * Inject custom CSS and JavaScript for SCORM form pre-filling.
 *
 * This will auto-fill the SCORM package file field when coming from our plugin.
 */
function local_scormvideomaker_before_standard_html_head() {
    global $SESSION, $PAGE;
    
    // Only run on SCORM mod edit page.
    if ($PAGE->pagetype !== 'course-modedit' || $PAGE->url->get_param('add') !== 'scorm') {
        return '';
    }
    
    // Check if we have data from SCORM Video Maker.
    if (!isset($SESSION->local_scormvideomaker)) {
        return '';
    }
    
    $data = $SESSION->local_scormvideomaker;
    
    // Inject JavaScript to pre-fill the form.
    $js = "
    <script>
    require(['jquery'], function($) {
        $(document).ready(function() {
            // Pre-fill name field
            if ($('#id_name').val() === '') {
                $('#id_name').val(" . json_encode($data->name) . ");
            }
            
            // Pre-fill intro editor
            if (typeof CKEDITOR !== 'undefined') {
                var editor = CKEDITOR.instances['id_introeditor'];
                if (editor && editor.getData() === '') {
                    editor.setData(" . json_encode($data->intro) . ");
                }
            } else if ($('#id_introeditor').length && $('#id_introeditor').val() === '') {
                $('#id_introeditor').val(" . json_encode($data->intro) . ");
            }
            
            // Set the draft item ID for the file (this is the key part)
            if ($('input[name=\"packagefile\"]').length) {
                $('input[name=\"packagefile\"]').val(" . json_encode($data->draftitemid) . ");
            }
            
            // Show notification
            var notification = '<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">' +
                '<strong>" . get_string('scorm_package_loaded', 'local_scormvideomaker') . "</strong> ' +
                " . json_encode(get_string('scorm_package_loaded_desc', 'local_scormvideomaker')) . " +
                '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">' +
                '<span aria-hidden=\"true\">&times;</span></button></div>';
            
            $('#region-main').prepend(notification);
        });
    });
    </script>
    ";
    
    return $js;
}
