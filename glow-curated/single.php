<?php
/**
 * Single Post – Glow Curated (enhanced)
 * Keeps your ACF + meta usage and adds ToC, share, progress, schema.
 */

get_header(); ?>

<main id="main" class="main-content" tabindex="-1">
<?php while ( have_posts() ) : the_post(); ?>

<?php
    // Flags / classes
    $hide_title   = get_post_meta( get_the_ID(), '_glow_hide_post_title', true );
    $hero_classes = 'blog-hero' . ( ! empty( $hide_title ) ? ' blog-hero--title-hidden' : '' );

    // Read time (uses your helper if present)
    if ( function_exists( 'glow_reading_time' ) ) {
        $read_time = glow_reading_time();
    } else {
        $words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ) );
        $read_time = max( 1, ceil( $words / 220 ) );
    }

    // Build ToC from headings in content
    function glow_build_toc_and_anchors( $content ) {
        if ( empty( $content ) ) return array( 'toc' => '', 'content' => $content );

        $dom = new DOMDocument();
        libxml_use_internal_errors( true );
        $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $content );
        libxml_clear_errors();

        $xpath = new DOMXPath( $dom );
        $nodes = $xpath->query('//h2 | //h3');

        $items = array();
        $index = 0;
        foreach ( $nodes as $node ) {
            $text = trim( $node->textContent );
            if ( ! $text ) continue;
            $slug = sanitize_title( $text ) . '-' . (++$index);
            $node->setAttribute( 'id', $slug );
            $tag  = strtolower( $node->nodeName );
            $items[] = array( 'id' => $slug, 'text' => $text, 'level' => $tag );
        }

        $toc_html = '';
        if ( ! empty( $items ) ) {
            $toc_html .= '<nav class="toc" aria-label="' . esc_attr__( 'Table of contents', 'glow-curated' ) . '"><strong>' . esc_html__( 'On this page', 'glow-curated' ) . '</strong><ul>';
            foreach ( $items as $it ) {
                $cls = $it['level'] === 'h3' ? ' class="toc-sub"' : '';
                $toc_html .= '<li' . $cls . '><a href="#' . esc_attr( $it['id'] ) . '">' . esc_html( $it['text'] ) . '</a></li>';
            }
            $toc_html .= '</ul></nav>';
        }

        // Return modified HTML
        $body = $dom->getElementsByTagName('body')->item(0);
        $new  = '';
        foreach ( $body->childNodes as $child ) {
            $new .= $dom->saveHTML( $child );
        }
        return array( 'toc' => $toc_html, 'content' => $new );
    }

    $raw_content = apply_filters( 'the_content', get_post_field( 'post_content', get_the_ID() ) );
    $toc_bundle  = glow_build_toc_and_anchors( $raw_content );
?>

