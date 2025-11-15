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

// Settings.
$string['setting_enabled'] = 'Enable SCORM Video Maker';
$string['setting_enabled_desc'] = 'Enable or disable the SCORM Video Maker plugin';
$string['setting_default_autoplay'] = 'Default Autoplay';
$string['setting_default_autoplay_desc'] = 'Enable autoplay by default for new videos';
$string['setting_default_completion_type'] = 'Default Completion Type';
$string['setting_default_completion_type_desc'] = 'Select how videos should be marked as complete by default';
$string['setting_default_completion_percentage'] = 'Default Completion Percentage';
$string['setting_default_completion_percentage_desc'] = 'Default percentage of video to watch before marking as complete (0-100)';

// Completion types.
$string['completion_end'] = 'At end of video';
$string['completion_percentage'] = 'At percentage watched';
