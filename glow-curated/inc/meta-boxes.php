<?php
// Add Product Meta Boxes
function glow_add_product_meta_boxes() {
    add_meta_box(
        'glow_product_details',
        'Product Details',
        'glow_product_details_callback',
        'glow_product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'glow_add_product_meta_boxes');

function glow_product_details_callback($post) {
    wp_nonce_field('glow_product_details_nonce', 'glow_product_details_nonce');

    $brand = get_post_meta($post->ID, '_glow_brand', true);
    $asin = get_post_meta($post->ID, '_glow_asin', true);
    $price = get_post_meta($post->ID, '_glow_price', true);
    $usage = get_post_meta($post->ID, '_glow_usage', true);
    $rating = get_post_meta($post->ID, '_glow_rating', true);
    $affiliate_url = get_post_meta($post->ID, '_glow_affiliate_url', true);
    ?>
    <style>
        .glow-meta-row { margin-bottom: 15px; }
        .glow-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
        .glow-meta-row input[type="text"], .glow-meta-row textarea { width: 100%; }
        .glow-meta-row textarea { height: 100px; }
    </style>

    <div class="glow-meta-row">
        <label>Brand Name</label>
        <input type="text" name="glow_brand" value="<?php echo esc_attr($brand); ?>">
    </div>

    <div class="glow-meta-row">
        <label>Amazon ASIN</label>
        <input type="text" name="glow_asin" value="<?php echo esc_attr($asin); ?>" placeholder="B0XXXXXX">
    </div>

    <div class="glow-meta-row">
        <label>Price Range</label>
        <input type="text" name="glow_price" value="<?php echo esc_attr($price); ?>" placeholder="$$$">
    </div>

    <div class="glow-meta-row">
        <label>Rating (out of 5)</label>
        <input type="number" name="glow_rating" value="<?php echo esc_attr($rating); ?>" min="1" max="5" step="0.5">
    </div>

    <div class="glow-meta-row">
        <label>Custom Affiliate URL (optional - will override ASIN)</label>
        <input type="text" name="glow_affiliate_url" value="<?php echo esc_attr($affiliate_url); ?>">
    </div>

    <div class="glow-meta-row">
        <label>How to Use</label>
        <textarea name="glow_usage"><?php echo esc_textarea($usage); ?></textarea>
    </div>
    <?php
}

// Save Product Meta
function glow_save_product_meta($post_id) {
    if (!isset($_POST['glow_product_details_nonce'])) return;
    if (!wp_verify_nonce($_POST['glow_product_details_nonce'], 'glow_product_details_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('glow_brand', 'glow_asin', 'glow_price', 'glow_rating', 'glow_usage', 'glow_affiliate_url');

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_glow_product', 'glow_save_product_meta');

// Add Page Display Options Meta Box
function glow_add_page_display_meta_box() {
    add_meta_box(
        'glow_page_display_options',
        __('Page Display Options', 'glow-curated'),
        'glow_page_display_options_callback',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'glow_add_page_display_meta_box');

function glow_page_display_options_callback($post) {
    wp_nonce_field('glow_page_display_options_nonce', 'glow_page_display_options_nonce');

    $hide_title = get_post_meta($post->ID, '_glow_hide_page_title', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="glow_hide_page_title" value="1" <?php checked($hide_title, '1'); ?>>
            <?php esc_html_e('Hide page title', 'glow-curated'); ?>
        </label>
    </p>
    <p class="description">
        <?php esc_html_e('Remove the hero heading on the front end for this page.', 'glow-curated'); ?>
    </p>
    <?php
}

function glow_save_page_display_meta($post_id) {
    if (!isset($_POST['glow_page_display_options_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['glow_page_display_options_nonce'], 'glow_page_display_options_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    if (isset($_POST['glow_hide_page_title']) && '1' === $_POST['glow_hide_page_title']) {
        update_post_meta($post_id, '_glow_hide_page_title', '1');
    } else {
        delete_post_meta($post_id, '_glow_hide_page_title');
    }
}
add_action('save_post_page', 'glow_save_page_display_meta');

// Add Post Display Options Meta Box
function glow_add_post_display_meta_box() {
    add_meta_box(
        'glow_post_display_options',
        __('Post Display Options', 'glow-curated'),
        'glow_post_display_options_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'glow_add_post_display_meta_box');

function glow_post_display_options_callback($post) {
    wp_nonce_field('glow_post_display_options_nonce', 'glow_post_display_options_nonce');

    $hide_title = get_post_meta($post->ID, '_glow_hide_post_title', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="glow_hide_post_title" value="1" <?php checked($hide_title, '1'); ?>>
            <?php esc_html_e('Hide post title', 'glow-curated'); ?>
        </label>
    </p>
    <p class="description">
        <?php esc_html_e('Remove the hero heading on the front end for this post.', 'glow-curated'); ?>
    </p>
    <?php
}

function glow_save_post_display_meta($post_id) {
    if (!isset($_POST['glow_post_display_options_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['glow_post_display_options_nonce'], 'glow_post_display_options_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['glow_hide_post_title']) && '1' === $_POST['glow_hide_post_title']) {
        update_post_meta($post_id, '_glow_hide_post_title', '1');
    } else {
        delete_post_meta($post_id, '_glow_hide_post_title');
    }
}
add_action('save_post_post', 'glow_save_post_display_meta');
