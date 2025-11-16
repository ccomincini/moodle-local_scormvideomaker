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
     * @throws \moodle_exception
     */
    public function create_scorm_activity(object $formdata) {
        global $DB, $USER, $CFG;

        mtrace('[SCORM Creator] Starting SCORM activity creation...');
        debugging('SCORM Creator: Form data received - ' . json_encode($formdata), DEBUG_DEVELOPER);

        // Validate course.
        try {
            $course = $DB->get_record('course', ['id' => $formdata->courseid], '*', \MUST_EXIST);
            mtrace('[SCORM Creator] Course validated: ' . $course->fullname);
        } catch (\Exception $e) {
            $error = 'Course validation failed: ' . $e->getMessage();
            mtrace('[SCORM Creator ERROR] ' . $error);
            debugging($error, DEBUG_DEVELOPER);
            throw new \moodle_exception('error_invalid_course', 'local_scormvideomaker', '', null, $error);
        }

        // Generate SCORM package.
        mtrace('[SCORM Creator] Generating SCORM package...');
        $generator = new scorm_package_generator();
        $zipfile = $generator->generate_scorm_package($formdata);

        if (!$zipfile || !file_exists($zipfile)) {
            $error = 'SCORM package generation failed - no zip file created';
            mtrace('[SCORM Creator ERROR] ' . $error);
            debugging($error, DEBUG_DEVELOPER);
            throw new \moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker', '', null, $error);
        }

        mtrace('[SCORM Creator] Package generated: ' . $zipfile . ' (' . filesize($zipfile) . ' bytes)');

        try {
            // Create course module.
            mtrace('[SCORM Creator] Creating SCORM module in course...');
            $scormid = $this->create_scorm_module($course, $formdata, $zipfile);

            if ($scormid) {
                mtrace('[SCORM Creator] SCORM activity created successfully with ID: ' . $scormid);
                // Clean up temp file.
                if (file_exists($zipfile)) {
                    unlink($zipfile);
                    mtrace('[SCORM Creator] Temporary file cleaned up');
                }

                return $scormid;
            }

            $error = 'create_scorm_module returned false/null';
            mtrace('[SCORM Creator ERROR] ' . $error);
            debugging($error, DEBUG_DEVELOPER);
            throw new \moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker', '', null, $error);

        } catch (\Exception $e) {
            $error = 'Exception during SCORM module creation: ' . $e->getMessage();
            mtrace('[SCORM Creator ERROR] ' . $error);
            debugging('Stack trace: ' . $e->getTraceAsString(), DEBUG_DEVELOPER);
            
            // Clean up temp file on error.
            if (file_exists($zipfile)) {
                unlink($zipfile);
                mtrace('[SCORM Creator] Temporary file cleaned up after error');
            }
            throw new \moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker', '', null, $error);
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

        require_once($CFG->dirroot . '/course/lib.php');

        mtrace('[create_scorm_module] Starting module creation');
        mtrace('[create_scorm_module] Course: ' . $course->id . ', Section: ' . ($formdata->section ?? 0));

        // Get the section.
        $section = $formdata->section ?? 0;
        $sectionrecord = $DB->get_record(
            'course_sections',
            ['course' => $course->id, 'section' => $section],
            '*'
        );

        if (!$sectionrecord) {
            mtrace('[create_scorm_module] Section ' . $section . ' not found, creating...');
            // Create section if it doesn't exist.
            $sectionrecord = new \stdClass();
            $sectionrecord->course = $course->id;
            $sectionrecord->section = $section;
            $sectionrecord->name = null;
            $sectionrecord->summary = '';
            $sectionrecord->summaryformat = \FORMAT_HTML;
            $sectionrecord->sequence = '';
            $sectionrecord->visible = 1;
            $sectionrecord->id = $DB->insert_record('course_sections', $sectionrecord);
            mtrace('[create_scorm_module] Section created with ID: ' . $sectionrecord->id);
        } else {
            mtrace('[create_scorm_module] Section found with ID: ' . $sectionrecord->id);
        }

        // Create course module record.
        mtrace('[create_scorm_module] Creating course module record...');
        $cm = new \stdClass();
        $cm->course = $course->id;
        
        try {
            $cm->module = $DB->get_field('modules', 'id', ['name' => 'scorm'], \MUST_EXIST);
            mtrace('[create_scorm_module] SCORM module ID: ' . $cm->module);
        } catch (\Exception $e) {
            mtrace('[create_scorm_module ERROR] SCORM module not found in database');
            throw new \moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker', '', null, 'SCORM module not installed');
        }
        
        $cm->section = $sectionrecord->id;
        $cm->instance = 0; // Will be updated after SCORM is created.
        $cm->visible = 1;
        $cm->visibleold = 1;
        $cm->visibleoncoursepage = 1;
        $cm->showdescription = 0;
        $cm->indent = 0;
        $cm->groupmode = 0;
        $cm->groupingid = 0;
        
        // Set completion to NONE - users will configure it themselves if needed.
        $cm->completion = \COMPLETION_TRACKING_NONE;
        $cm->completionview = 0;
        $cm->completionexpected = 0;
        $cm->completiongradeitemnumber = null;
        
        $cm->added = time();

        $cm->id = $DB->insert_record('course_modules', $cm);
        mtrace('[create_scorm_module] Course module created with ID: ' . $cm->id);

        // Create context.
        $context = \context_module::instance($cm->id);

        // Create SCORM instance.
        $scorm = new \stdClass();
        $scorm->course = $course->id;
        $scorm->name = $formdata->title;
        $scorm->intro = $formdata->description ?? '';
        $scorm->introformat = \FORMAT_HTML;
        
        // SCORM settings - use sensible defaults.
        $scorm->version = 'SCORM_1.2';
        $scorm->maxgrade = 100;
        $scorm->grademethod = 1; // Highest grade.
        $scorm->maxattempt = 0; // Unlimited.
        $scorm->whatgrade = 0; // Highest attempt.
        $scorm->displaycoursestructure = 0;
        $scorm->skipview = 2; // Always skip.
        $scorm->hidebrowse = 1;
        $scorm->hidetoc = 3; // Always hide.
        $scorm->nav = 1;
        $scorm->auto = 1; // Auto-continue.
        $scorm->popup = 0;
        $scorm->width = 100;
        $scorm->height = 600;
        $scorm->displayattemptstatus = 1;
        $scorm->timemodified = time();
        $scorm->timeopen = 0;
        $scorm->timeclose = 0;
        
        // Package settings.
        $scorm->scormtype = 'local';
        $scorm->reference = basename($zipfile);
        $scorm->pkgtype = 'zip';
        $scorm->sha1hash = '';
        $scorm->revision = 0;
        $scorm->forcecompleted = 0;
        $scorm->forcenewattempt = 0;
        $scorm->lastattemptlock = 0;
        $scorm->masteryoverride = 1;
        $scorm->displayactivityname = 1;

        $scormid = $DB->insert_record('scorm', $scorm);
        mtrace('[create_scorm_module] SCORM instance created with ID: ' . $scormid);

        if (!$scormid) {
            mtrace('[create_scorm_module ERROR] Failed to create SCORM instance');
            $DB->delete_records('course_modules', ['id' => $cm->id]);
            return false;
        }

        // Update course module with SCORM instance.
        $cm->instance = $scormid;
        $DB->update_record('course_modules', $cm);
        mtrace('[create_scorm_module] Course module updated with SCORM instance');

        // Upload the SCORM package file.
        mtrace('[create_scorm_module] Uploading SCORM package...');
        if (!$this->upload_scorm_package($scormid, $cm->id, $zipfile)) {
            mtrace('[create_scorm_module ERROR] Failed to upload SCORM package');
            $DB->delete_records('scorm', ['id' => $scormid]);
            $DB->delete_records('course_modules', ['id' => $cm->id]);
            return false;
        }
        mtrace('[create_scorm_module] SCORM package uploaded successfully');

        // Update course section sequence.
        $this->update_course_section_sequence($cm->id, $sectionrecord->id);

        // Rebuild course cache.
        rebuild_course_cache($course->id, true);

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
        global $CFG, $DB;

        mtrace('[upload_scorm_package] Starting upload - SCORM ID: ' . $scormid . ', CM ID: ' . $cmid);
        mtrace('[upload_scorm_package] ZIP file: ' . $zipfile . ' (' . filesize($zipfile) . ' bytes)');

        require_once($CFG->dirroot . '/mod/scorm/locallib.php');

        $fs = get_file_storage();
        $context = \context_module::instance($cmid);
        mtrace('[upload_scorm_package] Context ID: ' . $context->id);

        // Delete existing package files.
        $fs->delete_area_files($context->id, 'mod_scorm', 'package');
        mtrace('[upload_scorm_package] Existing package files deleted');

        // Create file record.
        $filerecord = [
            'contextid' => $context->id,
            'component' => 'mod_scorm',
            'filearea'  => 'package',
            'itemid'    => 0,
            'filepath'  => '/',
            'filename'  => basename($zipfile),
            'timecreated' => time(),
            'timemodified' => time(),
        ];

        // Create the file from pathname.
        mtrace('[upload_scorm_package] Creating file in storage...');
        $storedfile = $fs->create_file_from_pathname($filerecord, $zipfile);

        if (!$storedfile) {
            mtrace('[upload_scorm_package ERROR] Failed to create stored file');
            return false;
        }
        mtrace('[upload_scorm_package] File stored successfully, hash: ' . $storedfile->get_contenthash());

        // Get the full SCORM record from database.
        $scorm = $DB->get_record('scorm', ['id' => $scormid], '*', \MUST_EXIST);
        mtrace('[upload_scorm_package] SCORM record retrieved');
        
        // Set required properties for scorm_parse.
        $scorm->pkgtype = 'zip';
        $scorm->reference = $storedfile->get_filename();
        $scorm->sha1hash = $storedfile->get_contenthash();
        $scorm->revision = 0;
        mtrace('[upload_scorm_package] SCORM properties set for parsing');
        
        // Parse the SCORM package - this extracts and processes the content.
        mtrace('[upload_scorm_package] Starting SCORM package parsing...');
        if (scorm_parse($scorm, false)) {
            mtrace('[upload_scorm_package] SCORM package parsed successfully');
            // Update the scorm record with parsed data.
            $DB->update_record('scorm', $scorm);
            mtrace('[upload_scorm_package] SCORM record updated with parsed data');
            return true;
        }
        
        mtrace('[upload_scorm_package ERROR] SCORM parsing failed');
        return false;
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

        $section = $DB->get_record('course_sections', ['id' => $sectionid], '*', \MUST_EXIST);

        $sequence = !empty($section->sequence) ? explode(',', $section->sequence) : [];
        $sequence[] = $cmid;
        $section->sequence = implode(',', $sequence);

        $DB->update_record('course_sections', $section);
    }
}

