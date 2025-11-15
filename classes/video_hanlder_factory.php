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
 * Video Handler Factory class.
 *
 * Factory for creating video handler instances based on video type.
 *
 * @package   local_scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_scormvideomaker;

defined('MOODLE_INTERNAL') || die();

/**
 * Video Handler Factory.
 *
 * @package local_scormvideomaker
 */
class video_handler_factory {

    /**
     * Get video handler instance for video type.
     *
     * @param string $videotype The video type
     * @return video_handler_interface Handler instance
     * @throws \moodle_exception
     */
    public static function get_handler(string $videotype): video_handler_interface {
        $videotype = strtolower($videotype);

        switch ($videotype) {
            case 'vimeo':
                return new video_handler_vimeo();
            case 'youtube':
                return new video_handler_youtube();
            case 'hls':
                return new video_handler_hls();
            default:
                throw new \moodle_exception(
                    'error_invalid_video_url',
                    'local_scormvideomaker'
                );
        }
    }
}

