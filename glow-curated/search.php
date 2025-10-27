<?php
/**
 * Search results template.
 *
 * @package Glow_Curated
 */

get_header();
?>
<section class="blog-hub-hero" data-animate>
    <div class="container">
        <nav class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'glow-curated'); ?>"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'glow-curated'); ?></a> / <?php esc_html_e('Search', 'glow-curated'); ?></nav>
        <div class="blog-hub-hero__inner">
            <p class="eyebrow"><?php esc_html_e('Glow Journal', 'glow-curated'); ?></p>
            <h1><?php printf(esc_html__('Search results for “%s”', 'glow-curated'), esc_html(get_search_query())); ?></h1>
            <p class="lede"><?php esc_html_e('Discover curated editorials that match your luxury beauty interests.', 'glow-curated'); ?></p>
        </div>
    </div>
</section>

<section class="blog-hub-list" aria-labelledby="search-results-heading">
    <div class="container">
        <div class="blog-hub-list__header">
            <h2 id="search-results-heading"><?php esc_html_e('Your curated matches', 'glow-curated'); ?></h2>
            <p><?php esc_html_e('Refine your query if you don’t see exactly what you need—our library is always growing.', 'glow-curated'); ?></p>
        </div>
        <div class="blog-card-grid" role="list">
            <?php if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'blog');
                endwhile;
            else : ?>
                <p class="text-center"><?php esc_html_e('No results found. Try another search.', 'glow-curated'); ?></p>
            <?php endif; ?>
        </div>
        <?php if (have_posts()) : ?>
            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 1,
                    'prev_text' => __('Previous', 'glow-curated'),
                    'next_text' => __('Next', 'glow-curated'),
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php
get_footer();
