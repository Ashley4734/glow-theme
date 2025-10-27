<?php
// One-time import function - run via WP Admin
function glow_import_products_from_json($json_file = '') {
    if (empty($json_file)) {
        $json_file = get_theme_file_path('Affiliate/affiliate_products.json');
    }

    $json_file = wp_normalize_path($json_file);

    if (empty($json_file) || !file_exists($json_file) || !is_readable($json_file)) {
        return new WP_Error('file_not_found', __('JSON file not found or is not readable.', 'glow-curated'));
    }

    $json_content = file_get_contents($json_file);

    if ($json_content === false) {
        return new WP_Error('read_error', __('Unable to read JSON file.', 'glow-curated'));
    }

    $products = json_decode($json_content, true);

    if (json_last_error() !== JSON_ERROR_NONE || empty($products)) {
        return new WP_Error('invalid_json', __('Invalid JSON format.', 'glow-curated'));
    }

    if (!is_array($products)) {
        $products = array($products);
    }

    // Ensure we have a zero-indexed array
    $products = array_values($products);

    $imported = 0;
    $duplicates = 0;
    $invalid = 0;

    foreach ($products as $product) {
        if (!is_array($product)) {
            $invalid++;
            continue;
        }

        $brand = isset($product['Brand']) ? sanitize_text_field($product['Brand']) : '';
        $description = isset($product['Description']) ? wp_kses_post($product['Description']) : '';
        $usage = isset($product['How To Use']) ? wp_kses_post($product['How To Use']) : '';
        $title = isset($product['Product Name']) ? sanitize_text_field($product['Product Name']) : $brand;
        $affiliate_link = isset($product['Affiliate Link']) ? esc_url_raw($product['Affiliate Link']) : '';
        $image_url = isset($product['Product Image URL']) ? esc_url_raw($product['Product Image URL']) : '';
        $price = isset($product['price']) ? sanitize_text_field($product['price']) : '';
        $features = glow_prepare_product_features($product['Product Features'] ?? array());

        $asin = '';
        if (!empty($product['ASIN'])) {
            $asin = sanitize_text_field($product['ASIN']);
        } elseif (!empty($affiliate_link)) {
            if (preg_match('~/dp/([A-Z0-9]{10})~i', $affiliate_link, $matches)) {
                $asin = strtoupper($matches[1]);
            }
        }

        if (empty($title) || empty($description) || empty($asin)) {
            $invalid++;
            continue;
        }

        // Check if product already exists by ASIN
        $existing = get_posts(array(
            'post_type' => 'glow_product',
            'meta_key' => '_glow_asin',
            'meta_value' => $asin,
            'fields' => 'ids',
            'posts_per_page' => 1,
        ));

        if ($existing) {
            $duplicates++;
            continue;
        }

        $post_id = wp_insert_post(array(
            'post_title' => $title,
            'post_content' => $description,
            'post_status' => 'publish',
            'post_type' => 'glow_product',
            'post_excerpt' => wp_trim_words(wp_strip_all_tags($description), 20),
        ));

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_glow_brand', $brand);
            update_post_meta($post_id, '_glow_asin', $asin);
            if (!empty($usage)) {
                update_post_meta($post_id, '_glow_usage', $usage);
            }
            if (!empty($price)) {
                update_post_meta($post_id, '_glow_price', $price);
            }
            if (!empty($affiliate_link)) {
                update_post_meta($post_id, '_glow_affiliate_url', esc_url_raw($affiliate_link));
            }

            if (!empty($features)) {
                update_post_meta($post_id, '_glow_features', $features);
            }

            $category = glow_determine_product_category(array(
                'Description' => $description,
                'Brand' => $brand,
            ));

            if ($category) {
                wp_set_object_terms($post_id, $category, 'product_category');
            }

            if (!empty($image_url) && !has_post_thumbnail($post_id)) {
                $attachment_id = glow_import_product_image_from_url($image_url, $post_id, $title);
                if (!is_wp_error($attachment_id) && $attachment_id) {
                    set_post_thumbnail($post_id, $attachment_id);
                }
            }

            $imported++;
        } else {
            $invalid++;
        }
    }

    return array(
        'imported' => $imported,
        'duplicates' => $duplicates,
        'invalid' => $invalid,
        'total' => count($products),
    );
}

function glow_prepare_product_features($raw_features) {
    $features = array();

    if (is_array($raw_features)) {
        foreach ($raw_features as $feature) {
            $clean = trim(wp_strip_all_tags((string) $feature));
            if (!empty($clean)) {
                $features[] = sanitize_text_field($clean);
            }
        }
    } elseif (is_string($raw_features)) {
        $parts = preg_split('/\r?\n|\s{2,}/', $raw_features);
        foreach ($parts as $feature) {
            $clean = trim(wp_strip_all_tags($feature));
            if (!empty($clean)) {
                $features[] = sanitize_text_field($clean);
            }
        }
    }

    return array_values(array_unique($features));
}

