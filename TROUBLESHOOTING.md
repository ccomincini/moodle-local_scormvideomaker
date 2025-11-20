# SCORM Video Maker - Troubleshooting & Installation

## Installation Steps

After uploading the plugin to your Moodle site:

1. **Visit Admin Notifications**
   - Go to: `Site administration > Notifications`
   - Or directly: `https://your-moodle-site.com/admin/index.php`
   - This will install/upgrade the plugin

2. **Purge All Caches**
   - Go to: `Site administration > Development > Purge all caches`
   - Or directly: `https://your-moodle-site.com/admin/purgecaches.php`
   - **IMPORTANT**: This step is critical for JavaScript AMD modules to work

3. **Check Capabilities**
   - Make sure your user role has the capability: `local/scormvideomaker:create`
   - By default, this is assigned to: Manager and Editing Teacher

## Known Issues & Solutions

### Issue: Course dropdown remains empty when selecting a category

**Possible causes:**

1. **JavaScript not loaded** (most common)
   - Solution: Purge all caches as described above
   - The AMD module needs to be loaded: `amd/build/category_course_selector.min.js`

2. **AJAX web service not registered**
   - Check in browser console (F12) for errors
   - Look for: "local_scormvideomaker_get_courses_by_category"
   - If error about service not found, re-run: `php admin/cli/upgrade.php`

3. **Capability issues**
   - The web service requires: `moodle/category:viewcourselist`
   - Check if your user has this capability in the category context

4. **No courses in selected category**
   - The plugin shows ALL courses (including hidden ones)
   - If the category has no courses, dropdown will show "No courses in this category"

## Debug Mode

To see what's happening:

1. Open browser console (F12 → Console tab)
2. Select a category from the dropdown
3. You should see debug messages like:
   ```
   SCORM Video Maker: Initializing category/course selector
   Category select found: 1
   Course select found: 1
   SCORM Video Maker: Category changed to: 5
   SCORM Video Maker: Calling AJAX to load courses...
   SCORM Video Maker: Received courses: [...]
   ```

If you see errors, please report them with the full console output.

## Manual Cache Purge via CLI

If you have SSH access, you can purge caches via command line:

```bash
cd /path/to/moodle
php admin/cli/purge_caches.php
```

## Files to Check

After installation, verify these files exist:

- `local/scormvideomaker/amd/build/category_course_selector.min.js`
- `local/scormvideomaker/classes/external/get_courses_by_category.php`
- `local/scormvideomaker/db/services.php`

## Version Information

Current version: 1.0.4 BETA (Build: 2025112002)

## Support

For issues, please provide:
1. Moodle version
2. Browser console output (F12)
3. PHP error logs (if any)
4. Steps to reproduce the issue