<article <?php post_class('blog-post'); ?>>

    <!-- Reading progress -->
    <div class="reading-progress" aria-hidden="true"><span class="reading-progress__bar"></span></div>

    <!-- Hero -->
    <section class="<?php echo esc_attr( $hero_classes ); ?>">
        <?php if ( has_post_thumbnail() && ! get_theme_mod( 'glow_text_only_mode', false ) ) : ?>
            <div class="hero-image-wrapper">
                <?php
                the_post_thumbnail(
                    'glow-hero',
                    array(
                        'class'         => 'hero-image',
                        'loading'       => 'eager',
                        'fetchpriority' => 'high',
                        'srcset'        => wp_get_attachment_image_srcset( get_post_thumbnail_id(), 'glow-hero' ),
                        'sizes'         => '(max-width: 768px) 100vw, 1920px'
                    )
                );
                ?>
                <div class="hero-overlay"></div>
            </div>
        <?php endif; ?>

        <div class="hero-content <?php echo has_post_thumbnail() ? 'with-image' : 'text-only'; ?>">
            <div class="container">
                <p class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb','glow-curated'); ?>">
                    <a href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e('Home','glow-curated'); ?></a> /
                    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e('Glow Journal','glow-curated'); ?></a>
                </p>

                <span class="eyebrow"><?php the_category( ', ' ); ?></span>

                <?php if ( empty( $hide_title ) ) : ?>
                    <h1><?php the_title(); ?></h1>
                <?php endif; ?>

                <?php if ( has_excerpt() ) : ?>
                    <p class="lede"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
                <?php endif; ?>

                <div class="meta">
                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                    <span class="separator">·</span>
                    <span class="read-time"><?php echo esc_html( $read_time ); ?> <?php esc_html_e( 'min read', 'glow-curated' ); ?></span>
                    <?php if ( get_the_author_meta('display_name') ) : ?>
                        <span class="separator">·</span>
                        <span class="byline">
                            <?php echo get_avatar( get_the_author_meta('ID'), 28 ); ?>
                            <span><?php echo esc_html( get_the_author_meta('display_name') ); ?></span>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Compact share buttons -->
                <div class="share">
                    <?php
                        $url   = urlencode( get_permalink() );
                        $title = urlencode( get_the_title() );
                    ?>
                    <a class="share-btn" href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Share on X','glow-curated'); ?>">X</a>
                    <a class="share-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Share on Facebook','glow-curated'); ?>">Fb</a>
                    <a class="share-btn" href="https://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&description=<?php echo $title; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Share on Pinterest','glow-curated'); ?>">Pin</a>
                    <button class="share-btn" data-copy-link type="button" aria-label="<?php esc_attr_e('Copy link','glow-curated'); ?>"><?php esc_html_e('Copy','glow-curated'); ?></button>
                </div>
            </div>
        </div>
    </section>

    <!-- Affiliate Disclaimer -->
    <?php get_template_part( 'template-parts/affiliate-disclaimer' ); ?>

    <!-- Article Body -->
    <div class="article-body container">
        <?php if ( ! empty( $toc_bundle['toc'] ) ) : ?>
            <aside class="article-aside">
                <?php echo $toc_bundle['toc']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </aside>
        <?php endif; ?>

        <div class="content-wrapper">
            <?php
                // Output content with injected heading IDs
                echo $toc_bundle['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>

            <?php
            // Tags
            $tags = get_the_tags();
            if ( $tags ) : ?>
                <div class="tag-list" aria-label="<?php esc_attr_e('Tags','glow-curated'); ?>">
                    <?php foreach ( $tags as $tag ) : ?>
                        <a class="tag-pill" href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">#<?php echo esc_html( $tag->name ); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Related Products -->
        <?php
        $related_products = array();
        if ( function_exists('get_field') ) {
            $related_products = get_field('related_products');
        } elseif ( metadata_exists('post', get_the_ID(), '_glow_related_products') ) {
            $related_products = (array) get_post_meta( get_the_ID(), '_glow_related_products', true );
        }

        if ( ! empty( $related_products ) ) : ?>
            <section class="related-products">
                <h2><?php esc_html_e( 'Featured Products', 'glow-curated' ); ?></h2>
                <div class="product-grid">
                    <?php foreach ( $related_products as $product_id ) :
                        get_template_part( 'template-parts/content', 'product', array( 'product_id' => $product_id ) );
                    endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- You May Also Like (fallback if no products) -->
        <?php if ( empty( $related_products ) ) :
            $cats = wp_get_post_categories( get_the_ID() );
            $rel  = new WP_Query( array(
                'posts_per_page' => 3,
                'post__not_in'   => array( get_the_ID() ),
                'category__in'   => $cats,
                'ignore_sticky_posts' => 1,
            ) );
            if ( $rel->have_posts() ) : ?>
                <section class="related-posts">
                    <h2><?php esc_html_e('You may also like','glow-curated'); ?></h2>
                    <div class="related-grid">
                        <?php while ( $rel->have_posts() ) : $rel->the_post(); ?>
                            <article class="related-card">
                                <a class="related-card__media" href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
                                </a>
                                <h3 class="related-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Post Navigation -->
    <nav class="post-navigation container" aria-label="<?php esc_attr_e('Post navigation', 'glow-curated'); ?>">
        <?php
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        if ( $prev_post ) : ?>
            <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="nav-previous">
                <span class="nav-label"><?php esc_html_e( 'Previous', 'glow-curated' ); ?></span>
                <span class="nav-title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
            </a>
        <?php endif; ?>
        <?php if ( $next_post ) : ?>
            <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="nav-next">
                <span class="nav-label"><?php esc_html_e( 'Next', 'glow-curated' ); ?></span>
                <span class="nav-title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
            </a>
        <?php endif; ?>
    </nav>

    <!-- JSON-LD Article schema -->
    <?php
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Article',
        'headline' => wp_strip_all_tags( get_the_title() ),
        'datePublished' => get_the_date( 'c' ),
        'dateModified'  => get_the_modified_date( 'c' ),
        'author' => array(
            '@type' => 'Person',
            'name'  => get_the_author_meta( 'display_name' ),
        ),
        'mainEntityOfPage' => get_permalink(),
    );
    if ( has_post_thumbnail() ) {
        $img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
        if ( $img ) $schema['image'] = array( $img[0] );
    }
    ?>
    <script type="application/ld+json"><?php echo wp_json_encode( $schema ); // phpcs:ignore ?></script>

</article>

<?php endwhile; ?>
</main>

<script>
/* Reading progress + copy link */
(function(){
  var bar = document.querySelector('.reading-progress__bar');
  if(!bar) return;
  function onScroll(){
    var el = document.querySelector('.content-wrapper');
    if(!el) return;
    var rect = el.getBoundingClientRect(), sc = window.scrollY || window.pageYOffset;
    var start = el.offsetTop, end = start + el.offsetHeight - window.innerHeight;
    var p = Math.max(0, Math.min(1, (sc - start) / (end - start)));
    bar.style.transform = 'scaleX(' + p + ')';
  }
  window.addEventListener('scroll', onScroll, {passive:true}); onScroll();

  var btn = document.querySelector('[data-copy-link]');
  if (btn) btn.addEventListener('click', async function(){
    try { await navigator.clipboard.writeText('<?php echo esc_js( get_permalink() ); ?>'); this.textContent = 'Copied'; setTimeout(()=>this.textContent='Copy',1500); } catch(e){}
  });
})();
</script>

<?php get_footer(); ?>