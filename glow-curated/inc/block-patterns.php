<?php
function glow_register_block_patterns() {
    // Product Showcase Pattern
    register_block_pattern(
        'glow/product-showcase',
        array(
            'title' => 'Product Showcase',
            'description' => 'Display a product with image and details',
            'categories' => array('glow'),
            'content' => '
                <!-- wp:group {"className":"glow-product-showcase"} -->
                <div class="wp-block-group glow-product-showcase">
                    <!-- wp:columns -->
                    <div class="wp-block-columns">
                        <!-- wp:column {"width":"40%"} -->
                        <div class="wp-block-column" style="flex-basis:40%">
                            <!-- wp:image -->
                            <figure class="wp-block-image size-large"><img src="" alt="Product Image"/></figure>
                            <!-- /wp:image -->
                        </div>
                        <!-- /wp:column -->

                        <!-- wp:column {"width":"60%"} -->
                        <div class="wp-block-column" style="flex-basis:60%">
                            <!-- wp:heading {"level":3} -->
                            <h3>Product Name</h3>
                            <!-- /wp:heading -->

                            <!-- wp:paragraph -->
                            <p><strong>Brand:</strong> Luxury Brand</p>
                            <!-- /wp:paragraph -->

                            <!-- wp:paragraph -->
                            <p>Product description goes here. This luxurious formula delivers exceptional results...</p>
                            <!-- /wp:paragraph -->

                            <!-- wp:paragraph -->
                            <p><strong>How to Use:</strong> Apply to clean skin...</p>
                            <!-- /wp:paragraph -->

                            <!-- wp:buttons -->
                            <div class="wp-block-buttons">
                                <!-- wp:button {"className":"is-style-fill"} -->
                                <div class="wp-block-button is-style-fill">
                                    <a class="wp-block-button__link" href="#" rel="nofollow sponsored noopener" target="_blank">Shop on Amazon</a>
                                </div>
                                <!-- /wp:button -->
                            </div>
                            <!-- /wp:buttons -->
                        </div>
                        <!-- /wp:column -->
                    </div>
                    <!-- /wp:columns -->
                </div>
                <!-- /wp:group -->
            ',
        )
    );

    // Category Grid Pattern
    register_block_pattern(
        'glow/category-grid',
        array(
            'title' => 'Category Grid',
            'description' => 'Four-column category grid like homepage',
            'categories' => array('glow'),
            'content' => '
                <!-- wp:group {"className":"category-grid"} -->
                <div class="wp-block-group category-grid">
                    <!-- wp:columns {"columns":4} -->
                    <div class="wp-block-columns has-4-columns">
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:group {"className":"category-card"} -->
                            <div class="wp-block-group category-card">
                                <!-- wp:image -->
                                <figure class="wp-block-image"><img src="" alt=""/></figure>
                                <!-- /wp:image -->
                                <!-- wp:heading {"level":3} -->
                                <h3>Skincare</h3>
                                <!-- /wp:heading -->
                                <!-- wp:paragraph -->
                                <p>Premium skincare essentials</p>
                                <!-- /wp:paragraph -->
                            </div>
                            <!-- /wp:group -->
                        </div>
                        <!-- /wp:column -->

                        <!-- Repeat for other columns -->
                    </div>
                    <!-- /wp:columns -->
                </div>
                <!-- /wp:group -->
            ',
        )
    );
}
add_action('init', 'glow_register_block_patterns');

// Register pattern category
function glow_register_pattern_categories() {
    register_block_pattern_category(
        'glow',
        array('label' => 'Glow Curated')
    );
}
add_action('init', 'glow_register_pattern_categories');
