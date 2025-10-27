<?php
/**
 * Blog index template.
 *
 * @package Glow_Curated
 */

get_header();
?>
<section class="blog-hub-hero" data-animate>
    <div class="container">
        <nav class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'glow-curated'); ?>"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'glow-curated'); ?></a> / <?php esc_html_e('Blog', 'glow-curated'); ?></nav>
        <div class="blog-hub-hero__inner">
            <p class="eyebrow"><?php esc_html_e('Glow Journal', 'glow-curated'); ?></p>
            <h1><?php esc_html_e('Luxury skincare stories, season after season', 'glow-curated'); ?></h1>
            <p class="lede"><?php esc_html_e('Explore in-depth editorials from the Glow Curated team covering prestige skincare launches, ritual-driven routines, and the science behind indulgent formulas.', 'glow-curated'); ?></p>
        </div>
    </div>
</section>

<section class="blog-hub-list" aria-labelledby="latest-features-heading">
    <div class="container">
        <div class="blog-hub-list__header">
            <h2 id="latest-features-heading"><?php esc_html_e('Latest features', 'glow-curated'); ?></h2>
            <p><?php esc_html_e('Browse our newest long-form stories. Each post includes detailed routines, product spotlights, and expert-backed insights tailored to luxury beauty lovers.', 'glow-curated'); ?></p>
        </div>
        <div class="blog-card-grid" role="list">
            <?php if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'blog');
                endwhile;
            else : ?>
                <p class="text-center"><?php esc_html_e('No articles found. Check back soon for more luxury beauty stories.', 'glow-curated'); ?></p>
            <?php endif; ?>
        </div>
        <div class="pagination">
            <?php
            the_posts_pagination(array(
                'mid_size' => 1,
                'prev_text' => __('Previous', 'glow-curated'),
                'next_text' => __('Next', 'glow-curated'),
            ));
            ?>
        </div>
    </div>
</section>
<?php
get_footer();
