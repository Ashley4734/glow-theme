<?php
/**
 * Glow Curated Theme Functions
 */

// Theme Setup
function glow_curated_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-logo');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');

    // Custom image sizes
    add_image_size('glow-hero', 1920, 800, true);
    add_image_size('glow-hero-mobile', 768, 600, true);
    add_image_size('glow-product', 736, 736, true);
    add_image_size('glow-product-thumb', 368, 368, true);
    add_image_size('glow-blog-card', 400, 300, true);
    add_image_size('glow-pinterest', 736, 1104, true);
    add_image_size('glow-category', 600, 400, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer' => 'Footer Menu',
        'social' => 'Social Links Menu',
    ));

    // Add excerpt support to pages
    add_post_type_support('page', 'excerpt');
}
add_action('after_setup_theme', 'glow_curated_setup');

// Enqueue styles and scripts
function glow_curated_scripts() {
    // Google Fonts
    wp_enqueue_style('glow-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Montserrat:wght@300;400;500;600&display=swap', array(), null);

    // Theme styles
    wp_enqueue_style('glow-main', get_template_directory_uri() . '/css/main.css', array(), filemtime(get_template_directory() . '/css/main.css'));
    wp_enqueue_style('glow-components', get_template_directory_uri() . '/css/components.css', array('glow-main'), filemtime(get_template_directory() . '/css/components.css'));
    wp_enqueue_style('glow-pages', get_template_directory_uri() . '/css/pages.css', array('glow-components'), filemtime(get_template_directory() . '/css/pages.css'));
    wp_enqueue_style('glow-wordpress', get_stylesheet_uri(), array('glow-pages'), filemtime(get_stylesheet_directory() . '/style.css'));

    // Theme scripts
    wp_enqueue_script('glow-navigation', get_template_directory_uri() . '/js/navigation.js', array(), filemtime(get_template_directory() . '/js/navigation.js'), true);
    wp_enqueue_script('glow-main', get_template_directory_uri() . '/js/main.js', array(), filemtime(get_template_directory() . '/js/main.js'), true);

    // Pinterest script (conditional)
    if (is_page('pinterest')) {
        wp_enqueue_script('glow-pinterest', get_template_directory_uri() . '/js/pinterest.js', array(), filemtime(get_template_directory() . '/js/pinterest.js'), true);
    }

    // Pass data to JavaScript
    wp_localize_script('glow-main', 'glowData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'siteUrl' => home_url(),
        'themeUrl' => get_template_directory_uri(),
    ));
}
add_action('wp_enqueue_scripts', 'glow_curated_scripts');

// Include additional functionality
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/block-patterns.php';
require get_template_directory() . '/inc/import-products.php';

// Add reading time function
function glow_reading_time($post_id = null) {
    if (!$post_id) $post_id = get_the_ID();
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200);
    return $reading_time;
}

// Customize excerpt length
function glow_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'glow_excerpt_length');

// Add class to body for image toggle
function glow_body_classes($classes) {
    if (get_theme_mod('glow_text_only_mode', false)) {
        $classes[] = 'text-only-mode';
    }
    return $classes;
}
add_filter('body_class', 'glow_body_classes');

