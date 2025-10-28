<?php
/**
 * Main template file
 *
 * @package Glow_Curated
 */

get_header();
?>
<section class="page-hero" data-animate>
    <div class="container">
        <h1><?php esc_html_e( 'Latest Stories', 'glow-curated' ); ?></h1>
        <p><?php esc_html_e( 'Browse the newest editorials from Glow Curated.', 'glow-curated' ); ?></p>
    </div>
</section>
<div class="container page-content" data-animate>
    <?php if ( have_posts() ) : ?>
        <div class="blog-card-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card blog-card--archive' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="blog-card__media">
                            <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
                        </div>
                    <?php endif; ?>
                    <div class="blog-card__content">
                        <p class="eyebrow"><?php echo esc_html( get_the_date( _x( 'M j, Y', 'archive date format', 'glow-curated' ) ) ); ?></p>
                        <h2 class="blog-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32, '…' ) ); ?></p>
                        <a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Keep reading', 'glow-curated' ); ?></a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        <div class="pagination">
            <?php
            the_posts_pagination(
                array(
                    'mid_size'  => 2,
                    'prev_text' => __( 'Previous', 'glow-curated' ),
                    'next_text' => __( 'Next', 'glow-curated' ),
                )
            );
            ?>
        </div>
    <?php else : ?>
        <p><?php esc_html_e( 'No posts found. Publish your first article to see it here.', 'glow-curated' ); ?></p>
    <?php endif; ?>
</div>
<?php
get_footer();
