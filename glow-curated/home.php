<?php
/**
 * Main template file (enhanced)
 *
 * @package Glow_Curated
 */

get_header();

/**
 * Helper: estimate reading time (minutes)
 */
function glow_curated_read_time( $post_id = null ) {
    $post_id   = $post_id ?: get_the_ID();
    $content   = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $wpm        = 220; // upscale editorial pace
    $minutes    = max( 1, ceil( $word_count / $wpm ) );
    return sprintf( _n( '%d min read', '%d mins', $minutes, 'glow-curated' ), $minutes );
}

$is_paged = (bool) get_query_var( 'paged' );
?>
<section class="page-hero page-hero--subtle" data-animate>
    <div class="container">
        <p class="eyebrow"><?php esc_html_e( 'Glow Journal', 'glow-curated' ); ?></p>
        <h1><?php esc_html_e( 'Latest Stories', 'glow-curated' ); ?></h1>
        <p class="lede"><?php esc_html_e( 'Editor-curated luxury beauty features, routines, and product deep dives.', 'glow-curated' ); ?></p>
    </div>
</section>

<?php if ( have_posts() ) : ?>
    <?php if ( ! $is_paged ) : ?>
        <?php the_post(); // FEATURED lead article on page 1 only ?>
        <section class="featured-story" data-animate>
            <div class="container">
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'featured-card' ); ?>>
                    <a class="featured-card__media" href="<?php the_permalink(); ?>">
                        <?php
                        if ( has_post_thumbnail() ) {
                            // Big, responsive hero image
                            echo wp_get_attachment_image(
                                get_post_thumbnail_id(),
                                'full',
                                false,
                                array(
                                    'class' => 'featured-card__img',
                                    'loading' => 'eager',
                                    'fetchpriority' => 'high',
                                    'sizes' => '(min-width:1200px) 1200px, 100vw',
                                )
                            );
                        }
                        ?>
                        <span class="featured-card__scrim" aria-hidden="true"></span>
                        <span class="featured-card__badge"><?php echo esc_html( ( get_the_category() )[0]->name ?? __( 'Feature', 'glow-curated' ) ); ?></span>
                        <span class="featured-card__meta">
                            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( _x( 'M j, Y', 'archive date format', 'glow-curated' ) ) ); ?></time>
                            <span>•</span>
                            <span><?php echo esc_html( glow_curated_read_time() ); ?></span>
                        </span>
                        <h2 class="featured-card__title"><?php the_title(); ?></h2>
                    </a>
                    <?php if ( has_excerpt() ) : ?>
                        <p class="featured-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 34, '…' ) ); ?></p>
                    <?php endif; ?>
                    <a class="btn btn-primary" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read feature', 'glow-curated' ); ?></a>
                </article>
            </div>
        </section>
    <?php endif; ?>

    <div class="container page-content" data-animate>
        <div class="blog-card-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card blog-card--archive' ); ?>>
                    <a class="blog-card__media" href="<?php the_permalink(); ?>">
                        <?php
                        if ( has_post_thumbnail() ) {
                            echo get_the_post_thumbnail(
                                get_the_ID(),
                                'large',
                                array(
                                    'class'   => 'blog-card__img',
                                    'loading' => 'lazy',
                                    'sizes'   => '(min-width: 1200px) 420px, (min-width: 768px) 33vw, 100vw',
                                )
                            );
                        } else {
                            // Fallback aspect box
                            echo '<span class="blog-card__placeholder" aria-hidden="true"></span>';
                        }
                        ?>
                        <?php
                        $cats = get_the_category();
                        if ( ! empty( $cats ) ) :
                        ?>
                            <span class="blog-card__badge"><?php echo esc_html( $cats[0]->name ); ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="blog-card__content">
                        <p class="blog-card__meta">
                            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( _x( 'M j, Y', 'archive date format', 'glow-curated' ) ) ); ?></time>
                            <span>•</span>
                            <span><?php echo esc_html( glow_curated_read_time() ); ?></span>
                        </p>
                        <h2 class="blog-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="blog-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt() ?: wp_strip_all_tags( get_the_content() ), 26, '…' ) ); ?></p>
                        <a class="text-link" href="<?php the_permalink(); ?>">
                            <?php esc_html_e( 'Keep reading', 'glow-curated' ); ?>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <nav class="pagination" aria-label="<?php esc_attr_e( 'Posts navigation', 'glow-curated' ); ?>">
            <?php
            the_posts_pagination(
                array(
                    'mid_size'           => 1,
                    'prev_text'          => __( 'Previous', 'glow-curated' ),
                    'next_text'          => __( 'Next', 'glow-curated' ),
                    'screen_reader_text' => __( 'Posts navigation', 'glow-curated' ),
                )
            );
            ?>
        </nav>
    </div>
<?php else : ?>
    <div class="container page-content" data-animate>
        <p><?php esc_html_e( 'No posts found. Publish your first article to see it here.', 'glow-curated' ); ?></p>
    </div>
<?php endif; ?>

<?php get_footer(); ?>