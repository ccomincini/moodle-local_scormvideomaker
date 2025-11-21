# SCORM Video Maker - Administrator Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [System Requirements](#system-requirements)
5. [Maintenance and Monitoring](#maintenance-and-monitoring)
6. [Security Considerations](#security-considerations)
7. [Performance Optimization](#performance-optimization)

---

## Introduction

SCORM Video Maker is a Moodle local plugin that enables teachers and content creators to generate SCORM 1.2 packages containing video content from various sources (Vimeo, YouTube, HLS streams).

This guide provides comprehensive information for Moodle administrators on installation, configuration, and maintenance of the plugin.

---

## Installation

### Prerequisites

Before installing the plugin, ensure your Moodle installation meets the following requirements:

- Moodle 4.1 or higher
- PHP 7.4 or higher
- Write permissions in the Moodle data directory
- Sufficient disk space for SCORM package storage

### Installation Methods

#### Method 1: Via Moodle Plugin Installer (Recommended)

1. Download the plugin ZIP file
2. Log in to your Moodle site as an administrator
3. Navigate to **Site administration** → **Plugins** → **Install plugins**
4. Upload the ZIP file
5. Click **"Install plugin from the ZIP file"**
6. Follow the on-screen installation wizard
7. Click **"Continue"** to complete the installation

#### Method 2: Manual Installation

1. Download and extract the plugin files
2. Upload the `scormvideomaker` folder to your Moodle installation:
   ```
   /path/to/moodle/local/scormvideomaker/
   ```
3. Ensure proper file permissions:
   ```bash
   chmod -R 755 /path/to/moodle/local/scormvideomaker
   ```
4. Log in as administrator and navigate to **Site administration** → **Notifications**
5. Follow the installation prompts

### Post-Installation Steps

1. Verify the plugin appears in **Site administration** → **Plugins** → **Local plugins**
2. Configure default settings (see [Configuration](#configuration))
3. Test the plugin by creating a sample SCORM package
4. Grant appropriate permissions to teachers (see [Configuration](#configuration))

---

## Configuration

### Plugin Settings

Access plugin settings at: **Site administration** → **Plugins** → **Local plugins** → **SCORM Video Maker**

#### Available Settings

**Default Video Type**
- Options: Vimeo, YouTube, HLS
- Default: Vimeo
- Description: Pre-select the video type in the creation form

**Storage Location**
- Default: Moodle data directory under `/scormvideomaker/`
- Description: Where generated SCORM packages are stored
- Note: Must have write permissions

**Maximum Package Size**
- Default: 100 MB
- Range: 10 MB - 500 MB
- Description: Maximum allowed size for generated SCORM packages

**Auto-cleanup Period**
- Default: 30 days
- Range: 7 days - 365 days
- Description: Automatically delete packages older than specified days

### Permissions and Capabilities

The plugin defines the following capabilities:

**`local/scormvideomaker:create`**
- Description: Create new SCORM packages
- Default roles: Teacher, Manager
- Risk: RISK_SPAM

**`local/scormvideomaker:delete`**
- Description: Delete existing SCORM packages
- Default roles: Teacher, Manager
- Risk: RISK_DATALOSS

**`local/scormvideomaker:view`**
- Description: View and list SCORM packages
- Default roles: Teacher, Manager, Student
- Risk: None

#### Customizing Permissions

To modify permissions:

1. Navigate to **Site administration** → **Users** → **Permissions** → **Define roles**
2. Select the role you want to modify
3. Search for "scormvideomaker"
4. Adjust permissions as needed
5. Save changes

---

## System Requirements

### Server Requirements

**Operating System:**
- Linux (recommended)
- Windows Server
- macOS (for development only)

**Web Server:**
- Apache 2.4+ with mod_rewrite
- Nginx 1.18+

**PHP Requirements:**
- Version: 7.4 - 8.2
- Required extensions:
  - zip
  - dom
  - mbstring
  - curl
  - json
  - xml

**Database:**
- MySQL 5.7+ or MariaDB 10.2+
- PostgreSQL 10+

**Disk Space:**
- Minimum 500 MB for plugin and generated packages
- Recommended 5 GB+ depending on usage

### Network Requirements

**Outbound Connections:**
- Access to Vimeo API (https://vimeo.com)
- Access to YouTube (https://www.youtube.com)
- Access to HLS stream servers (if using HLS)

**Firewall Rules:**
- Allow HTTPS (443) outbound for video source access
- Allow HTTP (80) outbound if necessary

### Browser Compatibility

The plugin interface supports:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

SCORM packages created by the plugin are compatible with:
- All modern browsers supporting HTML5 video
- Mobile browsers (iOS Safari, Chrome Mobile)

---

## Maintenance and Monitoring

### Regular Maintenance Tasks

#### Daily Checks

- Monitor disk space usage
- Review error logs for plugin-related issues
- Check for failed package generation attempts

#### Weekly Tasks

- Review and clean up old packages
- Verify backup procedures include plugin data
- Check for plugin updates

#### Monthly Tasks

- Analyze usage statistics
- Review storage consumption trends
- Update documentation if needed

### Monitoring

#### Log Files

Plugin activities are logged to:
- Standard Moodle logs (viewable in **Site administration** → **Reports** → **Logs**)
- PHP error log (check your server's error log location)

#### Key Events to Monitor

- Package creation success/failure
- Download activity
- Deletion events
- Storage threshold warnings

#### Database Tables

The plugin uses the following tables:
- `mdl_local_scormvideomaker_packages` - Stores package metadata
- `mdl_files` - Stores actual SCORM package files (Moodle's file API)

#### Storage Monitoring

Check storage usage with:
```sql
SELECT COUNT(*) as total_packages, 
       SUM(filesize) as total_size 
FROM mdl_files 
WHERE component = 'local_scormvideomaker';
```

### Backup and Recovery

#### What to Backup

1. **Plugin Files:**
   ```
   /path/to/moodle/local/scormvideomaker/
   ```

2. **Database Tables:**
   - `mdl_local_scormvideomaker_packages`

3. **Generated SCORM Files:**
   - Moodledata directory: `/scormvideomaker/`

#### Backup Strategy

- Include plugin data in regular Moodle backups
- Consider separate backups for large SCORM packages
- Test restoration procedures periodically

#### Recovery Procedure

1. Restore plugin files to the correct directory
2. Restore database tables
3. Restore SCORM package files
4. Run Moodle upgrade checks
5. Verify plugin functionality

---

## Security Considerations

### In-Memory ZIP Generation

**Version 1.0.2+ Security Enhancement:**

The plugin implements in-memory ZIP generation to prevent security vulnerabilities:

- **No temporary files on disk**: All ZIP content is generated and handled entirely in memory
- **No accessible temporary directories**: Eliminates risk of unauthorized access to temporary files
- **Secure file handling**: Uses Moodle's `create_file_from_string()` for direct memory-to-storage transfer
- **Automatic cleanup**: Memory is freed immediately after package generation

**Technical Implementation:**
- PHP's `ZipArchive` with temporary in-memory storage
- Moodle File API for secure storage
- No remnant files or directories requiring cleanup

### Access Control

**Capability-based permissions:**
- Only users with appropriate capabilities can create/delete packages
- Viewing permissions can be restricted per role

**File access:**
- SCORM packages are stored using Moodle's file API
- Access controlled through Moodle's authentication system
- No direct URL access to package files

### Data Privacy

**User data:**
- Plugin stores minimal user data (creator ID, timestamps)
- No video content is stored locally (only references/URLs)
- Package metadata includes no personal information

**GDPR Compliance:**
- User's packages can be deleted upon account deletion
- No tracking of video viewing within generated packages
- Data retention configurable through auto-cleanup settings

### Video Source Security

**External content:**
- Videos are embedded from external sources (not hosted locally)
- Administrator should ensure video sources comply with privacy policies
- Consider firewall rules for allowed video domains

**URL Validation:**
- Plugin validates video source URLs before package creation
- Prevents injection of malicious content
- Supports only known safe video platforms

### Regular Security Updates

- Keep Moodle core updated
- Monitor plugin repository for security patches
- Subscribe to security announcements

---

## Performance Optimization

### Server Optimization

**PHP Configuration:**

Recommended php.ini settings:
```ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 100M
post_max_size = 100M
```

**Web Server:**

For Apache, enable:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE application/zip
</IfModule>
```

For Nginx:
```nginx
gzip on;
gzip_types application/zip;
```

### Database Optimization

**Indexing:**

The plugin automatically creates indexes on frequently queried columns:
- `userid` (for filtering packages by user)
- `timecreated` (for sorting by date)

**Query Optimization:**

- Plugin uses efficient Moodle DB API calls
- Pagination implemented for large package lists
- Minimal database queries per page load

### Storage Optimization

**Cleanup Strategy:**
- Enable auto-cleanup for unused packages
- Set reasonable retention periods
- Monitor storage usage regularly

**Package Size:**
- SCORM packages are minimal (typically 5-50 KB)
- No video content stored (only HTML/JavaScript wrappers)
- Negligible impact on storage

### Caching

**Moodle Cache:**
- Plugin respects Moodle's cache system
- Package list cached per user session
- Clear cache after bulk operations

### Load Balancing

For high-traffic sites:
- Plugin is stateless and works with load-balanced servers
- File storage should be on shared network storage (NFS, etc.)
- Database must be accessible from all web nodes

---

## Upgrading

### Upgrade Process

1. **Backup:** Always backup before upgrading
2. **Download:** Get the latest plugin version
3. **Replace files:** Overwrite existing plugin files
4. **Run upgrade:** Navigate to **Site administration** → **Notifications**
5. **Test:** Verify plugin functionality after upgrade

### Version-Specific Notes

**Upgrading to 1.0.2 or later:**
- In-memory ZIP generation implemented
- No configuration changes required
- Automatic removal of old temporary files (if any exist)

---

## Troubleshooting

For detailed troubleshooting information, please refer to the [Troubleshooting Guide](TROUBLESHOOTING.md).

Common administrator tasks include:
- Checking server permissions
- Verifying PHP extensions
- Reviewing error logs
- Testing external video access

---

## Support and Resources

**Plugin Repository:**
https://github.com/yourusername/moodle-local_scormvideomaker

**Issue Tracker:**
Report bugs and feature requests on GitHub Issues

**Documentation:**
- [Teacher Guide](TEACHER_GUIDE.md)
- [Troubleshooting Guide](TROUBLESHOOTING.md)
- [Changelog](CHANGELOG.md)

**Community Support:**
- Moodle forums
- Plugin discussion board

---

## License

This plugin is licensed under the GNU GPL v3 or later.

---

*Last updated: November 2024*
*Plugin version: 1.0.2*
