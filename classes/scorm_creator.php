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
 * @copyright 2025 Carlo Comincini - Invisiblefarm s.r.l. <c.comincini@invisiblefarm.com>
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
        global $DB;

        debugging('SCORM Creator: Form data received - ' . json_encode($formdata), DEBUG_DEVELOPER);

        // Validate course.
        try {
            $course = $DB->get_record('course', ['id' => $formdata->courseid], '*', \MUST_EXIST);
        } catch (\Exception $e) {
            $error = 'Course validation failed: ' . $e->getMessage();
            debugging('[SCORM Creator ERROR] ' . $error, DEBUG_DEVELOPER);
            throw new \moodle_exception('error_invalid_course', 'local_scormvideomaker', '', null, $error);
        }

        // Generate SCORM package.
        $generator = new scorm_package_generator();
        $zipfile = $generator->generate_scorm_package($formdata);

        if (!$zipfile || !file_exists($zipfile)) {
            $error = 'SCORM package generation failed - no zip file created';
            debugging('[SCORM Creator ERROR] ' . $error, DEBUG_DEVELOPER);
            throw new \moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker', '', null, $error);
        }

        try {
            // Create course module.
            $scormid = $this->create_scorm_module($course, $formdata, $zipfile);

            if ($scormid) {
                // Clean up temp file.
                if (file_exists($zipfile)) {
                    unlink($zipfile);
                }
                return $scormid;
            }

            throw new \moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker');

        } catch (\Exception $e) {
            // Clean up temp file on error.
            if (file_exists($zipfile)) {
                unlink($zipfile);
            }
            throw new \moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker', '', null, $e->getMessage());
        }
    }

    /**
     * Create a SCORM course module with fixed settings.
     *
     * @param object $course The course object
     * @param object $formdata The form data
     * @param string $zipfile Path to the SCORM package ZIP file
     * @return int|bool The SCORM activity ID or false on failure
     */
    private function create_scorm_module(object $course, object $formdata, string $zipfile) {
        global $DB, $CFG;

        require_once($CFG->dirroot . '/course/lib.php');

        // Get the section.
        $section = $formdata->section ?? 0;
        $sectionrecord = $DB->get_record(
            'course_sections',
            ['course' => $course->id, 'section' => $section],
            '*'
        );

        if (!$sectionrecord) {
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
        }

        // Create course module record with FIXED settings.
        $cm = new \stdClass();
        $cm->course = $course->id;
        
        try {
            $cm->module = $DB->get_field('modules', 'id', ['name' => 'scorm'], \MUST_EXIST);
        } catch (\Exception $e) {
            throw new \moodle_exception('error_scorm_creation_failed', 'local_scormvideomaker', '', null, 
                'SCORM module not installed');
        }
        
        $cm->section = $sectionrecord->id;
        $cm->instance = 0; // Will be updated after SCORM is created.
        
        // FIXED CM settings.
        $cm->visible = 1;
        $cm->visibleold = 1;
        $cm->visibleoncoursepage = 1;
        $cm->showdescription = 1;
        $cm->indent = 0;
        $cm->groupmode = 0;
        $cm->groupingid = 0;
        
        // Completion tracking: require activity completion.
        $cm->completion = \COMPLETION_TRACKING_AUTOMATIC;
        $cm->completionview = 0;
        $cm->completionexpected = 0;
        $cm->completiongradeitemnumber = null;
        
        $cm->added = time();
        $cm->id = $DB->insert_record('course_modules', $cm);

        // Create context.
        $context = \context_module::instance($cm->id);

        // Create SCORM instance with FIXED settings.
        $scorm = new \stdClass();
        $scorm->course = $course->id;
        $scorm->name = $formdata->title;
        $scorm->intro = $formdata->description ?? '';
        $scorm->introformat = \FORMAT_HTML;
        
        // FIXED SCORM settings as requested.
        $scorm->version = 'SCORM_1.2';
        $scorm->maxgrade = 100; // Max grade is 100, but actual grade = percentage watched.
        $scorm->grademethod = 1; // Highest grade.
        $scorm->maxattempt = 1; // 1 attempt only.
        $scorm->whatgrade = 0; // Highest attempt.
        $scorm->displaycoursestructure = 0;
        $scorm->skipview = 2; // Always skip.
        $scorm->hidebrowse = 1;
        $scorm->hidetoc = 3; // Always hide.
        $scorm->nav = 0;
        $scorm->auto = 0; // NO Auto-continue.
        $scorm->popup = 0;
        $scorm->width = 100;
        $scorm->height = 600;
        $scorm->displayattemptstatus = 1;
        $scorm->timemodified = time();
        $scorm->timeopen = 0;
        $scorm->timeclose = 0;
        
        // COMPLETION SETTINGS
        // The grade is ALWAYS the percentage watched (e.g., 75 if watched 75%).
        // The completion_percentage controls WHEN the activity is marked as "completed".
        // Example: If completion_percentage = 50:
        //   - User watches 50% → status = completed, grade = 50
        //   - User watches 75% → status = completed, grade = 75
        //   - User watches 30% → status = incomplete, grade = 30
        $scorm->completionstatusrequired = 4; // Require "completed" status.
        $scorm->completionstatusallscos = 0;
        $scorm->completionscorerequired = 0;
        
        if ($formdata->completion_type === 'percentage') {
            // Complete when user reaches the specified percentage.
            // Grade will be the actual percentage watched.
            $scorm->completionscorerequired = intval($formdata->completion_percentage ?? 100);
        } else {
            // Complete only at end (100%).
            // Grade will be 100 when completed.
            $scorm->completionscorerequired = 100;
        }
        
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
            'filename'  => basename($zipfile),
            'timecreated' => time(),
            'timemodified' => time(),
        ];

        // Create the file from pathname.
        $storedfile = $fs->create_file_from_pathname($filerecord, $zipfile);

        if (!$storedfile) {
            return false;
        }

        // Get the full SCORM record from database.
        $scorm = $DB->get_record('scorm', ['id' => $scormid], '*', \MUST_EXIST);
        
        // Set required properties for scorm_parse.
        $scorm->pkgtype = 'zip';
        $scorm->reference = $storedfile->get_filename();
        $scorm->sha1hash = $storedfile->get_contenthash();
        $scorm->revision = 0;
        
        // Parse the SCORM package - this extracts content to 'content' filearea.
        scorm_parse($scorm, false);
        
        // Check if SCOs were created.
        $scos = $DB->get_records('scorm_scoes', ['scorm' => $scormid]);
        
        if (!empty($scos) && !empty($scorm->launch)) {
            // Update the scorm record with parsed data.
            $DB->update_record('scorm', $scorm);
            
            // SECURITY: Delete the ZIP file from 'package' filearea after successful parsing.
            // The extracted content is now in 'content' filearea and that's all we need.
            $fs->delete_area_files($context->id, 'mod_scorm', 'package');
            
            return true;
        }
        
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
