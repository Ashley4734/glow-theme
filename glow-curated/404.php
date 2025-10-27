<?php
/**
 * 404 template.
 *
 * @package Glow_Curated
 */

get_header();
?>
<section class="error-page" data-animate>
    <div class="content">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" role="img" aria-labelledby="errorLogoTitle" width="80" height="80">
            <title id="errorLogoTitle"><?php esc_html_e('Glow Curated GC Monogram', 'glow-curated'); ?></title>
            <circle cx="50" cy="50" r="48" fill="#E89B7E"></circle>
            <text x="50" y="68" text-anchor="middle" font-family="'Playfair Display', serif" font-size="44" fill="#FFFFFF" font-weight="600">GC</text>
        </svg>
        <h1><?php esc_html_e('Page Not Found', 'glow-curated'); ?></h1>
        <p class="subheadline"><?php esc_html_e("Oops! Looks like this page doesn't exist.", 'glow-curated'); ?></p>
        <p><?php esc_html_e("The page you're looking for might have been moved or doesn't exist. Let's get you back on track!", 'glow-curated'); ?></p>
        <div class="buttons">
            <a class="btn btn-primary" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'glow-curated'); ?></a>
            <a class="btn btn-secondary" href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('About', 'glow-curated'); ?></a>
            <a class="btn btn-secondary" href="<?php echo esc_url(home_url('/pinterest/')); ?>"><?php esc_html_e('Pinterest', 'glow-curated'); ?></a>
            <a class="btn btn-secondary" href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'glow-curated'); ?></a>
            <a class="btn btn-secondary" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact', 'glow-curated'); ?></a>
        </div>
    </div>
</section>
<?php
get_footer();
