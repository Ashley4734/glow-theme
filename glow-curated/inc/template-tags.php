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
            'pinterest' => array( 'label' => __( 'Pinterest', 'glow-curated' ), 'icon' => '📌' ),
            'instagram' => array( 'label' => __( 'Instagram', 'glow-curated' ), 'icon' => '📷' ),
            'tiktok'    => array( 'label' => __( 'TikTok', 'glow-curated' ), 'icon' => '🎵' ),
            'youtube'   => array( 'label' => __( 'YouTube', 'glow-curated' ), 'icon' => '▶️' ),
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
                    esc_html( $network['icon'] )
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
