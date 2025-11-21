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
 * Video Handler Interface.
 *
 * Interface for video handlers.
 *
 * @package   local_scormvideomaker
 * @copyright 2025 Carlo Comincini - Invisiblefarm s.r.l. <c.comincini@invisiblefarm.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_scormvideomaker;

defined('MOODLE_INTERNAL') || die();

/**
 * Video Handler Interface.
 *
 * @package local_scormvideomaker
 */
interface video_handler_interface {

    /**
     * Process video URL and return parameters.
     *
     * @param string $videourl The video URL or code
     * @return array Array with 'videoid' and 'videourl' keys
     * @throws \moodle_exception
     */
    public function process_video_url(string $videourl): array;
}

