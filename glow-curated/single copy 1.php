<?php get_header(); ?>

<main id="main" class="main-content" tabindex="-1">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $hide_title = get_post_meta(get_the_ID(), '_glow_hide_post_title', true);
        $hero_classes = 'blog-hero';

        if (!empty($hide_title)) {
            $hero_classes .= ' blog-hero--title-hidden';
        }
        ?>
        <article <?php post_class('blog-post'); ?>>
            <!-- Hero Section -->
            <section class="<?php echo esc_attr($hero_classes); ?>">
                <?php if (has_post_thumbnail() && !get_theme_mod('glow_text_only_mode', false)) : ?>
                    <div class="hero-image-wrapper">
                        <?php
                        the_post_thumbnail('glow-hero', array(
                            'class' => 'hero-image',
                            'loading' => 'eager',
                            'srcset' => wp_get_attachment_image_srcset(get_post_thumbnail_id(), 'glow-hero'),
                            'sizes' => '(max-width: 768px) 100vw, 1920px'
                        ));
                        ?>
                        <div class="hero-overlay"></div>
                    </div>
                <?php endif; ?>

                <div class="hero-content <?php echo has_post_thumbnail() ? 'with-image' : 'text-only'; ?>">
                    <div class="container">
                        <span class="eyebrow"><?php the_category(', '); ?></span>
                        <?php if (empty($hide_title)) : ?>
                            <h1><?php the_title(); ?></h1>
                        <?php endif; ?>
                        <?php if (has_excerpt()) : ?>
                            <p class="lede"><?php echo get_the_excerpt(); ?></p>
                        <?php endif; ?>
                        <div class="meta">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                            <span class="separator">·</span>
                            <span class="read-time"><?php echo esc_html(glow_reading_time()); ?> <?php esc_html_e('min read', 'glow-curated'); ?></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Affiliate Disclaimer -->
            <?php get_template_part('template-parts/affiliate-disclaimer'); ?>

            <!-- Article Content -->
            <div class="article-body container">
                <div class="content-wrapper">
                    <?php the_content(); ?>
                </div>

                <!-- Related Products Section -->
                <?php
                $related_products = array();
                if (function_exists('get_field')) {
                    $related_products = get_field('related_products');
                } elseif (metadata_exists('post', get_the_ID(), '_glow_related_products')) {
                    $related_products = (array) get_post_meta(get_the_ID(), '_glow_related_products', true);
                }

                if (!empty($related_products)) : ?>
                    <section class="related-products">
                        <h2><?php esc_html_e('Featured Products', 'glow-curated'); ?></h2>
                        <div class="product-grid">
                            <?php foreach ($related_products as $product_id) :
                                get_template_part('template-parts/content', 'product', array('product_id' => $product_id));
                            endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>
            </div>

            <!-- Post Navigation -->
            <nav class="post-navigation container" aria-label="<?php esc_attr_e('Post navigation', 'glow-curated'); ?>">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                <?php if ($prev_post) : ?>
                    <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="nav-previous">
                        <span class="nav-label"><?php esc_html_e('Previous', 'glow-curated'); ?></span>
                        <span class="nav-title"><?php echo esc_html(get_the_title($prev_post)); ?></span>
                    </a>
                <?php endif; ?>
                <?php if ($next_post) : ?>
                    <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="nav-next">
                        <span class="nav-label"><?php esc_html_e('Next', 'glow-curated'); ?></span>
                        <span class="nav-title"><?php echo esc_html(get_the_title($next_post)); ?></span>
                    </a>
                <?php endif; ?>
            </nav>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
