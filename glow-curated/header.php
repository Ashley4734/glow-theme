<?php
/**
 * Theme header.
 *
 * @package Glow_Curated
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-pinterest-hydration="manual">
<?php wp_body_open(); ?>
<a href="#main" class="skip-link"><?php esc_html_e('Skip to main content', 'glow-curated'); ?></a>
<header class="site-header sticky" role="banner">
    <div class="container">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link" aria-label="<?php esc_attr_e('Glow Curated home', 'glow-curated'); ?>">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" role="img" aria-labelledby="logoTitle">
                    <title id="logoTitle"><?php esc_html_e('Glow Curated GC Monogram', 'glow-curated'); ?></title>
                    <circle cx="50" cy="50" r="48" fill="#E89B7E"></circle>
                    <text x="50" y="68" text-anchor="middle" font-family="'Playfair Display', serif" font-size="44" fill="#FFFFFF" font-weight="600">GC</text>
                </svg>
            <?php endif; ?>
        </a>
        <nav class="main-nav" aria-label="<?php esc_attr_e('Primary navigation', 'glow-curated'); ?>">
            <?php glow_curated_render_primary_navigation(); ?>
        </nav>
        <button class="mobile-menu-toggle" aria-label="<?php esc_attr_e('Toggle menu', 'glow-curated'); ?>" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
<main id="main" tabindex="-1">
