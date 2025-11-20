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
 * Main page for SCORM Video Maker.
 *
 * @package local_scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/form/create_scorm_form.php');

$context = context_system::instance();
require_login();
require_capability('local/scormvideomaker:create', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/scormvideomaker/index.php'));
$PAGE->set_title(get_string('createscrorm', 'local_scormvideomaker'));
$PAGE->set_heading(get_string('createscrorm', 'local_scormvideomaker'));

// Create form.
$mform = new local_scormvideomaker_create_scorm_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/my/'));
}

if ($data = $mform->get_data()) {
    // Create SCORM activity.
    $creator = new \local_scormvideomaker\scorm_creator();

    try {
        $scormid = $creator->create_scorm_activity($data);
        
        if ($scormid) {
            // Use redirect without notification to avoid session issues
            // The success will be shown on the course page
            redirect(
                new moodle_url('/course/view.php', ['id' => $data->courseid]),
                get_string('success', 'local_scormvideomaker'),
                0, // No delay
                \core\output\notification::NOTIFY_SUCCESS
            );
        } else {
            throw new moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker');
        }
    } catch (Exception $e) {
        // Only output header/footer on error
        echo $OUTPUT->header();
        echo $OUTPUT->notification($e->getMessage(), 'notifyproblem');
        echo $OUTPUT->continue_button(new moodle_url('/local/scormvideomaker/index.php'));
        echo $OUTPUT->footer();
        die();
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('createscrorm', 'local_scormvideomaker'));
$mform->display();
echo $OUTPUT->footer();

