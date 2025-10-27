<?php
get_header();
?>
<main id="main" class="main-content" tabindex="-1">
    <?php while (have_posts()) : the_post();
        $product_id   = get_the_ID();
        $brand        = glow_get_product_meta($product_id, 'glow_brand');
        $price        = glow_get_product_meta($product_id, 'glow_price');
        $rating       = glow_get_product_meta($product_id, 'glow_rating');
        $usage        = glow_get_product_meta($product_id, 'glow_usage');
        $affiliate    = glow_get_product_affiliate_link($product_id);
        $features     = glow_get_product_features($product_id);
        $show_images  = !get_theme_mod('glow_text_only_mode', false);
        $categories   = get_the_terms($product_id, 'product_category');
        $category     = '';

        if (!empty($categories) && !is_wp_error($categories)) {
            $category = $categories[0]->name;
        }
    ?>
        <article <?php post_class('product-page'); ?>>
            <section class="product-hero">
                <div class="container">
                    <p class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'glow-curated'); ?>">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'glow-curated'); ?></a>
                        /
                        <a href="<?php echo esc_url(get_post_type_archive_link('glow_product')); ?>"><?php esc_html_e('Products', 'glow-curated'); ?></a>
                        /
                        <span class="breadcrumb-current"><?php the_title(); ?></span>
                    </p>
                    <div class="product-hero__grid">
                        <?php if ($show_images && has_post_thumbnail()) : ?>
                            <div class="product-hero__media">
                                <?php the_post_thumbnail('glow-hero', array(
                                    'loading' => 'eager',
                                    'sizes'   => '(max-width: 768px) 100vw, 640px',
                                    'decoding' => 'async',
                                )); ?>
                            </div>
                        <?php endif; ?>
                        <div class="product-hero__summary">
                            <?php if (!empty($brand)) : ?>
                                <span class="product-brand"><?php echo esc_html($brand); ?></span>
                            <?php endif; ?>
                            <h1><?php the_title(); ?></h1>
                            <?php if (has_excerpt()) : ?>
                                <p class="product-summary"><?php echo esc_html(get_the_excerpt()); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($category) || !empty($price) || !empty($rating)) : ?>
                                <ul class="product-hero__meta">
                                    <?php if (!empty($category)) : ?>
                                        <li><strong><?php esc_html_e('Category', 'glow-curated'); ?>:</strong> <?php echo esc_html($category); ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($price)) : ?>
                                        <li><strong><?php esc_html_e('Investment Level', 'glow-curated'); ?>:</strong> <?php echo esc_html($price); ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($rating)) : ?>
                                        <li><strong><?php esc_html_e('Rating', 'glow-curated'); ?>:</strong> <?php echo esc_html($rating); ?> / 5</li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                            <?php if (!empty($affiliate)) : ?>
                                <div class="product-actions">
                                    <a class="btn btn-primary" href="<?php echo esc_url($affiliate); ?>" target="_blank" rel="nofollow sponsored noopener"><?php esc_html_e('Shop Now', 'glow-curated'); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <div class="product-page__body">
                <div class="container">
                    <div class="product-page__layout">
                        <div class="product-page__content">
                            <?php the_content(); ?>
                            <?php if (!empty($usage)) : ?>
                                <section class="product-usage">
                                    <h2><?php esc_html_e('How to Use', 'glow-curated'); ?></h2>
                                    <p><?php echo esc_html($usage); ?></p>
                                </section>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($features)) : ?>
                            <aside class="product-features" aria-labelledby="product-highlights-heading">
                                <h2 id="product-highlights-heading"><?php esc_html_e('Why We Love It', 'glow-curated'); ?></h2>
                                <ul>
                                    <?php foreach ($features as $feature) : ?>
                                        <li><?php echo esc_html($feature); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </aside>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<?php
get_footer();
