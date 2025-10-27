<?php
/**
 * Archive template (enhanced).
 *
 * @package Glow_Curated
 */

get_header();

// Derive archive context
$qo            = get_queried_object();
$archive_title = get_the_archive_title();
$archive_desc  = get_the_archive_description();

// Build base URL for sort links (preserves archive query context)
$base_url = home_url(add_query_arg([])); // current archive URL
$current_orderby = isset($_GET['orderby']) ? sanitize_text_field(wp_unslash($_GET['orderby'])) : 'date';
$current_order   = isset($_GET['order']) ? strtoupper(sanitize_text_field(wp_unslash($_GET['order']))) : 'DESC';

// Constrain to allowed values
$allowed_orderby = ['date', 'comment_count'];
$allowed_order   = ['ASC', 'DESC'];

if ( ! in_array($current_orderby, $allowed_orderby, true) ) {
	$current_orderby = 'date';
}
if ( ! in_array($current_order, $allowed_order, true) ) {
	$current_order = 'DESC';
}

// Apply ordering to the main query (safe: pre_get_posts usually preferred, but this is simple and localized)
global $wp_query;
$wp_query->set('orderby', $current_orderby);
$wp_query->set('order', $current_order);
$wp_query->get_posts();
?>
<section class="blog-hub-hero" data-animate>
	<div class="container">
		<nav class="breadcrumb" aria-label="<?php echo esc_attr_x('Breadcrumb', 'nav aria-label', 'glow-curated'); ?>" itemscope itemtype="https://schema.org/BreadcrumbList">
			<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<a itemprop="item" href="<?php echo esc_url(home_url('/')); ?>">
					<span itemprop="name"><?php echo esc_html_x('Home', 'breadcrumb', 'glow-curated'); ?></span>
				</a>
				<meta itemprop="position" content="1" />
			</span>
			<span class="sep" aria-hidden="true"> / </span>
			<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">
				<span itemprop="name"><?php echo esc_html($archive_title); ?></span>
				<meta itemprop="position" content="2" />
			</span>
		</nav>

		<div class="blog-hub-hero__inner">
			<p class="eyebrow"><?php esc_html_e('Glow Journal', 'glow-curated'); ?></p>
			<h1><?php echo esc_html($archive_title); ?></h1>
			<?php if ($archive_desc) : ?>
				<p class="lede"><?php echo wp_kses_post($archive_desc); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="blog-hub-list" aria-labelledby="archive-heading">
	<div class="container">
		<header class="blog-hub-list__header">
			<h2 id="archive-heading"><?php esc_html_e('Curated stories', 'glow-curated'); ?></h2>
			<p><?php esc_html_e('Explore every editorial within this collection.', 'glow-curated'); ?></p>
		</header>

		<!-- Optional: lightweight sort controls -->
		<div class="archive-sort-controls" role="group" aria-label="<?php echo esc_attr_x('Sort posts', 'controls aria-label', 'glow-curated'); ?>">
			<?php
			// Helper to render a sort link
			$make_sort_link = function ($label, $orderby, $order = 'DESC') use ($base_url, $current_orderby, $current_order) {
				$is_active = ($current_orderby === $orderby && $current_order === strtoupper($order));
				$url       = add_query_arg(
					[
						'orderby' => $orderby,
						'order'   => strtoupper($order),
					],
					$base_url
				);
				printf(
					'<a class="btn btn-pill %1$s" href="%2$s" rel="nofollow">%3$s</a>',
					$is_active ? 'is-active' : '',
					esc_url($url),
					esc_html($label)
				);
			};

			$make_sort_link( _x('Newest', 'sort label', 'glow-curated'), 'date', 'DESC' );
			$make_sort_link( _x('Oldest', 'sort label', 'glow-curated'), 'date', 'ASC' );
			$make_sort_link( _x('Most discussed', 'sort label', 'glow-curated'), 'comment_count', 'DESC' );
			?>
		</div>

		<?php if (have_posts()) : ?>
			<div class="blog-card-grid" role="list">
				<?php
				while (have_posts()) :
					the_post();
					// Your card partial should render each <article> with proper headings, dates, and lazy images.
					get_template_part('template-parts/content', 'blog');
				endwhile;
				?>
			</div>

			<nav class="pagination" role="navigation" aria-label="<?php echo esc_attr_x('Posts pagination', 'nav aria-label', 'glow-curated'); ?>">
				<?php
				the_posts_pagination(
					[
						'mid_size'           => 1,
						'prev_text'          => esc_html_x('Previous', 'pagination', 'glow-curated'),
						'next_text'          => esc_html_x('Next', 'pagination', 'glow-curated'),
						'screen_reader_text' => esc_html_x('Posts navigation', 'pagination screen reader text', 'glow-curated'),
					]
				);
				?>
			</nav>
		<?php else : ?>
			<p class="text-center no-results">
				<?php esc_html_e('No articles available in this archive yet.', 'glow-curated'); ?>
			</p>
			<div class="archive-cta text-center">
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
// Optional JSON-LD breadcrumb (helps when no SEO plugin handles it).
$breadcrumb_json = [
	'@context'        => 'https://schema.org',
	'@type'           => 'BreadcrumbList',
	'itemListElement' => [
		[
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => __('Home', 'glow-curated'),
			'item'     => home_url('/'),
		],
		[
			'@type'    => 'ListItem',
			'position' => 2,
			'name'     => wp_strip_all_tags($archive_title),
			'item'     => esc_url_raw( get_permalink( get_queried_object_id() ) ? get_permalink( get_queried_object_id() ) : home_url( add_query_arg( [] ) ) ),
		],
	],
];
?>
<script type="application/ld+json">
<?php echo wp_json_encode( $breadcrumb_json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ); ?>
</script>

<?php
get_footer();