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
     * Supports timestamps and other parameters.
     *
     * @param string $videourl The video URL or ID
     * @return array Array with 'videoid' and 'videourl' keys
     * @throws \moodle_exception
     */
    public function process_video_url(string $videourl): array {
        // Extract video ID and optional parameters from URL.
        $result = $this->extract_youtube_id($videourl);

        if (empty($result['videoid'])) {
            throw new \moodle_exception(
                'error_invalid_video_url',
                'local_scormvideomaker'
            );
        }

        // Build embed URL.
        $embedurl = 'https://www.youtube.com/embed/' . $result['videoid'];
        
        // Add start parameter if timestamp is present.
        if (!empty($result['start'])) {
            $embedurl .= '?start=' . $result['start'];
        }

        return [
            'videoid' => $result['videoid'],
            'videourl' => $embedurl,
        ];
    }

    /**
     * Extract YouTube video ID and optional parameters from URL or input.
     *
     * Supports formats:
     * - dQw4w9WgXcQ (simple ID)
     * - https://www.youtube.com/watch?v=dQw4w9WgXcQ
     * - https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=30s (with timestamp)
     * - https://youtu.be/dQw4w9WgXcQ
     * - https://youtu.be/dQw4w9WgXcQ?t=30 (short URL with timestamp)
     * - https://www.youtube.com/embed/dQw4w9WgXcQ
     * - https://www.youtube.com/v/dQw4w9WgXcQ
     * - https://www.youtube.com/shorts/dQw4w9WgXcQ (YouTube Shorts)
     *
     * @param string $input The input URL or ID
     * @return array Array with 'videoid' and optional 'start' keys, or false if invalid
     */
    private function extract_youtube_id(string $input) {
        $result = ['videoid' => '', 'start' => ''];
        
        // Remove whitespace.
        $input = trim($input);

        // Pattern 1: Simple video ID (11 characters).
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            $result['videoid'] = $input;
            return $result;
        }

        // Parse URL to extract query parameters if present.
        $parsedurl = parse_url($input);
        $queryparams = [];
        if (isset($parsedurl['query'])) {
            parse_str($parsedurl['query'], $queryparams);
        }

        // Try to extract from various YouTube URL formats.
        $patterns = [
            // Standard watch URL: youtube.com/watch?v=ID.
            '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/',
            // Short URL: youtu.be/ID.
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            // Embed URL: youtube.com/embed/ID.
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            // Old format: youtube.com/v/ID.
            '/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/',
            // YouTube Shorts: youtube.com/shorts/ID.
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
            // With channel/user path: youtube.com/*/watch?v=ID.
            '/youtube\.com\/[^\/]+\/watch\?.*v=([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input, $matches)) {
                $result['videoid'] = $matches[1];
                break;
            }
        }

        // If no video ID found, return false.
        if (empty($result['videoid'])) {
            return false;
        }

        // Extract timestamp if present.
        // Support both 't' parameter (seconds) and 'start' parameter.
        if (isset($queryparams['t'])) {
            // Convert time format (e.g., "30s", "1m30s", "90") to seconds.
            $result['start'] = $this->parse_timestamp($queryparams['t']);
        } else if (isset($queryparams['start'])) {
            $result['start'] = intval($queryparams['start']);
        }

        return $result;
    }

    /**
     * Parse YouTube timestamp format to seconds.
     *
     * Supports formats like:
     * - "30" (30 seconds)
     * - "30s" (30 seconds)
     * - "1m30s" (90 seconds)
     * - "1h2m3s" (3723 seconds)
     *
     * @param string $timestamp The timestamp string
     * @return int The timestamp in seconds
     */
    private function parse_timestamp(string $timestamp): int {
        // If it's just a number, return it.
        if (is_numeric($timestamp)) {
            return intval($timestamp);
        }

        $seconds = 0;
        
        // Parse hours.
        if (preg_match('/(\d+)h/', $timestamp, $matches)) {
            $seconds += intval($matches[1]) * 3600;
        }
        
        // Parse minutes.
        if (preg_match('/(\d+)m/', $timestamp, $matches)) {
            $seconds += intval($matches[1]) * 60;
        }
        
        // Parse seconds.
        if (preg_match('/(\d+)s/', $timestamp, $matches)) {
            $seconds += intval($matches[1]);
        }
        
        // If no time units found, try to parse as plain number.
        if ($seconds === 0 && preg_match('/\d+/', $timestamp, $matches)) {
            $seconds = intval($matches[0]);
        }

        return $seconds;
    }
}

