<?php
/**
 * Custom search form.
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text"><?php esc_html_e('Search for:', 'glow-curated'); ?></span>
        <input type="search" class="search-field" placeholder="<?php esc_attr_e('Search Glow Curated…', 'glow-curated'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" />
    </label>
    <button type="submit" class="btn btn-primary"><?php esc_html_e('Search', 'glow-curated'); ?></button>
</form>
