<?php
function glow_customize_register($wp_customize) {
    // Glow Settings Section
    $wp_customize->add_section('glow_settings', array(
        'title' => 'Glow Curated Settings',
        'priority' => 30,
    ));

    // Text-Only Mode
    $wp_customize->add_setting('glow_text_only_mode', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('glow_text_only_mode', array(
        'label' => 'Text-Only Mode',
        'description' => 'Hide all images site-wide (maintains original text-only aesthetic)',
        'section' => 'glow_settings',
        'type' => 'checkbox',
    ));

    // Amazon Associates ID
    $wp_customize->add_setting('glow_amazon_id', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('glow_amazon_id', array(
        'label' => 'Amazon Associates ID',
        'section' => 'glow_settings',
        'type' => 'text',
    ));

    // Pinterest Username
    $wp_customize->add_setting('glow_pinterest_username', array(
        'default' => 'GlowCurated',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('glow_pinterest_username', array(
        'label' => 'Pinterest Username',
        'section' => 'glow_settings',
        'type' => 'text',
    ));

    // Facebook URL
    $wp_customize->add_setting('glow_facebook_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('glow_facebook_url', array(
        'label' => 'Facebook URL',
        'section' => 'glow_settings',
        'type' => 'url',
    ));

    // X (Twitter) URL
    $wp_customize->add_setting('glow_x_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('glow_x_url', array(
        'label' => 'X (Twitter) URL',
        'section' => 'glow_settings',
        'type' => 'url',
    ));
}
add_action('customize_register', 'glow_customize_register');
