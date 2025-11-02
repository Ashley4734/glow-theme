<?php
/**
 * Plugin Name: Auto Post Scheduler
 * Plugin URI: https://github.com/Ashley4734/glow-theme
 * Description: Automatically schedules draft blog posts for publication based on your preferred schedule.
 * Version: 1.0.0
 * Author: Glow Theme
 * Author URI: https://github.com/Ashley4734/glow-theme
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: auto-post-scheduler
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Plugin Class
 */
class Auto_Post_Scheduler {

    /**
     * Plugin version
     */
    const VERSION = '1.0.0';

    /**
     * Plugin instance
     */
    private static $instance = null;

    /**
     * Get plugin instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Hook into post transitions
        add_action('transition_post_status', array($this, 'auto_schedule_draft'), 10, 3);

        // Hook into new post creation
        add_action('save_post', array($this, 'schedule_on_save'), 10, 3);

        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Register settings
        add_action('admin_init', array($this, 'register_settings'));

        // Add settings link on plugins page
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
    }

    /**
     * Auto-schedule draft posts
     */
    public function auto_schedule_draft($new_status, $old_status, $post) {
        // Only process posts (not pages or custom post types unless specified)
        $allowed_post_types = apply_filters('aps_allowed_post_types', array('post'));

        if (!in_array($post->post_type, $allowed_post_types)) {
            return;
        }

        // Only schedule if the post is being saved as draft
        if ($new_status !== 'draft') {
            return;
        }

        // Don't schedule if already scheduled or published
        if ($old_status === 'future' || $old_status === 'publish') {
            return;
        }

        // Schedule the post
        $this->schedule_post($post->ID);
    }

    /**
     * Schedule post on save
     */
    public function schedule_on_save($post_id, $post, $update) {
        // Avoid autosave and revisions
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (wp_is_post_revision($post_id)) {
            return;
        }

        // Only process posts
        $allowed_post_types = apply_filters('aps_allowed_post_types', array('post'));

        if (!in_array($post->post_type, $allowed_post_types)) {
            return;
        }

        // Only schedule drafts
        if ($post->post_status === 'draft') {
            $this->schedule_post($post_id);
        }
    }

    /**
     * Schedule a post for publication
     */
    private function schedule_post($post_id) {
        // Check if auto-scheduling is enabled
        $enabled = get_option('aps_enabled', 1);
        if (!$enabled) {
            return;
        }

        // Get current post
        $post = get_post($post_id);
        if (!$post || $post->post_status !== 'draft') {
            return;
        }

        // Get settings
        $days_ahead = get_option('aps_days_ahead', 7);
        $publish_time = get_option('aps_publish_time', '09:00');
        $publish_days = get_option('aps_publish_days', array('1', '2', '3', '4', '5')); // Mon-Fri by default

        // Calculate next available publish date
        $next_date = $this->get_next_publish_date($days_ahead, $publish_time, $publish_days);

        // Update post to scheduled status
        $scheduled_post = array(
            'ID' => $post_id,
            'post_status' => 'future',
            'post_date' => $next_date,
            'post_date_gmt' => get_gmt_from_date($next_date),
            'edit_date' => true
        );

        // Remove the hook to prevent infinite loop
        remove_action('transition_post_status', array($this, 'auto_schedule_draft'), 10);
        remove_action('save_post', array($this, 'schedule_on_save'), 10);

        // Update the post
        wp_update_post($scheduled_post);

        // Re-add the hook
        add_action('transition_post_status', array($this, 'auto_schedule_draft'), 10, 3);
        add_action('save_post', array($this, 'schedule_on_save'), 10, 3);

        // Log the scheduling
        do_action('aps_post_scheduled', $post_id, $next_date);
    }

