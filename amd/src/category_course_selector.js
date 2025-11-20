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
 * Category and course selector for SCORM Video Maker.
 *
 * @module     local_scormvideomaker/category_course_selector
 * @copyright  2025 Carlo Comincini <carlo@comincini.it>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {

    return {
        /**
         * Initialize the category/course selector.
         */
        init: function() {
            var categorySelect = $('#id_categoryid');
            var courseSelect = $('#id_courseid');

            if (!categorySelect.length || !courseSelect.length) {
                return;
            }

            // Handle category change.
            categorySelect.on('change', function() {
                var categoryId = $(this).val();
                
                // Clear current courses.
                courseSelect.empty();
                courseSelect.append($('<option>', {
                    value: '',
                    text: M.util.get_string('choosedots', 'moodle')
                }));

                if (!categoryId) {
                    return;
                }

                // Load courses for selected category.
                var promises = Ajax.call([{
                    methodname: 'local_scormvideomaker_get_courses_by_category',
                    args: {
                        categoryid: parseInt(categoryId)
                    }
                }]);

                promises[0].done(function(courses) {
                    $.each(courses, function(index, course) {
                        courseSelect.append($('<option>', {
                            value: course.id,
                            text: course.name
                        }));
                    });
                }).fail(function(error) {
                    Notification.exception(error);
                });
            });
        }
    };
});
