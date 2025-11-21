# SCORM Video Maker - Teacher Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Creating a New SCORM Package](#creating-a-new-scorm-package)
3. [Video Types and Configuration](#video-types-and-configuration)
4. [Managing Your Packages](#managing-your-packages)
5. [Best Practices](#best-practices)

---

## Introduction

SCORM Video Maker is a Moodle plugin that allows you to create SCORM packages containing video content. These packages can be easily added to your Moodle courses to provide engaging multimedia learning experiences.

This guide will help you understand how to use the plugin to create and manage video-based SCORM packages.

---

## Creating a New SCORM Package

### Step 1: Access the Plugin

1. Navigate to **Site administration** → **Plugins** → **Local plugins** → **SCORM Video Maker**
2. Click on **"Create new SCORM package"**

![Access SCORM Video Maker](images/access-plugin.png)
*Screenshot: Accessing the SCORM Video Maker plugin*

### Step 2: Fill in the Package Details

You will see a form with the following fields:

**Package Name** (required)
- Enter a descriptive name for your SCORM package
- This name will be used to identify the package in your list
- Example: "Introduction to Biology - Cell Structure"

**Video Type** (required)
- Choose from: Vimeo, YouTube, or HLS
- Select based on where your video is hosted
- See [Video Types and Configuration](#video-types-and-configuration) for details

**Video Source** (required)
- Enter the video identifier or URL
- Format depends on the video type selected

![Package Creation Form](images/creation-form.png)
*Screenshot: SCORM package creation form*

### Step 3: Submit and Generate

1. Review your entries for accuracy
2. Click **"Create Package"**
3. Wait for the generation process to complete
4. Download the generated SCORM package

![Package Generated](images/package-ready.png)
*Screenshot: Successfully generated package ready for download*

---

## Video Types and Configuration

### Vimeo

**Video Source Format:**
- Enter only the video ID number
- Example: For `https://vimeo.com/123456789`, enter: `123456789`

**Requirements:**
- Video must be publicly accessible or have proper sharing settings
- Embedding must be enabled in Vimeo settings

![Vimeo Configuration](images/vimeo-config.png)
*Screenshot: Vimeo video configuration*

### YouTube

**Video Source Format:**
- Enter only the video ID
- Example: For `https://www.youtube.com/watch?v=dQw4w9WgXcQ`, enter: `dQw4w9WgXcQ`

**Requirements:**
- Video must be public or unlisted
- Embedding must be allowed

![YouTube Configuration](images/youtube-config.png)
*Screenshot: YouTube video configuration*

### HLS (HTTP Live Streaming)

**Video Source Format:**
- Enter the complete URL to the .m3u8 playlist file
- Example: `https://example.com/video/playlist.m3u8`

**Requirements:**
- The HLS stream must be accessible from your Moodle server
- CORS headers must be properly configured if the stream is on a different domain

![HLS Configuration](images/hls-config.png)
*Screenshot: HLS stream configuration*

---

## Managing Your Packages

### Viewing Your Packages

1. Go to the SCORM Video Maker plugin page
2. You'll see a list of all your created packages with:
   - Package name
   - Video type
   - Creation date
   - Available actions

![Package List](images/package-list.png)
*Screenshot: List of created SCORM packages*

### Downloading a Package

1. Locate your package in the list
2. Click the **"Download"** button
3. The SCORM package (ZIP file) will be downloaded to your device

### Deleting a Package

1. Locate the package you want to remove
2. Click the **"Delete"** button
3. Confirm the deletion when prompted
4. The package will be permanently removed

**Note:** Deleting a package from the plugin does not affect any copies already added to your courses.

---

## Best Practices

### Choosing the Right Video Type

- **Vimeo**: Best for professional video hosting with advanced privacy controls
- **YouTube**: Ideal for public educational content with wide accessibility
- **HLS**: Recommended for high-quality streaming or when you need full control over hosting

### Naming Conventions

Use clear, descriptive names that include:
- Course or module name
- Topic or lesson identifier
- Version number if you plan updates

Example: "BIO101-Module2-CellStructure-v1"

### Before Creating a Package

1. **Verify video accessibility**: Ensure the video can be viewed publicly or with appropriate permissions
2. **Check video quality**: Confirm the video plays correctly at its source
3. **Test on different devices**: If possible, verify the video works on various platforms
4. **Review content**: Ensure the video content is appropriate and complete

### After Creating a Package

1. **Test the SCORM package**: Upload it to a test course and verify it plays correctly
2. **Check on different browsers**: Test in Chrome, Firefox, Safari, and Edge
3. **Verify mobile compatibility**: If students use mobile devices, test on phones/tablets
4. **Keep the original video**: Don't delete the source video, as you may need to recreate the package

### Storage Management

- Regularly review and delete unused packages
- Keep only the most current version of packages you've updated
- Archive important packages outside of Moodle for long-term storage

### Accessibility Considerations

- Ensure videos have captions or subtitles when possible
- Provide transcripts for important content
- Consider adding introductory text to your SCORM activities in Moodle
- Test that videos work with screen readers if applicable

---

## Need Help?

If you encounter issues while using the plugin, please refer to the [Troubleshooting Guide](TROUBLESHOOTING.md) or contact your Moodle administrator.

For technical support and updates, visit: https://github.com/yourusername/moodle-local_scormvideomaker
