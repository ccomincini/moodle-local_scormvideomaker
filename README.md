# SCORM Video Maker

A Moodle local plugin that creates SCORM 1.2 learning packages from video URLs (Vimeo, YouTube, HLS streams).

## Features

- **Multiple Video Sources**: Support for Vimeo, YouTube, and HLS streaming URLs
- **Easy Package Creation**: Intuitive form to create SCORM packages in minutes
- **Video Controls**: Configure seek bar behavior (locked, free, backward-only)
- **Completion Tracking**: Set completion criteria (end of video or percentage watched)
- **Category-Based Course Selection**: Filter courses by category with dynamic AJAX loading
- **Autoplay Option**: Enable/disable automatic video playback
- **Multilingual Support**: English and Italian translations included
- **Moodle Integration**: Seamless integration with Moodle's course structure and grading


## Requirements

- Moodle 4.0 or later
- SCORM module installed and enabled
- PHP 7.4 or later
- JavaScript enabled in browser


## Installation

1. Download or clone this plugin into your Moodle installation:
```bash
git clone https://github.com/ccomincini/moodle-local_scormvideomaker.git /path/to/moodle/local/scormvideomaker
```

2. Log in to Moodle as an administrator
3. Navigate to **Administration > Notifications** and upgrade the database
4. The plugin is now ready to use

## Usage

### Creating a SCORM Video Package

1. Navigate to **Site Administration > Plugins > Local Plugins > SCORM Video Maker**
2. Fill in the form:
    - **Category**: Select the course category
    - **Course**: Select the course where the SCORM activity will be added
    - **Course Section**: (Optional) Specify which section to add the activity to
    - **Activity Title**: Name of the SCORM activity
    - **Description**: Detailed description of the activity
    - **Video Type**: Choose Vimeo, YouTube, or HLS Stream
    - **Video URL/Code**: Enter the video URL or code (format depends on video type)
    - **Seek Bar Behavior**: Choose how users can navigate the video
    - **Completion Type**: Choose between "At end of video" or "At percentage watched"
    - **Completion Percentage**: (If "At percentage watched" is selected) Enter the percentage
    - **Autoplay**: Enable/disable automatic playback
3. Click **Create SCORM Package**
4. The package is created and automatically added to the selected course
5. A new SCORM activity appears in the course with activity tracking enabled

### Video URL Format Examples

**Vimeo:**

```
Video URL: https://vimeo.com/123456789
Enter as: 123456789
```

**YouTube:**

```
Video URL: https://www.youtube.com/watch?v=dQw4w9WgXcQ
Enter as: dQw4w9WgXcQ
```

**HLS Stream:**

```
Enter full URL: https://example.com/videos/stream/playlist.m3u8
```


## Permissions

The plugin uses one permission:

- **local/scormvideomaker:create**: Allows users to create SCORM video packages

By default, this permission is granted to administrators and course creators.

## Configuration

Navigate to **Administration > Plugins > Local Plugins > SCORM Video Maker** to configure:

- Enable/disable the plugin
- Set default autoplay behavior
- Set default completion type
- Set default completion percentage


## Supported Video Providers

### Vimeo

- Supports public and private videos (if properly configured)
- Requires video ID
- Supports Vimeo player features


### YouTube

- Supports public videos only
- Requires video ID
- Supports YouTube player features
- Respects YouTube's embedding restrictions


### HLS Streams

- Supports HTTP Live Streaming (HLS/M3U8) format
- Suitable for custom video servers
- Requires direct URL to M3U8 playlist file


## Completion and Tracking

Students' progress is tracked using SCORM 1.2 standard:

- **Video watched**: When a student views the video
- **Completion status**: Based on configured completion criteria
- **Time spent**: Automatically tracked by SCORM module
- **Grading**: Can be configured in SCORM activity settings


## Browser Compatibility

- Chrome/Chromium 90+
- Firefox 88+
- Safari 14+
- Edge 90+


## Troubleshooting

### Package not appearing in course

1. Verify the course exists and you have permissions to add activities
2. Check that the specified section number is valid
3. Ensure SCORM module is installed and enabled
4. Review Moodle error logs for details

### Video not playing in SCORM player

1. Verify the video URL/code is correct
2. For Vimeo: Ensure video is accessible to Moodle server
3. For YouTube: Confirm video is public or properly shared
4. For HLS: Verify M3U8 URL is accessible and properly formatted
5. Check browser console for JavaScript errors

### Completion not tracking

1. Verify the video actually plays in the SCORM player
2. Check Moodle SCORM module is configured for tracking
3. Ensure student watches at least the configured percentage
4. Review SCORM module activity logs in Moodle

## Known Limitations

- Only SCORM 1.2 format is currently supported
- Video completion tracking is basic (presence-based)
- DRM-protected videos are not supported
- Geographically restricted videos may not play in all regions


## Support

For bugs, feature requests, or questions:

- Create an issue on [GitHub Issues](https://github.com/ccomincini/moodle-local_scormvideomaker/issues)
- Check existing issues and discussions first


## License

This plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Moodle. If not, see http://www.gnu.org/licenses/.

## Copyright
