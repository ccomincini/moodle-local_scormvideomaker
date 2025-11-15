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
 * Form for creating SCORM video packages.
 *
 * @package   local_scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * SCORM Video Package Creation Form.
 *
 * @package local_scormvideomaker
 */
class local_scormvideomaker_create_scorm_form extends moodleform {

    /**
     * Define form fields.
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;

        // Course selection.
        $mform->addElement('header', 'courseheader', get_string('form_course', 'local_scormvideomaker'));

        $courses = $this->get_available_courses();
        $mform->addElement('select', 'course', get_string('form_course', 'local_scormvideomaker'), $courses);
        $mform->addRule('course', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('course', 'form_course', 'local_scormvideomaker');

        // Video configuration.
        $mform->addElement('header', 'videoheader', get_string('pluginname', 'local_scormvideomaker'));

        $mform->addElement('text', 'title', get_string('form_title', 'local_scormvideomaker'), ['size' => 50]);
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('title', 'form_title', 'local_scormvideomaker');

        $mform->addElement('textarea', 'description', get_string('form_description', 'local_scormvideomaker'),
            ['rows' => 4, 'cols' => 50]);
        $mform->setType('description', PARAM_RAW);
        $mform->addHelpButton('description', 'form_description', 'local_scormvideomaker');

        // Video type selection.
        $videotypes = [
            'vimeo' => get_string('videotype_vimeo', 'local_scormvideomaker'),
            'youtube' => get_string('videotype_youtube', 'local_scormvideomaker'),
            'hls' => get_string('videotype_hls', 'local_scormvideomaker'),
        ];
        $mform->addElement('select', 'videotype', get_string('form_videotype', 'local_scormvideomaker'), $videotypes);
        $mform->setDefault('videotype', 'vimeo');
        $mform->addHelpButton('videotype', 'form_videotype', 'local_scormvideomaker');

        // Video URL.
        $mform->addElement('text', 'videourl', get_string('form_videourl', 'local_scormvideomaker'), ['size' => 100]);
        $mform->setType('videourl', PARAM_RAW);
        $mform->addRule('videourl', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('videourl', 'form_videourl', 'local_scormvideomaker');

        // Seek bar option.
        $seekbaroptions = [
            'locked' => get_string('seekbar_locked', 'local_scormvideomaker'),
            'free' => get_string('seekbar_free', 'local_scormvideomaker'),
            'backward' => get_string('seekbar_backward', 'local_scormvideomaker'),
        ];
        $mform->addElement('select', 'seekbar', get_string('form_seekbar', 'local_scormvideomaker'), $seekbaroptions);
        $mform->setDefault('seekbar', 'locked');
        $mform->addHelpButton('seekbar', 'form_seekbar', 'local_scormvideomaker');

        // Completion type.
        $completiontypes = [
            'end' => get_string('completion_end', 'local_scormvideomaker'),
            'percentage' => get_string('completion_percentage', 'local_scormvideomaker'),
        ];
        $mform->addElement('select', 'completion_type', get_string('form_completion_type', 'local_scormvideomaker'), $completiontypes);
        $mform->setDefault('completion_type', 'end');
        $mform->addHelpButton('completion_type', 'form_completion_type', 'local_scormvideomaker');

        // Completion percentage (conditional).
        $mform->addElement('text', 'completion_percentage', get_string('form_completion_percentage', 'local_scormvideomaker'), ['size' => 3]);
        $mform->setType('completion_percentage', PARAM_INT);
        $mform->setDefault('completion_percentage', 100);
        $mform->hideIf('completion_percentage', 'completion_type', 'neq', 'percentage');
        $mform->addHelpButton('completion_percentage', 'form_completion_percentage', 'local_scormvideomaker');

        // Autoplay option.
        $mform->addElement('checkbox', 'autoplay', get_string('form_autoplay', 'local_scormvideomaker'));
        $mform->setDefault('autoplay', 0);
        $mform->addHelpButton('autoplay', 'form_autoplay', 'local_scormvideomaker');

        // SCORM Settings Header.
        $mform->addElement('header', 'scormheader', get_string('section_scorm_settings', 'local_scormvideomaker'));
        $mform->setExpanded('scormheader', false);

        // SCORM Version.
        $scormversions = [
            'SCORM_1.2' => get_string('scorm_version_12', 'local_scormvideomaker'),
            'SCORM_2004' => get_string('scorm_version_2004', 'local_scormvideomaker'),
        ];
        $mform->addElement('select', 'scorm_version', get_string('scorm_version', 'local_scormvideomaker'), $scormversions);
        $mform->setDefault('scorm_version', 'SCORM_1.2');

        // Maximum grade.
        $mform->addElement('text', 'scorm_maxgrade', get_string('scorm_maxgrade', 'local_scormvideomaker'), ['size' => 3]);
        $mform->setType('scorm_maxgrade', PARAM_INT);
        $mform->setDefault('scorm_maxgrade', 100);

        // Grading method.
        $grademethods = [
            1 => get_string('scorm_grademethod_high', 'local_scormvideomaker'),
            2 => get_string('scorm_grademethod_avg', 'local_scormvideomaker'),
            3 => get_string('scorm_grademethod_sum', 'local_scormvideomaker'),
        ];
        $mform->addElement('select', 'scorm_grademethod', get_string('scorm_grademethod', 'local_scormvideomaker'), $grademethods);
        $mform->setDefault('scorm_grademethod', 1);

        // Maximum attempts.
        $attemptoptions = [];
        for ($i = 1; $i <= 10; $i++) {
            $attemptoptions[$i] = $i;
        }
        $attemptoptions[0] = get_string('scorm_maxattempt_unlimited', 'local_scormvideomaker');
        $mform->addElement('select', 'scorm_maxattempt', get_string('scorm_maxattempt', 'local_scormvideomaker'), $attemptoptions);
        $mform->setDefault('scorm_maxattempt', 0);

        // What grade to report.
        $whatgradeoptions = [
            0 => get_string('scorm_whatgrade_high', 'local_scormvideomaker'),
            1 => get_string('scorm_whatgrade_first', 'local_scormvideomaker'),
            2 => get_string('scorm_whatgrade_last', 'local_scormvideomaker'),
            3 => get_string('scorm_whatgrade_avg', 'local_scormvideomaker'),
        ];
        $mform->addElement('select', 'scorm_whatgrade', get_string('scorm_whatgrade', 'local_scormvideomaker'), $whatgradeoptions);
        $mform->setDefault('scorm_whatgrade', 0);

        // Display course structure.
        $mform->addElement('checkbox', 'scorm_displaycoursestructure', get_string('scorm_displaycoursestructure', 'local_scormvideomaker'));
        $mform->setDefault('scorm_displaycoursestructure', 0);

        // Skip view page.
        $skipviewoptions = [
            0 => get_string('scorm_skipview_never', 'local_scormvideomaker'),
            1 => get_string('scorm_skipview_first', 'local_scormvideomaker'),
            2 => get_string('scorm_skipview_always', 'local_scormvideomaker'),
        ];
        $mform->addElement('select', 'scorm_skipview', get_string('scorm_skipview', 'local_scormvideomaker'), $skipviewoptions);
        $mform->setDefault('scorm_skipview', 2);

        // Hide browse button.
        $mform->addElement('checkbox', 'scorm_hidebrowse', get_string('scorm_hidebrowse', 'local_scormvideomaker'));
        $mform->setDefault('scorm_hidebrowse', 1);

        // Hide TOC.
        $hidetococoptions = [
            0 => get_string('scorm_hidetoc_never', 'local_scormvideomaker'),
            1 => get_string('scorm_hidetoc_structure', 'local_scormvideomaker'),
            3 => get_string('scorm_hidetoc_all', 'local_scormvideomaker'),
        ];
        $mform->addElement('select', 'scorm_hidetoc', get_string('scorm_hidetoc', 'local_scormvideomaker'), $hidetococoptions);
        $mform->setDefault('scorm_hidetoc', 3);

        // Show navigation bar.
        $mform->addElement('checkbox', 'scorm_nav', get_string('scorm_nav', 'local_scormvideomaker'));
        $mform->setDefault('scorm_nav', 1);

        // Auto commit.
        $mform->addElement('checkbox', 'scorm_auto', get_string('scorm_auto', 'local_scormvideomaker'));
        $mform->setDefault('scorm_auto', 0);

        // Visibility.
        $mform->addElement('checkbox', 'visible', get_string('visible', 'moodle'));
        $mform->setDefault('visible', 1);

        // Form buttons.
        $this->add_action_buttons(true, get_string('createscorm', 'local_scormvideomaker'));
    }

    /**
     * Validate form data.
     *
     * @param array $data Form data
     * @param array $files Uploaded files
     * @return array Validation errors
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Validate completion percentage.
        if ($data['completion_type'] === 'percentage') {
            $percentage = intval($data['completion_percentage'] ?? 100);
            if ($percentage < 0 || $percentage > 100) {
                $errors['completion_percentage'] = get_string('error_invalid_completion_percentage', 'local_scormvideomaker');
            }
        }

        return $errors;
    }

    /**
     * Get available courses for current user.
     *
     * @return array Courses array
     */
    private function get_available_courses(): array {
        global $USER, $DB;

        $courses = [];
        $context = context_system::instance();

        // Get all courses where user has capability to add activities.
        $usercourses = enrol_get_users_courses($USER->id);

        foreach ($usercourses as $course) {
            $coursecontext = context_course::instance($course->id);
            if (has_capability('moodle/course:manageactivities', $coursecontext)) {
                $courses[$course->id] = $course->fullname;
            }
        }

        // Sort by name.
        asort($courses);

        return $courses;
    }
}

