<?php
/**
 * Template tags for Glow Curated theme.
 *
 * @package Glow_Curated
 */

if ( ! function_exists( 'glow_curated_social_links' ) ) {
    /**
     * Output social links list based on Customizer settings.
     */
    function glow_curated_social_links() {
        $networks = array(
            'pinterest' => array(
                'label' => __( 'Pinterest', 'glow-curated' ),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.486 2 2 6.389 2 11.795c0 3.978 2.59 7.375 6.236 8.647-.086-.734-.163-1.862.034-2.662.177-.742 1.145-4.728 1.145-4.728s-.292-.586-.292-1.452c0-1.36.788-2.375 1.771-2.375.835 0 1.238.627 1.238 1.378 0 .84-.535 2.095-.812 3.259-.231.978.491 1.776 1.456 1.776 1.748 0 3.087-1.844 3.087-4.504 0-2.353-1.691-4.001-4.106-4.001-2.799 0-4.442 2.099-4.442 4.267 0 .84.322 1.743.724 2.233a.29.29 0 0 1 .067.279c-.074.307-.241.978-.274 1.112-.043.177-.142.215-.329.129-1.23-.568-1.997-2.352-1.997-3.785 0-3.086 2.244-5.918 6.467-5.918 3.394 0 6.033 2.419 6.033 5.655 0 3.374-2.126 6.093-5.081 6.093-1 0-1.941-.52-2.263-1.135 0 0-.495 1.89-.615 2.356-.187.713-.686 1.605-1.022 2.147.77.238 1.585.368 2.432.368 5.514 0 10-4.389 10-9.795C22 6.389 17.514 2 12 2Z"/></svg>'
            ),
            'facebook' => array(
                'label' => __( 'Facebook', 'glow-curated' ),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9.198 21.5h4v-8.01h3.604l.396-3.98h-4V7.5a1 1 0 0 1 1-1h3v-4h-3a5 5 0 0 0-5 5v2.01h-2l-.396 3.98h2.396v8.01Z"/></svg>'
            ),
            'x' => array(
                'label' => __( 'X', 'glow-curated' ),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'
            ),
            'instagram' => array(
                'label' => __( 'Instagram', 'glow-curated' ),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8 1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3z"/></svg>'
            ),
            'tiktok' => array(
                'label' => __( 'TikTok', 'glow-curated' ),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.6 5.82s.51.5 0 0A4.278 4.278 0 0 1 15.54 3h-3.09v12.4a2.592 2.592 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6 0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64 0 3.33 2.76 5.7 5.69 5.7 3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3s-1.88.09-3.24-1.48z"/></svg>'
            ),
            'youtube' => array(
                'label' => __( 'YouTube', 'glow-curated' ),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 19c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73.47-.13 1.33-.22 2.65-.28 1.3-.07 2.49-.1 3.59-.1L12 5c4.19 0 6.8.16 7.83.44.9.25 1.48.83 1.73 1.73z"/></svg>'
            ),
        );

        echo '<ul class="social-links">';
        foreach ( $networks as $key => $network ) {
            $url = get_theme_mod( 'glow_curated_social_' . $key, '' );
            if ( ! empty( $url ) ) {
                printf(
                    '<li><a href="%1$s" class="social-link social-link--%2$s" target="_blank" rel="noopener noreferrer">%4$s<span class="screen-reader-text">%3$s</span></a></li>',
                    esc_url( $url ),
                    esc_attr( $key ),
                    esc_html( $network['label'] ),
                    $network['icon'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                );
            }
        }
        echo '</ul>';
    }
}

if ( ! function_exists( 'glow_curated_hero_buttons' ) ) {
    /**
     * Output hero buttons using Customizer settings.
     */
    function glow_curated_hero_buttons() {
        $primary_label = get_theme_mod( 'glow_curated_hero_primary_label', __( 'Follow on Pinterest ✨', 'glow-curated' ) );
        $primary_url   = get_theme_mod( 'glow_curated_hero_primary_url', 'https://pinterest.com/glowcurated' );
        $secondary_label = get_theme_mod( 'glow_curated_hero_secondary_label', __( 'Learn More', 'glow-curated' ) );
        $secondary_url   = get_theme_mod( 'glow_curated_hero_secondary_url', '/about/' );
        ?>
        <div class="buttons">
            <?php if ( $primary_label && $primary_url ) : ?>
                <a class="btn btn-primary" href="<?php echo esc_url( $primary_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $primary_label ); ?></a>
            <?php endif; ?>
            <?php if ( $secondary_label && $secondary_url ) : ?>
                <a class="btn btn-secondary" href="<?php echo esc_url( $secondary_url ); ?>"><?php echo esc_html( $secondary_label ); ?></a>
            <?php endif; ?>
        </div>
        <?php
    }
}

if ( ! function_exists( 'glow_curated_render_breadcrumb' ) ) {
    /**
     * Basic breadcrumb for hero section.
     */
    function glow_curated_render_breadcrumb() {
        echo '<p class="breadcrumb" aria-label="Breadcrumb">';
        echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'glow-curated' ) . '</a>';
        if ( is_page() ) {
            echo ' / ' . get_the_title();
        } elseif ( is_single() ) {
            echo ' / ' . esc_html__( 'Blog', 'glow-curated' ) . ' / ' . get_the_title();
        } elseif ( is_archive() ) {
            echo ' / ' . get_the_archive_title();
        } elseif ( is_search() ) {
            echo ' / ' . esc_html__( 'Search', 'glow-curated' );
        }
        echo '</p>';
    }
}

if ( ! function_exists( 'glow_curated_footer_disclaimer' ) ) {
    /**
     * Prints the footer disclaimer text.
     */
    function glow_curated_footer_disclaimer() {
        $disclaimer = get_theme_mod( 'glow_curated_footer_disclaimer' );
        if ( ! empty( $disclaimer ) ) {
            echo '<p class="footer-disclaimer">' . wp_kses_post( $disclaimer ) . '</p>';
        }
    }
}

if ( ! function_exists( 'glow_curated_render_newsletter' ) ) {
    /**
     * Output newsletter markup with shortcode fallback.
     */
    function glow_curated_render_newsletter() {
        $shortcode = apply_filters( 'glow_curated_newsletter_shortcode', '[contact-form-7 id="newsletter"]' );

        if ( function_exists( 'shortcode_exists' ) && function_exists( 'do_shortcode' ) ) {
            if ( preg_match( '/\[(\w[\w-]*)/', $shortcode, $matches ) ) {
                $tag = $matches[1];
                if ( shortcode_exists( $tag ) ) {
                    $output = do_shortcode( $shortcode );
                    if ( ! empty( $output ) ) {
                        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        return;
                    }
                }
            }
        }

        echo '<form class="newsletter-form" method="post">';
        echo '<label for="gc-newsletter-email" class="screen-reader-text">' . esc_html__( 'Email address', 'glow-curated' ) . '</label>';
        echo '<div class="newsletter-inline">';
        echo '<input type="email" id="gc-newsletter-email" name="gc-newsletter-email" placeholder="' . esc_attr__( 'Enter your email', 'glow-curated' ) . '" required />';
        echo '<button type="submit" class="btn btn-primary">' . esc_html__( 'Subscribe', 'glow-curated' ) . '</button>';
        echo '</div>';
        echo '<p class="form-help">' . esc_html__( 'We respect your inbox—unsubscribe anytime.', 'glow-curated' ) . '</p>';
        echo '</form>';
    }
}
