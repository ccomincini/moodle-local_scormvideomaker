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
 * SCORM Package Generator class.
 *
 * Generates SCORM packages from video sources using templates.
 *
 * @package   local_scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_scormvideomaker;

defined('MOODLE_INTERNAL') || die();

/**
 * SCORM Package Generator.
 *
 * @package local_scormvideomaker
 */
class scorm_package_generator {

    /**
     * Generate a SCORM package from form data.
     *
     * @param object $formdata The form data
     * @return string|bool Path to the generated ZIP file or false on failure
     */
    public function generate_scorm_package(object $formdata) {
        global $CFG;

        // Get template path based on video type.
        $templatepath = $this->get_template_path($formdata->videotype);

        if (!is_dir($templatepath)) {
            debugging("Template not found: {$templatepath}", DEBUG_DEVELOPER);
            return false;
        }

        // Create temp directory.
        $tempdir = make_temp_directory('scormvideomaker');
        $workdir = $tempdir . DIRECTORY_SEPARATOR . uniqid('scorm_');
        mkdir($workdir);

        try {
            // Copy template to work directory.
            $this->copy_directory($templatepath, $workdir);

            // Replace variables in files.
            $this->replace_variables($workdir, $formdata);

            // Create ZIP file.
            $zipfile = $this->create_zip($workdir);

            // Clean up work directory.
            $this->remove_directory($workdir);

            return $zipfile;

        } catch (\Exception $e) {
            // Clean up on error.
            if (is_dir($workdir)) {
                $this->remove_directory($workdir);
            }
            debugging("Error generating SCORM package: " . $e->getMessage(), DEBUG_DEVELOPER);
            return false;
        }
    }

    /**
     * Get template path for video type.
     *
     * @param string $videotype The video type (vimeo, youtube, hls)
     * @return string Template path
     */
    private function get_template_path(string $videotype): string {
        global $CFG;

        $validtypes = ['vimeo', 'youtube', 'hls'];
        $videotype = strtolower($videotype);

        if (!in_array($videotype, $validtypes)) {
            $videotype = 'vimeo';
        }

        return $CFG->dirroot . '/local/scormvideomaker/scorm_templates/' . $videotype;
    }

    /**
     * Copy directory recursively.
     *
     * @param string $src Source directory
     * @param string $dst Destination directory
     * @return void
     */
    private function copy_directory(string $src, string $dst): void {
        $dir = opendir($src);

        if (!is_dir($dst)) {
            mkdir($dst);
        }

        while (false !== ($file = readdir($dir))) {
            if ($file !== '.' && $file !== '..') {
                $srcfile = $src . DIRECTORY_SEPARATOR . $file;
                $dstfile = $dst . DIRECTORY_SEPARATOR . $file;

                if (is_dir($srcfile)) {
                    $this->copy_directory($srcfile, $dstfile);
                } else {
                    copy($srcfile, $dstfile);
                }
            }
        }

        closedir($dir);
    }

    /**
     * Replace variables in files.
     *
     * @param string $workdir Working directory
     * @param object $formdata Form data with replacement values
     * @return void
     */
    private function replace_variables(string $workdir, object $formdata): void {
        // Get video handler for the video type.
        $handler = video_handler_factory::get_handler($formdata->videotype);
        $videoparams = $handler->process_video_url($formdata->videourl);

        // Prepare replacement values.
        $replacements = [
            '{{TITLE}}' => htmlspecialchars($formdata->title),
            '{{DESCRIPTION}}' => htmlspecialchars($formdata->description ?? ''),
            '{{VIDEO_ID}}' => htmlspecialchars($videoparams['videoid']),
            '{{VIDEO_URL}}' => htmlspecialchars($videoparams['videourl']),
            '{{VIDEO_TYPE}}' => strtoupper($formdata->videotype),
            '{{SEEKBAR}}' => $formdata->seekbar ?? 'locked',
            '{{COMPLETION_TYPE}}' => $formdata->completion_type ?? 'end',
            '{{COMPLETION_PERCENTAGE}}' => intval($formdata->completion_percentage ?? 100),
            '{{AUTOPLAY}}' => $formdata->autoplay ? 'true' : 'false',
            '{{TIMESTAMP}}' => time(),
        ];

        // Process all files in work directory.
        $this->process_directory_files($workdir, $replacements);
    }

