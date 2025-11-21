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
 * External function to get courses by category.
 *
 * @package   local_scormvideomaker
 * @copyright 2025 Carlo Comincini - Invisiblefarm s.r.l. <c.comincini@invisiblefarm.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_scormvideomaker\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_multiple_structure;
use external_single_structure;

/**
 * Get courses by category external function.
 *
 * @package local_scormvideomaker
 */
class get_courses_by_category extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'categoryid' => new external_value(PARAM_INT, 'Category ID'),
        ]);
    }

    /**
     * Get courses for a category.
     *
     * @param int $categoryid Category ID
     * @return array Courses list
     */
    public static function execute(int $categoryid): array {
        global $DB;

        // Validate parameters.
        $params = self::validate_parameters(self::execute_parameters(), [
            'categoryid' => $categoryid,
        ]);

        // Check capability - use course category context instead of system.
        $context = \context_coursecat::instance($params['categoryid']);
        self::validate_context($context);
        // Check if user can view courses in this category.
        require_capability('moodle/category:viewcourselist', $context);

        $courses = [];

        if ($params['categoryid'] <= 0) {
            return $courses;
        }

        // Get all courses in this category (including hidden).
        $sql = "SELECT id, fullname, shortname, visible 
                FROM {course} 
                WHERE category = :categoryid 
                ORDER BY fullname ASC";
        
        $courselist = $DB->get_records_sql($sql, ['categoryid' => $params['categoryid']]);

        foreach ($courselist as $course) {
            $name = $course->fullname;
            if (!$course->visible) {
                $name .= ' (' . get_string('hidden', 'local_scormvideomaker') . ')';
            }
            $courses[] = [
                'id' => $course->id,
                'name' => $name,
            ];
        }

        return $courses;
    }

    /**
     * Returns description of method result value.
     *
     * @return external_multiple_structure
     */
    public static function execute_returns(): external_multiple_structure {
        return new external_multiple_structure(
            new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Course ID'),
                'name' => new external_value(PARAM_TEXT, 'Course name'),
            ])
        );
    }
}
