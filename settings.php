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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Admin settings for SCORM Video Maker.
 *
 * @package local_scormvideomaker
 * @copyright 2025 Carlo Comincini - Invisiblefarm s.r.l. <c.comincini@invisiblefarm.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Add admin external page for creating SCORM videos.
    $page = new admin_externalpage(
        'local_scormvideomaker',
        get_string('createscrorm', 'local_scormvideomaker'),
        new moodle_url('/local/scormvideomaker/index.php'),
        'local/scormvideomaker:create'
    );
    $ADMIN->add('localplugins', $page);

    // Settings page for plugin configuration.
    $settings = new admin_settingpage(
        'local_scormvideomaker_settings',
        get_string('pluginname', 'local_scormvideomaker')
    );
    $ADMIN->add('localplugins', $settings);

    // Setting: Enable plugin.
    $settings->add(new admin_setting_configcheckbox(
        'local_scormvideomaker/enabled',
        get_string('setting_enabled', 'local_scormvideomaker'),
        get_string('setting_enabled_desc', 'local_scormvideomaker'),
        1
    ));

    // Setting: Default autoplay.
    $settings->add(new admin_setting_configcheckbox(
        'local_scormvideomaker/default_autoplay',
        get_string('setting_default_autoplay', 'local_scormvideomaker'),
        get_string('setting_default_autoplay_desc', 'local_scormvideomaker'),
        0
    ));

    // Setting: Default completion type.
    $completiontype = [
        'end' => get_string('completion_end', 'local_scormvideomaker'),
        'percentage' => get_string('completion_percentage', 'local_scormvideomaker'),
    ];
    $settings->add(new admin_setting_configselect(
        'local_scormvideomaker/default_completion_type',
        get_string('setting_default_completion_type', 'local_scormvideomaker'),
        get_string('setting_default_completion_type_desc', 'local_scormvideomaker'),
        'end',
        $completiontype
    ));

    // Setting: Default completion percentage.
    $settings->add(new admin_setting_configtext(
        'local_scormvideomaker/default_completion_percentage',
        get_string('setting_default_completion_percentage', 'local_scormvideomaker'),
        get_string('setting_default_completion_percentage_desc', 'local_scormvideomaker'),
        '80',
        PARAM_INT
    ));
}
