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
 * YouTube Video Handler class.
 *
 * Handles YouTube video URLs.
 *
 * @package   local_scormvideomaker
 * @copyright 2025 Carlo Comincini - Invisiblefarm s.r.l. <c.comincini@invisiblefarm.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_scormvideomaker;

defined('MOODLE_INTERNAL') || die();

/**
 * YouTube Video Handler.
 *
 * @package local_scormvideomaker
 */
class video_handler_youtube implements video_handler_interface {

    /**
     * Process YouTube video URL.
     *
     * Accepts YouTube video ID (e.g., dQw4w9WgXcQ) or full URL.
     *
     * @param string $videourl The video URL or ID
     * @return array Array with 'videoid' and 'videourl' keys
     * @throws \moodle_exception
     */
    public function process_video_url(string $videourl): array {
        // Extract video ID from URL or use as-is if it's just an ID.
        $videoid = $this->extract_youtube_id($videourl);

        if (empty($videoid)) {
            throw new \moodle_exception(
                'error_invalid_video_url',
                'local_scormvideomaker'
            );
        }

        $embedurl = 'https://www.youtube.com/embed/' . $videoid;

        return [
            'videoid' => $videoid,
            'videourl' => $embedurl,
        ];
    }

    /**
     * Extract YouTube video ID from URL or return as-is if it's an ID.
     *
     * @param string $input The input URL or ID
     * @return string|false The video ID or false if invalid
     */
    private function extract_youtube_id(string $input) {
        // If it's 11 characters alphanumeric, assume it's the video ID.
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }

        // Try to extract from various YouTube URL formats.
        $patterns = [
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/v\/([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input, $matches)) {
                return $matches[1];
            }
        }

        return false;
    }
}