    /**
     * Get next available publish date
     */
    private function get_next_publish_date($days_ahead, $publish_time, $publish_days) {
        $current_time = current_time('timestamp');

        // Get all scheduled posts to avoid conflicts
        $scheduled_posts = get_posts(array(
            'post_status' => 'future',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        // Get the latest scheduled date
        $latest_scheduled = null;
        if (!empty($scheduled_posts)) {
            $latest_post = get_post($scheduled_posts[0]);
            $latest_scheduled = strtotime($latest_post->post_date);
        }

        // Start from either the latest scheduled post or current time
        $start_time = $latest_scheduled ? $latest_scheduled : $current_time;

        // Parse publish time
        list($hour, $minute) = explode(':', $publish_time);

        // Find next available date
        $attempts = 0;
        $max_attempts = 365; // Prevent infinite loop

        while ($attempts < $max_attempts) {
            $attempts++;
            $check_date = strtotime("+{$attempts} day", $start_time);
            $day_of_week = date('N', $check_date); // 1 (Mon) through 7 (Sun)

            // Check if this day is in the allowed publish days
            if (in_array($day_of_week, $publish_days)) {
                // Set the time
                $publish_date = date('Y-m-d', $check_date) . ' ' . sprintf('%02d:%02d:00', $hour, $minute);
                $publish_timestamp = strtotime($publish_date);

                // Make sure it's in the future
                if ($publish_timestamp > $current_time) {
                    return $publish_date;
                }
            }
        }

        // Fallback: schedule for tomorrow at the specified time
        return date('Y-m-d', strtotime('+1 day')) . ' ' . sprintf('%02d:%02d:00', $hour, $minute);
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Auto Post Scheduler Settings', 'auto-post-scheduler'),
            __('Auto Post Scheduler', 'auto-post-scheduler'),
            'manage_options',
            'auto-post-scheduler',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('aps_settings', 'aps_enabled');
        register_setting('aps_settings', 'aps_days_ahead');
        register_setting('aps_settings', 'aps_publish_time');
        register_setting('aps_settings', 'aps_publish_days');

        add_settings_section(
            'aps_main_section',
            __('Scheduling Settings', 'auto-post-scheduler'),
            array($this, 'render_section_description'),
            'auto-post-scheduler'
        );

        add_settings_field(
            'aps_enabled',
            __('Enable Auto-Scheduling', 'auto-post-scheduler'),
            array($this, 'render_enabled_field'),
            'auto-post-scheduler',
            'aps_main_section'
        );

        add_settings_field(
            'aps_publish_time',
            __('Publish Time', 'auto-post-scheduler'),
            array($this, 'render_time_field'),
            'auto-post-scheduler',
            'aps_main_section'
        );

        add_settings_field(
            'aps_publish_days',
            __('Publish Days', 'auto-post-scheduler'),
            array($this, 'render_days_field'),
            'auto-post-scheduler',
            'aps_main_section'
        );
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Show success message
        if (isset($_GET['settings-updated'])) {
            add_settings_error(
                'aps_messages',
                'aps_message',
                __('Settings Saved', 'auto-post-scheduler'),
                'updated'
            );
        }

        settings_errors('aps_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('aps_settings');
                do_settings_sections('auto-post-scheduler');
                submit_button(__('Save Settings', 'auto-post-scheduler'));
                ?>
            </form>

            <hr>

            <h2><?php _e('How It Works', 'auto-post-scheduler'); ?></h2>
            <p><?php _e('This plugin automatically schedules any draft posts for publication based on your settings above.', 'auto-post-scheduler'); ?></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><?php _e('When you save a post as a draft, it will automatically be scheduled for the next available publish slot.', 'auto-post-scheduler'); ?></li>
                <li><?php _e('Posts are scheduled in the order they are created, with no overlapping publish times.', 'auto-post-scheduler'); ?></li>
                <li><?php _e('Only posts matching your selected publish days will be scheduled.', 'auto-post-scheduler'); ?></li>
                <li><?php _e('You can manually adjust the scheduled date/time after it\'s been set.', 'auto-post-scheduler'); ?></li>
            </ul>
        </div>
        <?php
    }

    /**
     * Render section description
     */
    public function render_section_description() {
        echo '<p>' . __('Configure how draft posts should be automatically scheduled.', 'auto-post-scheduler') . '</p>';
    }

    /**
     * Render enabled field
     */
    public function render_enabled_field() {
        $enabled = get_option('aps_enabled', 1);
        ?>
        <label>
            <input type="checkbox" name="aps_enabled" value="1" <?php checked($enabled, 1); ?>>
            <?php _e('Automatically schedule draft posts', 'auto-post-scheduler'); ?>
        </label>
        <p class="description">
            <?php _e('When enabled, all draft posts will be automatically scheduled for publication.', 'auto-post-scheduler'); ?>
        </p>
        <?php
    }

    /**
     * Render time field
     */
    public function render_time_field() {
        $time = get_option('aps_publish_time', '09:00');
        ?>
        <input type="time" name="aps_publish_time" value="<?php echo esc_attr($time); ?>">
        <p class="description">
            <?php _e('The time of day to publish posts (in your site\'s timezone).', 'auto-post-scheduler'); ?>
        </p>
        <?php
    }

    /**
     * Render days field
     */
    public function render_days_field() {
        $selected_days = get_option('aps_publish_days', array('1', '2', '3', '4', '5'));
        $days = array(
            '1' => __('Monday', 'auto-post-scheduler'),
            '2' => __('Tuesday', 'auto-post-scheduler'),
            '3' => __('Wednesday', 'auto-post-scheduler'),
            '4' => __('Thursday', 'auto-post-scheduler'),
            '5' => __('Friday', 'auto-post-scheduler'),
            '6' => __('Saturday', 'auto-post-scheduler'),
            '7' => __('Sunday', 'auto-post-scheduler')
        );

        echo '<fieldset>';
        foreach ($days as $value => $label) {
            $checked = in_array($value, $selected_days) ? 'checked' : '';
            ?>
            <label style="display: block; margin-bottom: 5px;">
                <input type="checkbox" name="aps_publish_days[]" value="<?php echo esc_attr($value); ?>" <?php echo $checked; ?>>
                <?php echo esc_html($label); ?>
            </label>
            <?php
        }
        echo '</fieldset>';
        ?>
        <p class="description">
            <?php _e('Select which days of the week posts should be published on.', 'auto-post-scheduler'); ?>
        </p>
        <?php
    }

    /**
     * Add settings link on plugins page
     */
    public function add_settings_link($links) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('options-general.php?page=auto-post-scheduler'),
            __('Settings', 'auto-post-scheduler')
        );
        array_unshift($links, $settings_link);
        return $links;
    }
}

/**
 * Initialize the plugin
 */
function auto_post_scheduler_init() {
    return Auto_Post_Scheduler::get_instance();
}

// Start the plugin
add_action('plugins_loaded', 'auto_post_scheduler_init');
