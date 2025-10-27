<?php
// Register Product Custom Post Type
function glow_register_product_cpt() {
    $labels = array(
        'name' => 'Products',
        'singular_name' => 'Product',
        'menu_name' => 'Products',
        'add_new' => 'Add New Product',
        'add_new_item' => 'Add New Product',
        'edit_item' => 'Edit Product',
        'new_item' => 'New Product',
        'view_item' => 'View Product',
        'search_items' => 'Search Products',
        'not_found' => 'No products found',
        'not_found_in_trash' => 'No products found in trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'products'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-cart',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    );

    register_post_type('glow_product', $args);

    // Register Product Categories
    register_taxonomy('product_category', 'glow_product', array(
        'labels' => array(
            'name' => 'Product Categories',
            'singular_name' => 'Product Category',
        ),
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'product-category'),
    ));
}
add_action('init', 'glow_register_product_cpt');

// Register Pinterest Board Custom Post Type
function glow_register_pinterest_cpt() {
    register_post_type('pinterest_board', array(
        'labels' => array(
            'name' => 'Pinterest Boards',
            'singular_name' => 'Pinterest Board',
        ),
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-pinterest',
        'supports' => array('title', 'thumbnail'),
        'menu_position' => 6,
    ));
}
add_action('init', 'glow_register_pinterest_cpt');
