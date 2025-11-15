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
 * SCORM Activity Creator class.
 *
 * Handles the creation of SCORM packages from video sources and creation of SCORM activities.
 *
 * @package   local_scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_scormvideomaker;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/scormvideomaker/lib.php');
require_once($CFG->dirroot . '/mod/scorm/lib.php');
require_once($CFG->libdir . '/filelib.php');

/**
 * SCORM Activity Creator.
 *
 * @package local_scormvideomaker
 */
class scorm_creator {

    /**
     * Create a SCORM activity from form data.
     *
     * @param object $formdata The form data containing video and SCORM settings
     * @return int|bool The ID of the created SCORM activity or false on failure
     * @throws moodle_exception
     */
    public function create_scorm_activity(object $formdata) {
        global $DB, $USER, $CFG;

        // Validate course.
        $course = $DB->get_record('course', ['id' => $formdata->course], '*', MUST_EXIST);

        // Generate SCORM package.
        $generator = new scorm_package_generator();
        $zipfile = $generator->generate_scorm_package($formdata);

        if (!$zipfile || !file_exists($zipfile)) {
            throw new \moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker');
        }

        try {
            // Create course module.
            $scormid = $this->create_scorm_module($course, $formdata, $zipfile);

            if ($scormid) {
                // Trigger event.
                $event = \local_scormvideomaker\event\scorm_created::create([
                    'courseid' => $course->id,
                    'objectid' => $scormid,
                    'userid' => $USER->id,
                ]);
                $event->trigger();

                return $scormid;
            }

            return false;

        } finally {
            // Clean up temp file.
            if (file_exists($zipfile)) {
                unlink($zipfile);
            }
        }
    }

    /**
     * Create a SCORM course module.
     *
     * @param object $course The course object
     * @param object $formdata The form data
     * @param string $zipfile Path to the SCORM package ZIP file
     * @return int|bool The SCORM activity ID or false on failure
     */
    private function create_scorm_module(object $course, object $formdata, string $zipfile) {
        global $DB, $CFG;

        // Get the latest section.
        $section = $DB->get_record(
            'course_sections',
            ['course' => $course->id],
            '*',
            IGNORE_MULTIPLE_RECORDS
        );

        if (!$section) {
            // Create a new section if none exist.
            $section = new \stdClass();
            $section->course = $course->id;
            $section->section = 0;
            $section->name = null;
            $section->summary = '';
            $section->summaryformat = FORMAT_HTML;
            $section->sequence = '';
            $section->visible = 1;
            $section->id = $DB->insert_record('course_sections', $section);
        }

        // Create course module record.
        $cm = new \stdClass();
        $cm->course = $course->id;
        $cm->module = $DB->get_field('modules', 'id', ['name' => 'scorm']);
        $cm->section = $section->section;
        $cm->instance = 0; // Will be updated after SCORM is created.
        $cm->visible = $formdata->visible ?? 1;
        $cm->visibleold = $cm->visible;
        $cm->visibleoncoursepage = 1;
        $cm->showdescription = 0;
        $cm->indent = 0;
        $cm->completion = COMPLETION_TRACKING_NONE;

        $cm->id = $DB->insert_record('course_modules', $cm);

        // Create SCORM instance.
        $scorm = new \stdClass();
        $scorm->course = $course->id;
        $scorm->name = $formdata->title;
        $scorm->intro = $formdata->description ?? '';
        $scorm->introformat = $formdata->descriptionformat ?? FORMAT_HTML;
        $scorm->version = $formdata->scorm_version ?? 'SCORM_1.2';
        $scorm->maxgrade = $formdata->scorm_maxgrade ?? 100;
        $scorm->grademethod = $formdata->scorm_grademethod ?? 1;
        $scorm->maxattempt = $formdata->scorm_maxattempt ?? 0;
        $scorm->whatgrade = $formdata->scorm_whatgrade ?? 0;
        $scorm->displaycoursestructure = $formdata->scorm_displaycoursestructure ?? 0;
        $scorm->skipview = $formdata->scorm_skipview ?? 2;
        $scorm->hidebrowse = $formdata->scorm_hidebrowse ?? 1;
        $scorm->hidetoc = $formdata->scorm_hidetoc ?? 3;
        $scorm->nav = $formdata->scorm_nav ?? 1;
        $scorm->auto = $formdata->scorm_auto ?? 0;
        $scorm->width = 100;
        $scorm->height = 600;
        $scorm->timemodified = time();
        $scorm->timeopen = 0;
        $scorm->timeclose = 0;

        $scormid = $DB->insert_record('scorm', $scorm);

        if (!$scormid) {
            $DB->delete_records('course_modules', ['id' => $cm->id]);
            return false;
        }

        // Update course module with SCORM instance.
        $cm->instance = $scormid;
        $DB->update_record('course_modules', $cm);

        // Upload the SCORM package file.
        if (!$this->upload_scorm_package($scormid, $cm->id, $zipfile)) {
            $DB->delete_records('scorm', ['id' => $scormid]);
            $DB->delete_records('course_modules', ['id' => $cm->id]);
            return false;
        }

        // Update course sequence.
        $this->update_course_section_sequence($cm->id, $section->id);

        // Log the action.
        add_to_log(
            $course->id,
            'scorm',
            'add',
            'view.php?id=' . $cm->id,
            $scormid
        );

        return $scormid;
    }

    /**
     * Upload SCORM package file to file area.
     *
     * @param int $scormid The SCORM activity ID
     * @param int $cmid The course module ID
     * @param string $zipfile Path to the ZIP file
     * @return bool Success
     */
    private function upload_scorm_package(int $scormid, int $cmid, string $zipfile): bool {
        global $CFG;

        require_once($CFG->dirroot . '/mod/scorm/locallib.php');

        $fs = get_file_storage();
        $context = \context_module::instance($cmid);

        // Delete existing package files.
        $fs->delete_area_files($context->id, 'mod_scorm', 'package');

        // Create file record.
        $filerecord = [
            'contextid' => $context->id,
            'component' => 'mod_scorm',
            'filearea'  => 'package',
            'itemid'    => 0,
            'filepath'  => '/',
            'filename'  => 'scorm_package.zip',
            'timecreated' => time(),
            'timemodified' => time(),
        ];

        // Create the file from pathname.
        $storedfile = $fs->create_file_from_pathname($filerecord, $zipfile);

        if (!$storedfile) {
            return false;
        }

        // Parse the SCORM package.
        $scorm = new \stdClass();
        $scorm->id = $scormid;
        scorm_parse($scorm, $storedfile);

        return true;
    }

    /**
     * Update course section sequence.
     *
     * @param int $cmid The course module ID to add
     * @param int $sectionid The section ID
     * @return void
     */
    private function update_course_section_sequence(int $cmid, int $sectionid): void {
        global $DB;

        $section = $DB->get_record('course_sections', ['id' => $sectionid], '*', MUST_EXIST);

        $sequence = explode(',', trim($section->sequence));
        $sequence[] = $cmid;
        $section->sequence = implode(',', array_filter($sequence));

        $DB->update_record('course_sections', $section);
    }
}

