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
 * @copyright 2025 Carlo Comincini rlo@comincini.it.it>
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
    redirect(new moodle_url('/admin/settings.php', ['section' => 'localplugins']));
}

if ($data = $mform->get_data()) {
    // Generate SCORM package.
    $generator = new \local_scormvideomaker\scorm_package_generator();
    
    try {
        $zipfile = $generator->generate_scorm_package($data);
        
        if (!$zipfile || !file_exists($zipfile)) {
            throw new moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker');
        }
        
        // Upload to draft area.
        $draftitemid = file_get_unused_draft_itemid();
        $fs = get_file_storage();
        $usercontext = context_user::instance($USER->id);
        
        // Create file record for draft area.
        $filerecord = [
            'contextid' => $usercontext->id,
            'component' => 'user',
            'filearea'  => 'draft',
            'itemid'    => $draftitemid,
            'filepath'  => '/',
            'filename'  => clean_filename($data->title . '_scorm.zip'),
            'timecreated' => time(),
            'timemodified' => time(),
        ];
        
        // Store file in draft area.
        $storedfile = $fs->create_file_from_pathname($filerecord, $zipfile);
        
        // Clean up temp file.
        if (file_exists($zipfile)) {
            unlink($zipfile);
        }
        
        if (!$storedfile) {
            throw new moodle_exception('error_file_upload', 'local_scormvideomaker');
        }
        
        // Store data in session for the SCORM form to pick up.
        $SESSION->local_scormvideomaker = (object)[
            'draftitemid' => $draftitemid,
            'name' => $data->title,
            'intro' => $data->description ?? '',
            'courseid' => $data->courseid,
            'section' => $data->section ?? 0,
        ];
        
        // Redirect to SCORM activity creation page.
        $redirecturl = new moodle_url('/course/modedit.php', [
            'add' => 'scorm',
            'type' => '',
            'course' => $data->courseid,
            'section' => $data->section ?? 0,
            'return' => 0,
            'sr' => 0,
        ]);
        
        redirect(
            $redirecturl,
            get_string('redirecting_to_scorm_form', 'local_scormvideomaker'),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
        
    } catch (Exception $e) {
        $PAGE->set_title(get_string('error'));
        $PAGE->set_heading(get_string('error'));
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
