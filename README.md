# SCORM Video Maker

A Moodle plugin to automatically create SCORM packages from Vimeo, YouTube or HLS videos.

## ✨ Features

- **Multi-platform support**: Vimeo, YouTube, HLS streaming
- **Complete SCORM tracking**: Automatic progression, grading and completion
- **Playback control**: Seekbar lock for incomplete videos
- **Flexible completion**: At video end or custom percentage
- **Responsive player**: Automatically adapts to all browsers and devices
- **In-memory ZIP generation**: Enhanced security with no temporary files on disk
- **Simplified configuration**: Optimized settings for educational use

## 📦 Requirements

- **Moodle**: 4.1 or higher
- **PHP**: 7.4 or higher with zip, dom, mbstring, curl extensions
- **SCORM module**: Must be installed and enabled
- **Disk space**: Minimal storage (packages typically 5-50 KB)

## 🚀 Quick Start

### Installation

1. Download the plugin ZIP file
2. Log in to Moodle as administrator
3. Go to **Site administration > Plugins > Install plugins**
4. Upload the ZIP file and follow the installation wizard

**For detailed installation instructions, see the [Administrator Guide](ADMIN_GUIDE.md).**

### Creating Your First Package

1. Navigate to **Site administration > Plugins > Local plugins > SCORM Video Maker**
2. Click **"Create new SCORM package"**
3. Fill in:
   - Package name (e.g., "Biology 101 - Cell Structure")
   - Video type (Vimeo, YouTube, or HLS)
   - Video source (ID or URL)
4. Click **"Create Package"**
5. Download and add to your course

**For step-by-step instructions with screenshots, see the [Teacher Guide](TEACHER_GUIDE.md).**

## 📚 Documentation

### For Teachers
- **[Teacher Guide](TEACHER_GUIDE.md)** / **[Guida Docenti](TEACHER_GUIDE_IT.md)**
  - Creating SCORM packages
  - Video types and configuration
  - Best practices
  - Managing packages

### For Administrators
- **[Administrator Guide](ADMIN_GUIDE.md)** / **[Guida Amministratori](ADMIN_GUIDE_IT.md)**
  - Installation and configuration
  - System requirements
  - Security considerations
  - Maintenance and monitoring
  - Performance optimization

### Support
- **[Troubleshooting Guide](TROUBLESHOOTING.md)** / **[Guida Risoluzione Problemi](TROUBLESHOOTING_IT.md)**
  - Common problems and solutions
  - Error messages reference
  - Getting help

## 🔒 Security

**Version 1.0.2+ implements enhanced security:**

- **In-memory ZIP generation**: All package content is handled entirely in memory
- **No temporary files**: Eliminates risk of unauthorized access to temporary files
- **Secure file handling**: Uses Moodle's File API for direct memory-to-storage transfer
- **Automatic cleanup**: Memory freed immediately after generation

For detailed security information, see the [Administrator Guide - Security Considerations](ADMIN_GUIDE.md#security-considerations).

## 📞 Support

### Getting Help

1. Check the **[Troubleshooting Guide](TROUBLESHOOTING.md)** for common issues
2. Review the appropriate documentation guide
3. Search existing [GitHub Issues](https://github.com/ccomincini/moodle-local_scormvideomaker/issues)
4. Report new issues with:
   - Moodle version
   - Plugin version
   - Problem description
   - Steps to reproduce

### Additional Resources

- **Developer Docs**: [Moodle Dev Docs](https://docs.moodle.org)
- **SCORM Spec**: [SCORM 1.2 Reference](https://scorm.com/scorm-explained/)
- **GitHub Repository**: [Plugin Repository](https://github.com/ccomincini/moodle-local_scormvideomaker)

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a Pull Request

Please follow Moodle coding standards and include tests where applicable.

## 📄 License

This plugin is released under the **GNU GPL v3** or later.

See [LICENSE](LICENSE) for full license text.

## 👥 Credits

**Author**: Carlo Comincini  
**Email**: carlo@comincini.it  
**Copyright**: 2025 Carlo Comincini

## 🔄 Changelog

For detailed version history, see [CHANGELOG.md](CHANGELOG.md).

### Latest Version: 1.0.2 BETA (November 2024)

#### Security Enhancement
- 🔒 **In-memory ZIP generation**: Eliminates temporary file security vulnerabilities
- 🔒 **No disk-based temporary files**: All processing done in memory
- 🔒 **Secure file handling**: Direct memory-to-Moodle File API transfer

#### Features
- ✅ Multi-platform video support (Vimeo, YouTube, HLS)
- ✅ SCORM 1.2 package generation
- ✅ Automatic tracking and grading
- ✅ Responsive video player

---

**Thank you for choosing SCORM Video Maker!** 🎉

For questions, suggestions, or feedback, please open an issue on GitHub or contact the author.
