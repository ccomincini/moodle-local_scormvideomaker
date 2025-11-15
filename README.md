# SCORM Video Maker

![Moodle 4.0+](https://img.shields.io/badge/Moodle-4.0%2B-blue)
![PHP 7.4+](https://img.shields.io/badge/PHP-7.4%2B-blue)
![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)

A powerful Moodle local plugin that automates the creation of standard SCORM 1.2 packages from video URLs and deploys them as native SCORM activities within courses.

## Overview

**SCORM Video Maker** bridges the gap between video hosting platforms (Vimeo, YouTube, HLS streams) and Moodle's robust SCORM learning ecosystem. It eliminates the manual process of creating SCORM packages by dynamically generating them from templates and deploying them directly into courses.

### Key Philosophy

- **No Reinvention**: Delegates all tracking, completion, and grading to Moodle's proven **mod_scorm** module
- **Template-Driven**: Uses configurable templates for each video type, making extensibility simple
- **Admin Tool**: A local plugin (not an activity) provides administrative control for package creation
- **Standard Integration**: Creates standard SCORM activities—no special handling needed by instructors

## Features

### Video Source Support
- **Vimeo**: Embed Vimeo videos with their powerful player
- **YouTube**: Support for public YouTube videos  
- **HLS Streams**: Stream video-on-demand with HTTP Live Streaming (M3U8 playlists)

### Video Playback Control
- **Configurable Seeking**: Control how users navigate within videos
  - *Locked*: No seeking allowed for incomplete videos
  - *Free*: Users can seek anywhere
  - *Backward Only*: Users can rewind but not skip ahead
- **Autoplay**: Optional automatic playback on page load
- **Video.js Player**: Professional HTML5 video player with adaptive bitrate support (HLS)

### Flexible Completion Logic
- **End of Video**: Mark complete when video reaches 90% duration
- **Percentage-Based**: Mark complete when user watches X% of the video
- **Real-Time Tracking**: Progress updates sent to SCORM API during playback

### SCORM Configuration
Full access to standard SCORM activity settings:
- Maximum grade and grading method
- Attempt limits and grading strategy
- Display options (TOC, navigation, structure)
- Auto-commit and view-skip options

### Security & Permissions
- Context-aware capability system
- User permission verification on all actions
- Secure file storage using Moodle File API
- SCORM packages stored in protected file areas

## Installation

### Requirements
- **Moodle**: 4.0 or later
- **PHP**: 7.4 or later
- **Database**: Any Moodle-supported database
- **mod_scorm**: Must be enabled (standard in Moodle)

### Installation Steps

1. **Download/Clone the plugin**
   ```bash
   git clone https://github.com/ccomincini/moodle-local_scormvideomaker.git \
     /path/to/moodle/local/scormvideomaker
   ```

2. **Navigate to Moodle Notifications**
   - Log in as administrator
   - Go to **Site Administration > Notifications**
   - Follow the installation wizard

3. **Verify Installation**
   - Go to **Site Administration > Plugins > Local plugins**
   - You should see "SCORM Video Maker" listed

4. **(Optional) Configure Settings**
   - Go to **Site Administration > Plugins > Local plugins > SCORM Video Maker**
   - Configure default settings

## Usage

### Creating a SCORM Video Package

1. **Access the Tool**
   - Go to **Site Administration > Local plugins > Create SCORM Video Package**
   - Only users with `local/scormvideomaker:create` capability can access

2. **Fill in the Form - Video Configuration**
   - **Course**: Target course for activity
   - **Activity Title**: Name shown in course
   - **Description**: Optional details
   - **Video Type**: Vimeo / YouTube / HLS
   - **Video URL/Code**: Video identifier or URL
   - **Seek Bar**: Navigation permission (Locked / Free / Backward)
   - **Completion Type**: How to mark complete (At End / At Percentage)
   - **Completion %**: If percentage-based (0-100)
   - **Autoplay**: Auto-start video

3. **Configure SCORM Settings**
   - SCORM Version (1.2 or 2004)
   - Maximum Grade (points available)
   - Grading Method
   - Maximum Attempts
   - Display and navigation options

4. **Submit**
   - Click "Create SCORM Video Package"
   - Plugin generates package and uploads to course
   - Redirected to course with success message

### Student Experience

1. Student clicks on SCORM activity in course
2. Moodle's standard SCORM player loads
3. Video player displays with controls
4. Player tracks progress in real-time
5. When completion criteria met:
   - Activity marked complete
   - Grade recorded (if applicable)
   - Data persists to Moodle gradebook

## Architecture

### Design Philosophy

The plugin follows a clean, modular architecture:

- **Video Handlers**: Factory pattern for extensibility with new video platforms
- **Package Generator**: Template-based dynamic SCORM package creation
- **SCORM Creator**: Orchestrates module creation and file management
- **Standard mod_scorm**: All tracking, grading, and completion delegated to core

### File Structure

```
local/scormvideomaker/
├── version.php                      # Plugin metadata
├── settings.php                     # Admin settings
├── lib.php                          # Moodle integrations
├── index.php                        # Main interface
├── README.md                        # Documentation
├── .gitignore                       # Git configuration
├── form/
│   └── create_scorm_form.php       # Form definition
├── classes/
│   ├── scorm_creator.php           # Main SCORM creator
│   ├── scorm_package_generator.php # Package generation
│   ├── video_handler_factory.php   # Handler factory
│   ├── video_handler_interface.php # Video handler interface
│   ├── video_handler_vimeo.php     # Vimeo implementation
│   ├── video_handler_youtube.php   # YouTube implementation
│   ├── video_handler_hls.php       # HLS implementation
│   └── event/
│       └── scorm_created.php       # Event definition
├── db/
│   ├── access.php                  # Capabilities
│   └── events.php                  # Event definitions
├── lang/
│   └── en/
│       └── local_scormvideomaker.php # English strings
└── scorm_templates/
    ├── vimeo/                      # Vimeo SCORM template
    ├── youtube/                    # YouTube SCORM template
    └── hls/                        # HLS SCORM template
```

## Supported Video Types

### Vimeo
- **Input**: Video ID (e.g., `123456789`) or full Vimeo URL
- **Embed**: `https://player.vimeo.com/video/{ID}`
- **Player**: Native Vimeo Player with adaptive streaming

### YouTube
- **Input**: Video ID (11 chars, e.g., `dQw4w9WgXcQ`) or URL
- **Embed**: `https://www.youtube.com/embed/{ID}`
- **Player**: YouTube IFrame API with quality selection

### HLS Streams
- **Input**: Full M3U8 URL (e.g., `https://example.com/stream.m3u8`)
- **Player**: Video.js with HTTP Streaming plugin
- **Features**: Adaptive bitrate, multi-quality, live streaming support

## Permissions & Capabilities

- `local/scormvideomaker:create` - Create SCORM packages (Manager, Editing Teacher)
- `local/scormvideomaker:manage` - Manage all packages (Manager)

## Security

- All SCORM packages stored in protected file areas
- Files not directly accessible via URL
- Input validation on all form data
- Context-aware permission checking
- GDPR-compliant design

## Troubleshooting

### Package Creation Fails
- Check temp directory is writable (`/moodledata/temp/`)
- Verify PHP ZipArchive extension is installed
- Check disk space availability

### Video Not Playing
- **Vimeo**: Verify video privacy allows embedding
- **YouTube**: Check video is public or unlisted, not region-blocked
- **HLS**: Verify M3U8 URL is accessible, check CORS headers
- Check browser console (F12) for errors

### Activity Not Appearing
- Refresh course page (Ctrl+F5)
- Check activity visibility settings
- Verify you're in correct course section

### Completion Not Tracking
- Ensure video plays long enough (90% for end-type, or configured percentage)
- Check browser console for SCORM API errors
- Verify seeking restrictions aren't preventing progress

## Development

### Code Standards
- Follows Moodle coding standards (PSR-12)
- Comprehensive PHPDoc comments
- Proper namespacing
- Type hints throughout
- GPL v3 license headers

### Contributing

Contributions welcome! Please:
1. Fork the repository
2. Create feature branch
3. Follow Moodle coding standards
4. Submit pull request

## License

GNU General Public License v3 or later

You are free to use, modify, and distribute this plugin. See LICENSE file for details.

## Support

- 📧 Email: carlo@comincini.it
- 🐙 GitHub: [moodle-local_scormvideomaker](https://github.com/ccomincini/moodle-local_scormvideomaker)
- 🔗 Moodle Plugins: [SCORM Video Maker](https://moodle.org/plugins/)

## Credits

**Author**: Carlo Comincini <carlo@comincini.it>

**Built With**:
- Moodle LMS 4.0+
- Video.js player
- Vimeo Player API
- YouTube IFrame API

---

**Version**: 1.0.0  
**Last Updated**: November 15, 2025  
**License**: GNU GPL v3 or later

© 2025 Carlo Comincini. All rights reserved.
