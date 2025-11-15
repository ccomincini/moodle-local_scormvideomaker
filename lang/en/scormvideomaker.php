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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language strings for SCORM Video Maker (English).
 *
 * @package scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Plugin information.
$string['pluginname'] = 'SCORM Video Maker';
$string['plugindesc'] = 'Create SCORM packages from video URLs (Vimeo, YouTube, HLS)';

// Admin menu.
$string['createscrorm'] = 'Create SCORM Video Package';

// Settings.
$string['setting_enabled'] = 'Enable SCORM Video Maker';
$string['setting_enabled_desc'] = 'Enable or disable the SCORM Video Maker plugin';
$string['setting_default_autoplay'] = 'Default Autoplay';
$string['setting_default_autoplay_desc'] = 'Enable autoplay by default for new videos';
$string['setting_default_completion_type'] = 'Default Completion Type';
$string['setting_default_completion_type_desc'] = 'Select how videos should be marked as complete by default';
$string['setting_default_completion_percentage'] = 'Default Completion Percentage';
$string['setting_default_completion_percentage_desc'] = 'Default percentage of video to watch before marking as complete (0-100)';

// Form labels - Video Configuration.
$string['form_course'] = 'Course';
$string['form_course_help'] = 'Select the course where the SCORM activity will be created';
$string['form_title'] = 'Activity Title';
$string['form_title_help'] = 'Enter the title for the SCORM activity';
$string['form_description'] = 'Description';
$string['form_description_help'] = 'Enter the description for the SCORM activity (supports text formatting)';
$string['form_videotype'] = 'Video Type';
$string['form_videotype_help'] = 'Select the type of video source: Vimeo, YouTube, or HLS Stream';
$string['form_videourl'] = 'Video URL/Code';
$string['form_videourl_help'] = 'For Vimeo: enter video ID (e.g., 123456789). For YouTube: enter video ID (e.g., dQw4w9WgXcQ). For HLS: enter full URL to the M3U8 file';
$string['form_seekbar'] = 'Seek Bar Behavior (Incomplete Videos)';
$string['form_seekbar_help'] = 'Control how users can navigate in videos that have not been completed yet';
$string['form_completion_type'] = 'Completion Type';
$string['form_completion_type_help'] = 'Select how the activity should be marked as complete';
$string['form_completion_percentage'] = 'Completion Percentage';
$string['form_completion_percentage_help'] = 'Enter the percentage of video that must be watched (0-100). Only used when "At percentage watched" is selected';
$string['form_autoplay'] = 'Autoplay';
$string['form_autoplay_help'] = 'Enable automatic playback when the video player loads';

// Video types.
$string['videotype_vimeo'] = 'Vimeo';
$string['videotype_youtube'] = 'YouTube';
$string['videotype_hls'] = 'HLS Stream';

// Seek bar options.
$string['seekbar_locked'] = 'Locked (no seeking allowed)';
$string['seekbar_free'] = 'Free (full seeking allowed)';
$string['seekbar_backward'] = 'Backward only (can rewind but not skip forward)';

// Completion types.
$string['completion_end'] = 'At end of video';
$string['completion_percentage'] = 'At percentage watched';

// SCORM settings section.
$string['section_scorm_settings'] = 'SCORM Activity Settings';
$string['section_scorm_settings_help'] = 'Configure the standard SCORM activity settings as you would when adding a SCORM activity manually to a course';

// SCORM options.
$string['scorm_version'] = 'SCORM Version';
$string['scorm_version_12'] = 'SCORM 1.2';
$string['scorm_version_2004'] = 'SCORM 2004';
$string['scorm_maxgrade'] = 'Maximum Grade';
$string['scorm_grademethod'] = 'Grading Method';
$string['scorm_grademethod_high'] = 'Highest grade obtained';
$string['scorm_grademethod_avg'] = 'Average grade obtained';
$string['scorm_grademethod_sum'] = 'Sum of grades obtained';
$string['scorm_grademethod_first'] = 'First attempt grade';
$string['scorm_grademethod_last'] = 'Last attempt grade';
$string['scorm_maxattempt'] = 'Maximum Attempts';
$string['scorm_maxattempt_unlimited'] = 'Unlimited';
$string['scorm_whatgrade'] = 'What Grade to Report';
$string['scorm_whatgrade_high'] = 'Highest attempt';
$string['scorm_whatgrade_first'] = 'First attempt';
$string['scorm_whatgrade_last'] = 'Last attempt';
$string['scorm_whatgrade_avg'] = 'Average attempt';
$string['scorm_displaycoursestructure'] = 'Display Course Structure';
$string['scorm_skipview'] = 'Skip View Page';
$string['scorm_skipview_never'] = 'Never';
$string['scorm_skipview_first'] = 'First access';
$string['scorm_skipview_always'] = 'Always';
$string['scorm_hidebrowse'] = 'Hide Browse Button';
$string['scorm_hidetoc'] = 'Hide Table of Contents';
$string['scorm_hidetoc_never'] = 'Show';
$string['scorm_hidetoc_structure'] = 'Hide structure only';
$string['scorm_hidetoc_all'] = 'Hide completely';
$string['scorm_nav'] = 'Show Navigation Bar';
$string['scorm_auto'] = 'Auto Commit';

// Messages and status.
$string['creating'] = 'Creating SCORM package...';
$string['success'] = 'SCORM package created successfully! The activity has been added to the course.';
$string['error'] = 'Error creating SCORM package';
$string['error_invalid_video_url'] = 'Invalid video URL or code format';
$string['error_invalid_course'] = 'Invalid course selected';
$string['error_course_not_found'] = 'Course not found or you do not have permission to access it';
$string['error_scorm_creation_failed'] = 'Failed to create SCORM activity in the course';
$string['error_invalid_completion_percentage'] = 'Completion percentage must be between 0 and 100';

// Permissions.
$string['scormvideomaker:create'] = 'Create SCORM video packages';
$string['scormvideomaker:manage'] = 'Manage SCORM video packages';

// Events.
$string['event_scorm_created'] = 'SCORM video package created';
$string['event_scorm_created_desc'] = 'User created a new SCORM video package in course {courseid}';
$string['event_scorm_updated'] = 'SCORM video package updated';
$string['event_scorm_updated_desc'] = 'User updated SCORM video package in course {courseid}';

// Privacy.
$string['privacy:metadata'] = 'The SCORM Video Maker plugin does not store any personal data. All tracking is managed by the standard SCORM activity module.'
