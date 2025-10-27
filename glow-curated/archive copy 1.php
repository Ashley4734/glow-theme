<?php
/**
 * Archive template.
 *
 * @package Glow_Curated
 */

get_header();
?>
<section class="blog-hub-hero" data-animate>
    <div class="container">
        <nav class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'glow-curated'); ?>"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'glow-curated'); ?></a> / <?php echo esc_html(get_the_archive_title()); ?></nav>
        <div class="blog-hub-hero__inner">
            <p class="eyebrow"><?php esc_html_e('Glow Journal', 'glow-curated'); ?></p>
            <h1><?php echo esc_html(get_the_archive_title()); ?></h1>
            <?php if (get_the_archive_description()) : ?>
                <p class="lede"><?php echo wp_kses_post(get_the_archive_description()); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="blog-hub-list" aria-labelledby="archive-heading">
    <div class="container">
        <div class="blog-hub-list__header">
            <h2 id="archive-heading"><?php esc_html_e('Curated stories', 'glow-curated'); ?></h2>
            <p><?php esc_html_e('Explore every editorial within this collection.', 'glow-curated'); ?></p>
        </div>
        <div class="blog-card-grid" role="list">
            <?php if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'blog');
                endwhile;
            else : ?>
                <p class="text-center"><?php esc_html_e('No articles available in this archive yet.', 'glow-curated'); ?></p>
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
