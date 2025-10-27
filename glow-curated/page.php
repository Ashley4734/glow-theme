<?php
/**
 * Page template.
 *
 * @package Glow_Curated
 */

get_header();
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php
    $hide_title = get_post_meta(get_the_ID(), '_glow_hide_page_title', true);
    $has_excerpt = has_excerpt();
    $hero_classes = 'page-hero';

    if (!empty($hide_title)) {
        $hero_classes .= ' page-hero--title-hidden';
    }
    ?>
    <div class="container">
        <nav class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'glow-curated'); ?>"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'glow-curated'); ?></a> / <?php echo esc_html(get_the_title()); ?></nav>
    </div>
    <?php if (empty($hide_title) || $has_excerpt) : ?>
        <section class="<?php echo esc_attr($hero_classes); ?>" data-animate>
            <div class="container">
                <?php if (empty($hide_title)) : ?>
                    <h1><?php the_title(); ?></h1>
                <?php endif; ?>
                <?php if ($has_excerpt) : ?>
                    <p class="subheadline"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
    <section class="page-content" data-animate>
        <div class="container">
            <?php the_content(); ?>
        </div>
    </section>
<?php endwhile; endif; ?>
<?php
get_footer();
