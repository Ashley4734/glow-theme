<?php
/**
 * Template part for displaying a product card.
 *
 * @param array $args Optional. Arguments including product_id.
 */

$product_id = $args['product_id'] ?? get_the_ID();
$product = get_post($product_id);

if (!$product) {
    return;
}

$brand = glow_get_product_meta($product_id, 'glow_brand');
$price = glow_get_product_meta($product_id, 'glow_price');
$usage = glow_get_product_meta($product_id, 'glow_usage');
$rating = glow_get_product_meta($product_id, 'glow_rating');
$affiliate_url = glow_get_product_affiliate_link($product_id);
$show_images = !get_theme_mod('glow_text_only_mode', false);
?>
<article class="product-card">
    <?php if ($show_images && has_post_thumbnail($product_id)) : ?>
        <div class="product-image">
            <?php echo get_the_post_thumbnail($product_id, 'glow-product', array('loading' => 'lazy')); ?>
        </div>
    <?php endif; ?>
    <div class="product-details">
        <?php if ($brand) : ?>
            <span class="product-brand"><?php echo esc_html($brand); ?></span>
        <?php endif; ?>
        <h3 class="product-title"><?php echo esc_html(get_the_title($product_id)); ?></h3>
        <?php if (!empty($product->post_excerpt)) : ?>
            <p class="product-excerpt"><?php echo esc_html($product->post_excerpt); ?></p>
        <?php endif; ?>
        <ul class="product-meta">
            <?php if ($price) : ?>
                <li><strong><?php esc_html_e('Price Range', 'glow-curated'); ?>:</strong> <?php echo esc_html($price); ?></li>
            <?php endif; ?>
            <?php if ($rating) : ?>
                <li><strong><?php esc_html_e('Rating', 'glow-curated'); ?>:</strong> <?php echo esc_html($rating); ?> / 5</li>
            <?php endif; ?>
        </ul>
        <?php if ($usage) : ?>
            <p class="product-usage"><strong><?php esc_html_e('How to Use', 'glow-curated'); ?>:</strong> <?php echo esc_html($usage); ?></p>
        <?php endif; ?>
        <?php if ($affiliate_url) : ?>
            <div class="product-actions">
                <a class="btn btn-primary" href="<?php echo esc_url($affiliate_url); ?>" target="_blank" rel="nofollow sponsored noopener"><?php esc_html_e('Shop Now', 'glow-curated'); ?></a>
            </div>
        <?php endif; ?>
    </div>
</article>
