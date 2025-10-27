<?php
/**
 * Theme footer.
 *
 * @package Glow_Curated
 */
?>
</main>
<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 120" role="img" aria-labelledby="footerLogoTitle">
                    <title id="footerLogoTitle"><?php esc_html_e('Glow Curated Logo', 'glow-curated'); ?></title>
                    <circle cx="60" cy="60" r="48" fill="#FFFFFF" opacity="0.2"></circle>
                    <text x="60" y="76" text-anchor="middle" font-family="'Playfair Display', serif" font-size="58" fill="#FFFFFF" font-weight="600">GC</text>
                    <text x="130" y="55" font-family="'Playfair Display', serif" font-size="42" fill="#FFFFFF">Glow</text>
                    <text x="130" y="92" font-family="'Montserrat', sans-serif" font-size="26" letter-spacing="6" fill="#FFFFFF">CURATED</text>
                </svg>
                <p class="tagline"><?php esc_html_e('Discover Luxury Beauty Worth The Investment', 'glow-curated'); ?></p>
                <p class="copyright">&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'glow-curated'); ?></p>
            </div>

            <div class="footer-links">
                <h4><?php esc_html_e('Quick Links', 'glow-curated'); ?></h4>
                <?php
                $quick_links = wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'fallback_cb' => false,
                    'echo' => false,
                    'items_wrap' => '%3$s',
                    'depth' => 1,
                ));

                if ($quick_links) {
                    echo '<ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo $quick_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '</ul>';
                } else {
                    echo '<ul>';
                    printf('<li><a href="%s">%s</a></li>', esc_url(home_url('/')), esc_html__('Home', 'glow-curated'));
                    printf('<li><a href="%s">%s</a></li>', esc_url(home_url('/about/')), esc_html__('About', 'glow-curated'));
                    printf('<li><a href="%s">%s</a></li>', esc_url(home_url('/blog/')), esc_html__('Blog', 'glow-curated'));
                    printf('<li><a href="%s">%s</a></li>', esc_url(home_url('/pinterest/')), esc_html__('Pinterest', 'glow-curated'));
                    printf('<li><a href="%s">%s</a></li>', esc_url(home_url('/contact/')), esc_html__('Contact', 'glow-curated'));
                    echo '</ul>';
                }
                ?>
            </div>

            <div class="footer-legal">
                <h4><?php esc_html_e('Legal', 'glow-curated'); ?></h4>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container' => false,
                    'menu_class' => '',
                    'fallback_cb' => function () {
                        echo '<ul>';
                        printf('<li><a href="%s">%s</a></li>', esc_url(home_url('/privacy/')), esc_html__('Privacy Policy', 'glow-curated'));
                        printf('<li><a href="%s">%s</a></li>', esc_url(home_url('/disclosure/')), esc_html__('Affiliate Disclosure', 'glow-curated'));
                        echo '</ul>';
                    },
                    'items_wrap' => '<ul>%3$s</ul>',
                    'depth' => 1,
                ));
                ?>
            </div>

            <div class="footer-social">
                <h4><?php esc_html_e('Connect', 'glow-curated'); ?></h4>
                <?php
                $social_menu = wp_nav_menu(array(
                    'theme_location' => 'social',
                    'container' => false,
                    'fallback_cb' => false,
                    'echo' => false,
                    'items_wrap' => '%3$s',
                    'depth' => 1,
                ));

                if ($social_menu) {
                    echo '<div class="social-links">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo $social_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '</div>';
                } else {
                    $pinterest = get_theme_mod('glow_pinterest_username', 'GlowCurated');
                    $url = 'https://pinterest.com/' . rawurlencode($pinterest);
                    ?>
                    <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" class="social-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="#FFFFFF" d="M12 2C6.486 2 2 6.389 2 11.795c0 3.978 2.59 7.375 6.236 8.647-.086-.734-.163-1.862.034-2.662.177-.742 1.145-4.728 1.145-4.728s-.292-.586-.292-1.452c0-1.36.788-2.375 1.771-2.375.835 0 1.238.627 1.238 1.378 0 .84-.535 2.095-.812 3.259-.231.978.491 1.776 1.456 1.776 1.748 0 3.087-1.844 3.087-4.504 0-2.353-1.691-4.001-4.106-4.001-2.799 0-4.442 2.099-4.442 4.267 0 .84.322 1.743.724 2.233a.29.29 0 0 1 .067.279c-.074.307-.241.978-.274 1.112-.043.177-.142.215-.329.129-1.23-.568-1.997-2.352-1.997-3.785 0-3.086 2.244-5.918 6.467-5.918 3.394 0 6.033 2.419 6.033 5.655 0 3.374-2.126 6.093-5.081 6.093-1 0-1.941-.52-2.263-1.135 0 0-.495 1.89-.615 2.356-.187.713-.686 1.605-1.022 2.147.77.238 1.585.368 2.432.368 5.514 0 10-4.389 10-9.795C22 6.389 17.514 2 12 2Z"/></svg>
                        <?php esc_html_e('Follow on Pinterest', 'glow-curated'); ?>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>

        <div class="footer-bottom">
            <p><?php esc_html_e('Made with ✨ for luxury beauty lovers', 'glow-curated'); ?></p>
            <button class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'glow-curated'); ?>" type="button">↑</button>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
