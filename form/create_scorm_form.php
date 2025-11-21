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
 * @copyright 2025 Carlo Comincini - Invisiblefarm s.r.l. <c.comincini@invisiblefarm.com>
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
        global $PAGE;
        
        $mform = $this->_form;

        // Course selection.
        $mform->addElement('header', 'courseheader', get_string('form_course', 'local_scormvideomaker'));

        // Category selection.
        $categories = $this->get_available_categories();
        $mform->addElement('select', 'categoryid', get_string('form_category', 'local_scormvideomaker'), $categories);
        $mform->addRule('categoryid', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('categoryid', 'form_category', 'local_scormvideomaker');

        // Course selection (will be populated based on category).
        // Use simple text instead of get_string() in array
        $mform->addElement('select', 'courseid', get_string('form_course', 'local_scormvideomaker'), 
                ['' => get_string('choosecourse', 'local_scormvideomaker')]);

        $mform->addHelpButton('courseid', 'form_course', 'local_scormvideomaker');
        $mform->setType('courseid', PARAM_INT);

        // Section selection (optional).
        $mform->addElement('text', 'section', get_string('form_section', 'local_scormvideomaker'), ['size' => 3]);
        $mform->setType('section', PARAM_INT);
        $mform->setDefault('section', 0);
        $mform->addHelpButton('section', 'form_section', 'local_scormvideomaker');

        // Add JavaScript to handle category change.
        $PAGE->requires->js_call_amd('local_scormvideomaker/category_course_selector', 'init');

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
        $mform->setDefault('seekbar', get_config('local_scormvideomaker', 'default_seekbar'));
        $mform->addHelpButton('seekbar', 'form_seekbar', 'local_scormvideomaker');

        // Completion type.
        $completiontypes = [
            'end' => get_string('completion_end', 'local_scormvideomaker'),
            'percentage' => get_string('completion_percentage', 'local_scormvideomaker'),
        ];
        $mform->addElement('select', 'completion_type', get_string('form_completion_type', 'local_scormvideomaker'), $completiontypes);
        $mform->setDefault('completion_type', get_config('local_scormvideomaker', 'default_completion_type'));
        $mform->addHelpButton('completion_type', 'form_completion_type', 'local_scormvideomaker');

        // Completion percentage (conditional).
        $mform->addElement('text', 'completion_percentage', get_string('form_completion_percentage', 'local_scormvideomaker'), ['size' => 3]);
        $mform->setType('completion_percentage', PARAM_INT);
        $mform->setDefault('completion_percentage', get_config('local_scormvideomaker', 'default_completion_percentage'));
        $mform->hideIf('completion_percentage', 'completion_type', 'neq', 'percentage');
        $mform->addHelpButton('completion_percentage', 'form_completion_percentage', 'local_scormvideomaker');

        // Autoplay option.
        $mform->addElement('checkbox', 'autoplay', get_string('form_autoplay', 'local_scormvideomaker'));
        $mform->setType('autoplay', PARAM_BOOL);
        $mform->setDefault('autoplay', get_config('local_scormvideomaker', 'default_autoplay'));
        $mform->addHelpButton('autoplay', 'form_autoplay', 'local_scormvideomaker');

        // Restrict access (Conditional Access).
        $mform->addElement('header', 'restrictaccessheader', get_string('restrictaccess', 'availability'));

        // Enable restrict access.
        $mform->addElement('checkbox', 'enablerestrictaccess', get_string('form_enable_restrict_access', 'local_scormvideomaker'));
        $mform->setType('enablerestrictaccess', PARAM_BOOL);
        $mform->addHelpButton('enablerestrictaccess', 'form_enable_restrict_access', 'local_scormvideomaker');

        // Activity completion dependency.
        $mform->addElement('select', 'dependencycmid', get_string('form_dependency_activity', 'local_scormvideomaker'),
            ['' => get_string('form_select_course_first', 'local_scormvideomaker')]);
        $mform->setType('dependencycmid', PARAM_INT);
        $mform->hideIf('dependencycmid', 'enablerestrictaccess');
        $mform->addHelpButton('dependencycmid', 'form_dependency_activity', 'local_scormvideomaker');

        // Require completion.
        $mform->addElement('checkbox', 'dependencycompletion', get_string('form_require_completion', 'local_scormvideomaker'));
        $mform->setType('dependencycompletion', PARAM_BOOL);
        $mform->setDefault('dependencycompletion', 1);
        $mform->hideIf('dependencycompletion', 'enablerestrictaccess');
        $mform->hideIf('dependencycompletion', 'dependencycmid', 'eq', '');
        $mform->addHelpButton('dependencycompletion', 'form_require_completion', 'local_scormvideomaker');

        // Form buttons.
        $this->add_action_buttons(true, get_string('createscorm', 'local_scormvideomaker'));
    }

    /**
     * Called after the form data has been set.
     * This method repopulates the course select with the submitted values
     * to prevent validation errors on dynamically loaded options.
     *
     * @return void
     */
    public function definition_after_data() {
        global $DB;
        
        $mform = $this->_form;
        
        // Ottieni i valori inviati
        $categoryid = $mform->getSubmitValue('categoryid');
        $courseid = $mform->getSubmitValue('courseid');
        
        // Se c'è una categoria, ripopola i corsi
        if ($categoryid) {
            $courses = self::get_courses_by_category($categoryid);
            if (!empty($courses)) {
                $courseelement = $mform->getElement('courseid');
                
                // Ricrea l'elemento con tutte le opzioni
                // IMPORTANTE: usa array semplice, non get_string() qui
                // Ricrea l'elemento con tutte le opzioni
                $options = ['' => get_string('choosecourse', 'local_scormvideomaker')] + $courses;

                // Aggiorna le opzioni dell'elemento
                $courseelement->removeOptions();
                $courseelement->loadArray($options);
            }
        }

        // Se c'è un corso selezionato, ripopola le attività per la dipendenza
        if ($courseid) {
            $activities = self::get_course_activities($courseid);
            if (!empty($activities)) {
                $dependencyelement = $mform->getElement('dependencycmid');
                
                // Ricrea l'elemento con tutte le attività
                $options = ['' => get_string('form_no_dependency', 'local_scormvideomaker')] + $activities;

                // Aggiorna le opzioni dell'elemento
                $dependencyelement->removeOptions();
                $dependencyelement->loadArray($options);
            }
        }
    }

    /**
     * Validate form data.
     *
     * @param array $data Form data
     * @param array $files Uploaded files
     * @return array Validation errors
     */
    public function validation($data, $files) {
        global $DB;
        
        $errors = parent::validation($data, $files);

        // Validate courseid is present and exists in database.
        if (empty($data['courseid'])) {
            $errors['courseid'] = get_string('error_select_course', 'local_scormvideomaker');
        } else if (!$DB->record_exists('course', ['id' => $data['courseid']])) {
            $errors['courseid'] = get_string('error_invalid_course', 'local_scormvideomaker');
        }

        // Validate completion percentage.
        if ($data['completion_type'] === 'percentage') {
            $percentage = intval($data['completion_percentage'] ?? 100);
            if ($percentage < 0 || $percentage > 100) {
                $errors['completion_percentage'] = get_string('error_invalid_completion_percentage', 'local_scormvideomaker');
            }
        }

        // Validate section number.
        if (isset($data['section']) && $data['section'] < 0) {
            $errors['section'] = get_string('error_invalid_section', 'local_scormvideomaker');
        }

        return $errors;
    }

    /**
     * Get available categories.
     *
     * @return array Categories array
     */
    private function get_available_categories(): array {
        global $DB;

        // Start with empty option - chiamata get_string() QUI funziona
        $categories = ['' => get_string('choosecategory', 'local_scormvideomaker')];

        // Get all categories.
        $allcategories = $DB->get_records('course_categories', null, 'name ASC', 'id, name, parent, path');
        
        // Build hierarchical list with indentation.
        foreach ($allcategories as $category) {
            // Calculate depth for indentation.
            $depth = substr_count($category->path, '/');
            $indent = str_repeat('  ', $depth - 1); // Use spaces, not &nbsp;
            $categories[$category->id] = $indent . $category->name;
        }

        return $categories;
    }

    /**
     * Get available courses for a category (includes hidden courses).
     *
     * @param int $categoryid Category ID
     * @return array Courses array indexed by course ID
     */
    public static function get_courses_by_category(int $categoryid): array {
        global $DB;

        $courses = [];

        if ($categoryid <= 0) {
            return $courses;
        }

        // Get all courses in this category (including hidden).
        $sql = "SELECT id, fullname, shortname, visible 
                FROM {course} 
                WHERE category = :categoryid 
                ORDER BY fullname ASC";
        
        $courselist = $DB->get_records_sql($sql, ['categoryid' => $categoryid]);

        foreach ($courselist as $course) {
            $name = $course->fullname;
            if (!$course->visible) {
                $name .= ' (' . get_string('hidden', 'local_scormvideomaker') . ')';
            }
            $courses[$course->id] = $name;
        }

        return $courses;
    }

    /**
     * Get activities from a course that support completion.
     *
     * @param int $courseid Course ID
     * @return array Activities array indexed by course module ID
     */
    public static function get_course_activities(int $courseid): array {
        global $DB;

        $activities = [];

        if ($courseid <= 0) {
            return $activities;
        }

        // Get all course modules with completion enabled.
        $sql = "SELECT cm.id, cm.instance, cm.module, cm.section, m.name as modname
                FROM {course_modules} cm
                JOIN {modules} m ON m.id = cm.module
                WHERE cm.course = :courseid
                  AND cm.deletioninprogress = 0
                  AND cm.completion > 0
                ORDER BY cm.section ASC, cm.id ASC";
        
        $modulelist = $DB->get_records_sql($sql, ['courseid' => $courseid]);

        foreach ($modulelist as $module) {
            // Get the actual activity name from the module table.
            $activityname = $DB->get_field($module->modname, 'name', ['id' => $module->instance]);
            
            if ($activityname) {
                // Format: "Section X: Activity Name (type)".
                $sectionnum = $DB->get_field('course_sections', 'section', ['id' => $module->section]);
                $typename = get_string('modulename', $module->modname);
                $activities[$module->id] = get_string('section') . ' ' . $sectionnum . ': ' . $activityname . ' (' . $typename . ')';
            }
        }

        return $activities;
    }
}
