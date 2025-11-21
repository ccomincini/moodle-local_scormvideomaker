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
 * @copyright 2025 Carlo Comincini - Invisiblefarm s.r.l. <c.comincini@invisiblefarm.com>
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
     * Also supports private videos with hash (e.g., 123456789/abc123def or 123456789?h=abc123def).
     *
     * @param string $videourl The video URL or ID
     * @return array Array with 'videoid' and 'videourl' keys
     * @throws \moodle_exception
     */
    public function process_video_url(string $videourl): array {
        // Extract video ID and optional hash from URL.
        $result = $this->extract_vimeo_id($videourl);

        if (empty($result['videoid'])) {
            throw new \moodle_exception(
                'error_invalid_video_url',
                'local_scormvideomaker'
            );
        }

        // Build embed URL.
        $embedurl = 'https://player.vimeo.com/video/' . $result['videoid'];
        
        // Add hash parameter if present (for private videos).
        if (!empty($result['hash'])) {
            $embedurl .= '?h=' . $result['hash'];
        }

        return [
            'videoid' => $result['videoid'] . (!empty($result['hash']) ? '/' . $result['hash'] : ''),
            'videourl' => $embedurl,
        ];
    }

    /**
     * Extract Vimeo video ID and optional hash from URL or input.
     *
     * Supports formats:
     * - 123456789 (simple ID)
     * - 123456789/abc123def (ID with hash)
     * - 123456789?h=abc123def (ID with hash parameter)
     * - https://vimeo.com/123456789
     * - https://vimeo.com/manage/videos/123456789/abc123def
     * - https://player.vimeo.com/video/123456789?h=abc123def
     *
     * @param string $input The input URL or ID
     * @return array Array with 'videoid' and optional 'hash' keys, or false if invalid
     */
    private function extract_vimeo_id(string $input) {
        $result = ['videoid' => '', 'hash' => ''];
        
        // Remove whitespace.
        $input = trim($input);

        // Pattern 1: Simple numeric ID.
        if (preg_match('/^\d+$/', $input)) {
            $result['videoid'] = $input;
            return $result;
        }

        // Pattern 2: ID/hash format (e.g., 123456789/abc123def).
        if (preg_match('/^(\d+)\/(\w+)$/', $input, $matches)) {
            $result['videoid'] = $matches[1];
            $result['hash'] = $matches[2];
            return $result;
        }

        // Pattern 3: ID?h=hash format (e.g., 123456789?h=abc123def).
        if (preg_match('/^(\d+)\?h=(\w+)$/', $input, $matches)) {
            $result['videoid'] = $matches[1];
            $result['hash'] = $matches[2];
            return $result;
        }

        // Pattern 4: Full URLs with various formats.
        $patterns = [
            // https://vimeo.com/manage/videos/123456789/abc123def.
            '/vimeo\.com\/manage\/videos\/(\d+)\/(\w+)/',
            // https://player.vimeo.com/video/123456789?h=abc123def.
            '/player\.vimeo\.com\/video\/(\d+)\?h=(\w+)/',
            // https://vimeo.com/123456789/abc123def.
            '/vimeo\.com\/(\d+)\/(\w+)/',
            // https://vimeo.com/123456789 (simple public video).
            '/vimeo\.com\/(\d+)(?:[?&#]|$)/',
            // https://player.vimeo.com/video/123456789.
            '/player\.vimeo\.com\/video\/(\d+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input, $matches)) {
                $result['videoid'] = $matches[1];
                if (isset($matches[2])) {
                    $result['hash'] = $matches[2];
                }
                return $result;
            }
        }

        return false;
    }
}