// Register widget areas
function glow_widgets_init() {
    register_sidebar(array(
        'name' => 'Footer Widget Area 1',
        'id' => 'footer-1',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'glow_widgets_init');

/**
 * Fallback primary menu mirroring static navigation.
 */
function glow_curated_fallback_menu() {
    glow_curated_render_default_links();
}

/**
 * Helper to build affiliate URL for a product.
 */
function glow_get_product_affiliate_link($product_id) {
    $custom = trim((string) get_post_meta($product_id, '_glow_affiliate_url', true));
    if (!empty($custom)) {
        return esc_url($custom);
    }

    $asin = trim((string) get_post_meta($product_id, '_glow_asin', true));
    if (empty($asin)) {
        return '';
    }

    $amazon_id = trim((string) get_theme_mod('glow_amazon_id', ''));
    $url = 'https://www.amazon.com/dp/' . rawurlencode($asin) . '/';

    if (!empty($amazon_id)) {
        $url = add_query_arg('tag', sanitize_text_field($amazon_id), $url);
    }

    return esc_url($url);
}

/**
 * Retrieve product meta safely.
 */
function glow_get_product_meta($product_id, $key) {
    return get_post_meta($product_id, '_' . $key, true);
}

function glow_get_product_features($product_id) {
    $features = get_post_meta($product_id, '_glow_features', true);

    if (is_array($features)) {
        $features = array_filter(array_map('trim', $features));
        return array_values($features);
    }

    if (is_string($features) && !empty($features)) {
        return array(trim($features));
    }

    return array();
}

/**
 * Render primary navigation replicating static layout while honoring WordPress menus.
 */
function glow_curated_render_primary_navigation() {
    $locations = get_nav_menu_locations();

    if (!empty($locations['primary'])) {
        $menu_items = wp_get_nav_menu_items($locations['primary']);
        if ($menu_items) {
            $items_by_parent = array();
            foreach ($menu_items as $item) {
                $items_by_parent[$item->menu_item_parent][] = $item;
            }

            $top_level = $items_by_parent[0] ?? array();

            foreach ($top_level as $item) {
                $children = $items_by_parent[$item->ID] ?? array();
                $is_active = in_array('current-menu-item', $item->classes, true) || in_array('current_page_item', $item->classes, true) || in_array('current-menu-ancestor', $item->classes, true);

                if (!empty($children)) {
                    echo '<div class="nav-dropdown">';
                    printf(
                        '<button class="nav-link dropdown-toggle" type="button" aria-expanded="false" aria-haspopup="true">%s</button>',
                        esc_html($item->title)
                    );
                    echo '<div class="dropdown-menu" role="menu">';
                    foreach ($children as $child) {
                        printf(
                            '<a href="%1$s" role="menuitem">%2$s</a>',
                            esc_url($child->url),
                            esc_html($child->title)
                        );
                    }
                    echo '</div></div>';
                } else {
                    printf(
                        '<a href="%1$s" class="nav-link%3$s"%4$s>%2$s</a>',
                        esc_url($item->url),
                        esc_html($item->title),
                        $is_active ? ' active' : '',
                        $is_active ? ' aria-current="page"' : ''
                    );
                }
            }

            return;
        }
    }

    glow_curated_render_default_links();
}

/**
 * Output default link set used when no menu is assigned.
 */
function glow_curated_render_default_links() {
    $links = array(
        array('url' => home_url('/'), 'label' => __('Home', 'glow-curated')),
        array('url' => home_url('/about/'), 'label' => __('About', 'glow-curated')),
        array('url' => home_url('/blog/'), 'label' => __('Blog', 'glow-curated')),
        array('url' => home_url('/pinterest/'), 'label' => __('Pinterest', 'glow-curated')),
        array('url' => home_url('/contact/'), 'label' => __('Contact', 'glow-curated')),
    );

    foreach ($links as $link) {
        printf(
            '<a href="%1$s" class="nav-link">%2$s</a>',
            esc_url($link['url']),
            esc_html($link['label'])
        );
    }

    echo '<div class="nav-dropdown">';
    echo '<button class="nav-link dropdown-toggle" type="button" aria-expanded="false" aria-haspopup="true">' . esc_html__('Legal', 'glow-curated') . '</button>';
    echo '<div class="dropdown-menu" role="menu">';
    printf('<a href="%1$s" role="menuitem">%2$s</a>', esc_url(home_url('/privacy/')), esc_html__('Privacy Policy', 'glow-curated'));
    printf('<a href="%1$s" role="menuitem">%2$s</a>', esc_url(home_url('/disclosure/')), esc_html__('Affiliate Disclosure', 'glow-curated'));
    echo '</div></div>';
}
