<?php
/**
 * Template part for displaying blog cards in listings.
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('blog-card'); ?> role="listitem" data-animate>
    <a class="blog-card__link" href="<?php the_permalink(); ?>">
        <?php
        $categories = get_the_category();
        if (!empty($categories)) {
            echo '<span class="blog-card__tag">' . esc_html($categories[0]->name) . '</span>';
        }
        ?>
        <h3 class="blog-card__title"><?php the_title(); ?></h3>
        <p class="blog-card__excerpt"><?php echo esc_html(wp_strip_all_tags(get_the_excerpt())); ?></p>
        <dl class="blog-card__meta">
            <div>
                <dt><?php esc_html_e('Published', 'glow-curated'); ?></dt>
                <dd><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time></dd>
            </div>
            <div>
                <dt><?php esc_html_e('Reading time', 'glow-curated'); ?></dt>
                <dd><?php echo esc_html(glow_reading_time()); ?> <?php esc_html_e('minutes', 'glow-curated'); ?></dd>
            </div>
        </dl>
    </a>
</article>