function glow_import_product_image_from_url($image_url, $post_id, $title) {
    if (empty($image_url) || !filter_var($image_url, FILTER_VALIDATE_URL)) {
        return new WP_Error('invalid_image_url', __('Invalid product image URL provided.', 'glow-curated'));
    }

    $image_url = esc_url_raw($image_url);

    $existing = get_posts(array(
        'post_type' => 'attachment',
        'meta_key' => '_glow_source_url',
        'meta_value' => $image_url,
        'posts_per_page' => 1,
        'fields' => 'ids',
    ));

    if (!empty($existing)) {
        $attachment_id = (int) $existing[0];
        set_post_thumbnail($post_id, $attachment_id);
        return $attachment_id;
    }

    if (!function_exists('media_sideload_image')) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
    }
    if (!function_exists('download_url')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    if (!function_exists('wp_read_image_metadata')) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $attachment_id = media_sideload_image($image_url, $post_id, $title, 'id');

    if (is_wp_error($attachment_id)) {
        return $attachment_id;
    }

    update_post_meta($attachment_id, '_glow_source_url', $image_url);

    if (!metadata_exists('post', $attachment_id, '_wp_attachment_image_alt')) {
        update_post_meta($attachment_id, '_wp_attachment_image_alt', sanitize_text_field($title));
    }

    return (int) $attachment_id;
}

// Helper function to categorize products
function glow_determine_product_category($product) {
    $description = strtolower($product['Description']);
    $brand = strtolower($product['Brand']);

    if (strpos($description, 'sunscreen') !== false || strpos($description, 'spf') !== false) {
        return 'sunscreen';
    } elseif (strpos($description, 'lipstick') !== false || strpos($description, 'lip') !== false) {
        return 'makeup';
    } elseif (strpos($description, 'serum') !== false || strpos($description, 'moisturizer') !== false) {
        return 'skincare';
    } elseif (strpos($description, 'perfume') !== false || strpos($description, 'fragrance') !== false) {
        return 'fragrance';
    }

    return 'uncategorized';
}

// Add admin page for import
function glow_add_import_page() {
    add_management_page(
        'Import Products',
        'Import Products',
        'manage_options',
        'glow-import',
        'glow_import_page_html'
    );
}
add_action('admin_menu', 'glow_add_import_page');

function glow_import_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $message = '';
    $class = '';

    if (isset($_POST['import_products'])) {
        check_admin_referer('glow_import_products', 'glow_import_nonce');

        $json_path = '';
        $delete_after = false;

        if (!empty($_FILES['glow_products_json']['name'])) {
            if (!function_exists('wp_handle_upload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            $uploaded = wp_handle_upload(
                $_FILES['glow_products_json'],
                array(
                    'test_form' => false,
                    'mimes' => array(
                        'json' => 'application/json',
                        'txt' => 'text/plain',
                    ),
                )
            );

            if (isset($uploaded['error'])) {
                $message = $uploaded['error'];
                $class = 'error';
            } elseif (!empty($uploaded['file'])) {
                $json_path = $uploaded['file'];
                $delete_after = true;
            }
        }

        if (empty($message)) {
            $result = glow_import_products_from_json($json_path);

            if ($delete_after && !empty($json_path) && file_exists($json_path)) {
                wp_delete_file($json_path);
            }

            if (is_wp_error($result)) {
                $message = $result->get_error_message();
                $class = 'error';
            } else {
                $message = sprintf(
                    /* translators: 1: imported count, 2: duplicate count, 3: invalid count */
                    __('Import complete! Imported: %1$d, Duplicates skipped: %2$d, Invalid rows: %3$d.', 'glow-curated'),
                    intval($result['imported']),
                    intval($result['duplicates']),
                    intval($result['invalid'])
                );
                $class = 'updated';
            }
        }
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Import Products from JSON', 'glow-curated'); ?></h1>
        <?php if (!empty($message)) : ?>
            <div class="notice notice-<?php echo esc_attr($class); ?>"><p><?php echo esc_html($message); ?></p></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('glow_import_products', 'glow_import_nonce'); ?>
            <p><?php esc_html_e('Select a JSON file exported from Glow Curated or leave blank to use the bundled affiliate_products.json file.', 'glow-curated'); ?></p>
            <p>
                <label for="glow_products_json" class="screen-reader-text"><?php esc_html_e('Affiliate product JSON file', 'glow-curated'); ?></label>
                <input type="file" id="glow_products_json" name="glow_products_json" accept="application/json,.json,.txt" />
            </p>
            <?php submit_button(__('Import Products', 'glow-curated'), 'primary', 'import_products'); ?>
        </form>
    </div>
    <?php
}
