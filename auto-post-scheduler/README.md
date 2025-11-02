# Auto Post Scheduler - WordPress Plugin

Automatically schedule your WordPress blog posts with intelligent date distribution.

## Overview

Auto Post Scheduler is a WordPress plugin that automatically converts draft posts into scheduled posts, distributing them across your preferred publishing schedule. Never worry about manually scheduling posts again!

## Features

- **Automatic Scheduling**: Drafts are automatically scheduled when saved
- **Intelligent Distribution**: Posts are distributed across your selected days without conflicts
- **Customizable Schedule**: Choose specific days of the week and time of day for publishing
- **Conflict Prevention**: Ensures no two posts are scheduled for the same time
- **Easy Configuration**: Simple settings page in WordPress admin
- **Lightweight**: Minimal performance impact on your site

## Installation

### Option 1: Upload to WordPress

1. Download or clone this plugin folder
2. Upload the `auto-post-scheduler` folder to `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to Settings → Auto Post Scheduler to configure

### Option 2: Install from Repository

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone [repository-url] auto-post-scheduler
```

Then activate through WordPress admin.

## Configuration

After activation, navigate to **Settings → Auto Post Scheduler** in your WordPress admin panel.

### Settings Options

1. **Enable Auto-Scheduling**
   - Toggle automatic scheduling on/off
   - When enabled, all draft posts are automatically scheduled

2. **Publish Time**
   - Set the time of day for posts to be published (e.g., 9:00 AM)
   - Uses your WordPress site's timezone

3. **Publish Days**
   - Select which days of the week to publish on
   - Default: Monday through Friday
   - You can select any combination of days

## How It Works

1. Create a new post and save it as a **Draft**
2. The plugin automatically converts it to a **Scheduled** post
3. The publish date is set to the next available slot based on your settings
4. Posts are queued in order, ensuring no conflicts

### Example

**Settings:**
- Publish Time: 9:00 AM
- Publish Days: Monday, Wednesday, Friday

**Behavior:**
- Draft 1 saved on Monday → Scheduled for Wednesday at 9:00 AM
- Draft 2 saved on Monday → Scheduled for Friday at 9:00 AM
- Draft 3 saved on Monday → Scheduled for next Monday at 9:00 AM

## Advanced Usage

### Customize Post Types

By default, the plugin only schedules regular posts. To schedule custom post types, add this to your theme's `functions.php`:

```php
add_filter('aps_allowed_post_types', function($post_types) {
    $post_types[] = 'your_custom_post_type';
    return $post_types;
});
```

### Track Scheduling Events

Hook into the scheduling event:

```php
add_action('aps_post_scheduled', function($post_id, $scheduled_date) {
    // Your custom code here
    error_log("Post {$post_id} scheduled for {$scheduled_date}");
}, 10, 2);
```

## Frequently Asked Questions

**Q: Can I manually change the scheduled date after it's set?**
A: Yes! The plugin only sets the initial schedule. You can manually adjust the date/time in the post editor.

**Q: What happens to posts I've already scheduled manually?**
A: The plugin respects existing scheduled posts and won't override them.

**Q: Can I disable auto-scheduling temporarily?**
A: Yes, just uncheck "Enable Auto-Scheduling" in the settings.

**Q: Does this work with Gutenberg and Classic Editor?**
A: Yes! It works with both editors and any other editor plugin.

**Q: What if I don't select any publish days?**
A: Posts will be scheduled for the next day by default to prevent issues.

## Technical Details

- **WordPress Version**: 5.0 or higher
- **PHP Version**: 7.0 or higher
- **Database**: Uses WordPress options table for settings
- **Hooks Used**: `transition_post_status`, `save_post`
- **Filters Available**: `aps_allowed_post_types`
- **Actions Available**: `aps_post_scheduled`

## Troubleshooting

**Posts aren't being scheduled automatically:**
1. Check that the plugin is activated
2. Verify "Enable Auto-Scheduling" is checked in settings
3. Ensure at least one publish day is selected
4. Check that you're saving as "Draft" (not "Pending" or other status)

**Scheduled dates seem wrong:**
1. Check your WordPress timezone in Settings → General
2. Verify your publish time setting
3. Ensure your server time is correct

## Changelog

### Version 1.0.0
- Initial release
- Automatic draft scheduling
- Customizable publish days and time
- Settings page
- Conflict prevention

## Support

For issues, questions, or contributions, please visit the repository or contact support.

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

## Credits

Developed for the Glow Theme project.
