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
 * Vimeo Video Handler class.
 *
 * Handles Vimeo video URLs.
 *
 * @package   local_scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_scormvideomaker;

defined('MOODLE_INTERNAL') || die();

/**
 * Vimeo Video Handler.
 *
 * @package local_scormvideomaker
 */
class video_handler_vimeo implements video_handler_interface {

    /**
     * Process Vimeo video URL.
     *
     * Accepts Vimeo video ID (e.g., 123456789) or full URL.
     *
     * @param string $videourl The video URL or ID
     * @return array Array with 'videoid' and 'videourl' keys
     * @throws \moodle_exception
     */
    public function process_video_url(string $videourl): array {
        // Extract video ID from URL or use as-is if it's just an ID.
        $videoid = $this->extract_vimeo_id($videourl);

        if (empty($videoid)) {
            throw new \moodle_exception(
                'error_invalid_video_url',
                'local_scormvideomaker'
            );
        }

        $embedurl = 'https://player.vimeo.com/video/' . $videoid;

        return [
            'videoid' => $videoid,
            'videourl' => $embedurl,
        ];
    }

    /**
     * Extract Vimeo video ID from URL or return as-is if it's an ID.
     *
     * @param string $input The input URL or ID
     * @return string|false The video ID or false if invalid
     */
    private function extract_vimeo_id(string $input) {
        // If it's just a number, assume it's the video ID.
        if (preg_match('/^\d+$/', $input)) {
            return $input;
        }

        // Try to extract from various Vimeo URL formats.
        $patterns = [
            '/(?:https?:\/\/)?(?:www\.)?vimeo\.com\/(\d+)/',
            '/(?:https?:\/\/)?vimeo\.com\/(\d+)/',
            '/(?:https?:\/\/)?player\.vimeo\.com\/video\/(\d+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input, $matches)) {
                return $matches[1];
            }
        }

        return false;
    }
}

