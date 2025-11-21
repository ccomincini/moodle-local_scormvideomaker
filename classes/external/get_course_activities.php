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
 * External service to get course activities.
 *
 * @package   local_scormvideomaker
 * @copyright 2025 Carlo Comincini - Invisiblefarm s.r.l. <c.comincini@invisiblefarm.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_scormvideomaker\external;

use context_course;
use external_api;
use external_function_parameters;
use external_value;
use external_multiple_structure;
use external_single_structure;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * External service to get course activities with completion enabled.
 *
 * @package local_scormvideomaker
 */
class get_course_activities extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course ID'),
        ]);
    }

    /**
     * Get activities from a course that have completion enabled.
     *
     * @param int $courseid Course ID
     * @return array List of activities
     */
    public static function execute($courseid) {
        global $DB;

        // Validate parameters.
        $params = self::validate_parameters(self::execute_parameters(), [
            'courseid' => $courseid,
        ]);

        $courseid = $params['courseid'];

        // Verify course exists and user has access.
        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
        $context = context_course::instance($courseid);
        self::validate_context($context);

        require_capability('moodle/course:view', $context);

        $activities = [];

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
                // Get section number.
                $sectionnum = $DB->get_field('course_sections', 'section', ['id' => $module->section]);
                
                // Get module type name.
                $typename = get_string('modulename', $module->modname);
                
                // Format: "Section X: Activity Name (type)".
                $displayname = get_string('section') . ' ' . $sectionnum . ': ' . $activityname . ' (' . $typename . ')';
                
                $activities[] = [
                    'id' => $module->id,
                    'name' => $displayname,
                ];
            }
        }

        return $activities;
    }

    /**
     * Returns description of method result value.
     *
     * @return external_multiple_structure
     */
    public static function execute_returns() {
        return new external_multiple_structure(
            new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Course module ID'),
                'name' => new external_value(PARAM_TEXT, 'Activity display name'),
            ])
        );
    }
}
