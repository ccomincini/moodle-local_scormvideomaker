# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.5](https://github.com/ccomincini/moodle-local_scormvideomaker/compare/v1.0.4...v1.0.5) - 2025-11-20

### Added

- Italian language translations (lang/it/local_scormvideomaker.php)
- New language strings: `choosecategory`, `choosecourse`, `nocoursesincategory`
- Proper AMD module loading for translated JavaScript strings
- Comprehensive form validation with database checks


### Fixed

- **Form Validation Bug**: Fixed course select showing placeholder text instead of translated strings
    - Implemented `definition_after_data()` method to repopulate course options
    - Course selection now properly validates against database records
    - AJAX course loading respects form validation security
- **Redirect Issue**: Fixed form submission getting stuck after package creation
    - Replaced `mtrace()` with `error_log()` to prevent output buffering issues
    - Removed debugging output that interfered with HTTP headers
    - Redirect now completes successfully to course view page
- **JavaScript String Loading**: Fixed hardcoded strings in category_course_selector.js
    - Implemented `Str.get_strings()` for asynchronous translation loading
    - Proper error handling for missing language strings


### Changed

- Optimized form field definitions for better user experience
- Improved console logging for better debugging
- Enhanced error handling and validation messages
- Repository cleanup: removed test/troubleshooting documentation files


### Performance

- Optimized JavaScript module loading for faster page rendering
- Reduced debugging output in production environments

***

## [1.0.4](https://github.com/ccomincini/moodle-local_scormvideomaker/compare/v1.0.3...v1.0.4) - 2025-11-19

### Added

- Code refactoring for better maintainability
- Enhanced debugging capabilities


### Fixed

- Improved AJAX error handling in course selector
- Better error messages for debugging

***

## [1.0.3](https://github.com/ccomincini/moodle-local_scormvideomaker/compare/v1.0.2...v1.0.3) - 2025-11-18

### Added

- Category-based course selection with AJAX filtering
- Dynamic course dropdown population
- Course visibility indicator (shows "hidden" label for hidden courses)


### Fixed

- Course selection loading logic
- Database query optimization

***

## [1.0.2](https://github.com/ccomincini/moodle-local_scormvideomaker/compare/v1.0.1...v1.0.2) - 2025-11-17

### Added

- Comprehensive form field descriptions and help text
- Support for optional course section selection
- Description field for SCORM activities


### Fixed

- Form field type validation
- Help button integration for all form fields

***

## [1.0.1](https://github.com/ccomincini/moodle-local_scormvideomaker/compare/v1.0.0...v1.0.1) - 2025-11-16

### Added

- Basic SCORM 1.2 package generation
- Support for Vimeo video source
- Support for YouTube video source
- Support for HLS streaming


### Fixed

- Manifest XML generation
- SCORM package file structure

***

## [1.0.0](https://github.com/ccomincini/moodle-local_scormvideomaker/releases/tag/v1.0.0) - 2025-11-15

### Added

- Initial release
- Core functionality for creating SCORM packages
- Form for package configuration
- Basic video handler infrastructure
- Database services for AJAX operations
- Admin settings page