    /**
     * Process all files in directory for variable replacement.
     *
     * @param string $dir Directory path
     * @param array $replacements Replacement values
     * @return void
     */
    private function process_directory_files(string $dir, array $replacements): void {
        $handle = opendir($dir);

        while (false !== ($file = readdir($handle))) {
            if ($file !== '.' && $file !== '..') {
                $filepath = $dir . DIRECTORY_SEPARATOR . $file;

                if (is_dir($filepath)) {
                    $this->process_directory_files($filepath, $replacements);
                } elseif ($this->is_replaceable_file($filepath)) {
                    $this->replace_in_file($filepath, $replacements);
                }
            }
        }

        closedir($handle);
    }

    /**
     * Check if file should have variable replacement.
     *
     * @param string $filepath File path
     * @return bool
     */
    private function is_replaceable_file(string $filepath): bool {
        $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        $replaceableexts = ['html', 'js', 'xml', 'css'];

        return in_array($extension, $replaceableexts);
    }

    /**
     * Replace variables in a file.
     *
     * @param string $filepath File path
     * @param array $replacements Replacement values
     * @return void
     */
    private function replace_in_file(string $filepath, array $replacements): void {
        $content = file_get_contents($filepath);

        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        file_put_contents($filepath, $content);
    }

    /**
     * Create ZIP file from directory.
     *
     * @param string $workdir Working directory to ZIP
     * @return string Path to ZIP file
     * @throws \Exception
     */
    private function create_zip(string $workdir): string {
        global $CFG;

        $tempdir = make_temp_directory('scormvideomaker');
        $zipfile = $tempdir . DIRECTORY_SEPARATOR . 'scorm_' . time() . '.zip';

        $zip = new \ZipArchive();

        if ($zip->open($zipfile, \ZipArchive::CREATE) !== true) {
            throw new \Exception("Cannot create ZIP file: {$zipfile}");
        }

        $this->add_files_to_zip($zip, $workdir, '');

        if (!$zip->close()) {
            throw new \Exception("Failed to close ZIP file");
        }

        return $zipfile;
    }

    /**
     * Add files to ZIP archive recursively.
     *
     * @param \ZipArchive $zip ZIP archive object
     * @param string $dir Directory to add
     * @param string $arcpath Archive path
     * @return void
     */
    private function add_files_to_zip(\ZipArchive $zip, string $dir, string $arcpath): void {
        $handle = opendir($dir);

        while (false !== ($file = readdir($handle))) {
            if ($file !== '.' && $file !== '..') {
                $filepath = $dir . DIRECTORY_SEPARATOR . $file;
                $arcfilepath = $arcpath . (empty($arcpath) ? '' : '/') . $file;

                if (is_dir($filepath)) {
                    $zip->addEmptyDir($arcfilepath);
                    $this->add_files_to_zip($zip, $filepath, $arcfilepath);
                } else {
                    $zip->addFile($filepath, $arcfilepath);
                }
            }
        }

        closedir($handle);
    }

    /**
     * Remove directory recursively.
     *
     * @param string $dir Directory path
     * @return void
     */
    private function remove_directory(string $dir): void {
        if (!is_dir($dir)) {
            return;
        }

        $handle = opendir($dir);

        while (false !== ($file = readdir($handle))) {
            if ($file !== '.' && $file !== '..') {
                $filepath = $dir . DIRECTORY_SEPARATOR . $file;

                if (is_dir($filepath)) {
                    $this->remove_directory($filepath);
                } else {
                    unlink($filepath);
                }
            }
        }

        closedir($handle);
        rmdir($dir);
    }
}

