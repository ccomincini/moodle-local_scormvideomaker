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
 * @package scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Extends admin navigation to add SCORM Video Maker link.
 *
 * @param navigation_node $navnode The navigation node to extend
 * @return void
 */
function scormvideomaker_extend_navigation_admin_node(navigation_node $navnode): void {
    global $CFG;

    $context = context_system::instance();

    if (has_capability('local/scormvideomaker:create', $context)) {
        $url = new moodle_url($CFG->wwwroot . '/local/scormvideomaker/index.php');
        $navnode->add(
            get_string('createscrorm', 'local_scormvideomaker'),
            $url,
            navigation_node::TYPE_SETTING,
            null,
            'local_scormvideomaker',
            new pix_icon('/settings', '')
        );
    }
}
