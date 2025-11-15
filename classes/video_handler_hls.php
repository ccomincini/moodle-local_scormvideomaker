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
 * HLS Video Handler class.
 *
 * Handles HLS (HTTP Live Streaming) video streams.
 *
 * @package   local_scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_scormvideomaker;

defined('MOODLE_INTERNAL') || die();

/**
 * HLS Video Handler.
 *
 * @package local_scormvideomaker
 */
class video_handler_hls implements video_handler_interface {

    /**
     * Process HLS video URL.
     *
     * Accepts full M3U8 stream URL.
     *
     * @param string $videourl The full HLS stream URL
     * @return array Array with 'videoid' and 'videourl' keys
     * @throws \moodle_exception
     */
    public function process_video_url(string $videourl): array {
        // Validate URL format.
        if (!$this->is_valid_hls_url($videourl)) {
            throw new \moodle_exception(
                'error_invalid_video_url',
                'local_scormvideomaker'
            );
        }

        // Generate a simple ID from the URL.
        $videoid = md5($videourl);

        return [
            'videoid' => $videoid,
            'videourl' => $videourl,
        ];
    }

    /**
     * Validate HLS URL.
     *
     * @param string $url The URL to validate
     * @return bool
     */
    private function is_valid_hls_url(string $url): bool {
        // Check if it's a valid URL.
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check if it ends with .m3u8 or contains it in the path.
        if (strpos($url, '.m3u8') === false) {
            return false;
        }

        return true;
    }
}

