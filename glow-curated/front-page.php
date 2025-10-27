<?php
/**
 * Front page template replicating the static homepage design.
 *
 * @package Glow_Curated
 */

get_header();

$pinterest_username = trim(get_theme_mod('glow_pinterest_username', 'GlowCurated'));
$pinterest_handle = ltrim($pinterest_username, '@');
$pinterest_url = 'https://pinterest.com/' . $pinterest_handle;
?>
<section class="hero" data-animate>
    <div class="container">
        <p class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'glow-curated'); ?>"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'glow-curated'); ?></a> / <?php esc_html_e('Luxury Beauty Recommendations', 'glow-curated'); ?></p>
        <h1><?php esc_html_e('Discover Luxury Beauty Worth The Investment', 'glow-curated'); ?></h1>
        <p class="subheadline"><?php esc_html_e('Expert-curated recommendations for premium skincare, high-end makeup, and designer fragrances that actually deliver results.', 'glow-curated'); ?></p>
        <div class="buttons">
            <a class="btn btn-primary" href="<?php echo esc_url($pinterest_url); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Follow on Pinterest ✨', 'glow-curated'); ?></a>
            <a class="btn btn-secondary" href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('Learn More', 'glow-curated'); ?></a>
        </div>
    </div>
</section>

<section class="featured-categories" id="categories" data-animate>
    <div class="container">
        <h2 class="text-center"><?php esc_html_e('What We Curate', 'glow-curated'); ?></h2>
        <div class="grid">
            <?php
            $categories = array(
                array(
                    'icon' => '💎',
                    'title' => __('Luxury Skincare', 'glow-curated'),
                    'description' => __('Premium serums, moisturizers, and treatments from brands like La Mer, SK-II, and Drunk Elephant.', 'glow-curated'),
                    'url' => home_url('/pinterest/#skincare'),
                    'cta' => __('Explore skincare', 'glow-curated'),
                ),
                array(
                    'icon' => '✨',
                    'title' => __('High-End Makeup', 'glow-curated'),
                    'description' => __('Prestige cosmetics from Charlotte Tilbury, Pat McGrath, Natasha Denona, and more.', 'glow-curated'),
                    'url' => home_url('/pinterest/#makeup'),
                    'cta' => __('Explore makeup', 'glow-curated'),
                ),
                array(
                    'icon' => '🌸',
                    'title' => __('Designer Fragrances', 'glow-curated'),
                    'description' => __('Signature scents from Tom Ford, Chanel, Dior, and niche perfume houses.', 'glow-curated'),
                    'url' => home_url('/pinterest/#fragrance'),
                    'cta' => __('Explore fragrance', 'glow-curated'),
                ),
                array(
                    'icon' => '🛁',
                    'title' => __('Beauty Tools & Devices', 'glow-curated'),
                    'description' => __('LED masks, facial tools, and luxury haircare devices worth the investment.', 'glow-curated'),
                    'url' => home_url('/pinterest/#tools'),
                    'cta' => __('Explore tools', 'glow-curated'),
                ),
            );

            foreach ($categories as $card) :
                ?>
                <article class="card category-card" tabindex="0">
                    <div class="icon" aria-hidden="true"><?php echo esc_html($card['icon']); ?></div>
                    <h3><?php echo esc_html($card['title']); ?></h3>
                    <p><?php echo esc_html($card['description']); ?></p>
                    <a href="<?php echo esc_url($card['url']); ?>"><?php echo esc_html($card['cta']); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path fill="#E89B7E" d="M13.172 12L8.222 7.05l1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg>
                    </a>
                </article>
                <?php
            endforeach;
            ?>
        </div>
    </div>
</section>

<section class="why-choose-us" id="why" data-animate>
    <div class="container">
        <h2 class="text-center"><?php esc_html_e('Why Trust Glow Curated?', 'glow-curated'); ?></h2>
        <p class="text-center"><?php esc_html_e("We're not just another beauty blog. We're your trusted guide to luxury beauty investments that actually work.", 'glow-curated'); ?></p>
        <div class="grid">
            <?php
            $benefits = array(
                array('title' => __('Honest Reviews', 'glow-curated'), 'description' => __('Only products we truly believe in.', 'glow-curated')),
                array('title' => __('Expert Curation', 'glow-curated'), 'description' => __('Researched and vetted by enthusiasts.', 'glow-curated')),
                array('title' => __('Investment Focus', 'glow-curated'), 'description' => __('Worth the premium price tag.', 'glow-curated')),
                array('title' => __('Transparent', 'glow-curated'), 'description' => __('Clear about what works and what doesn\'t.', 'glow-curated')),
                array('title' => __('Results-Driven', 'glow-curated'), 'description' => __('Must deliver visible results.', 'glow-curated')),
                array('title' => __('FTC Compliant', 'glow-curated'), 'description' => __('Full disclosure on all affiliate links.', 'glow-curated')),
            );

            foreach ($benefits as $benefit) :
                ?>
                <article class="card benefit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"><path fill="#E89B7E" d="M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.5-1.5z"/></svg>
                    <div>
                        <h3><?php echo esc_html($benefit['title']); ?></h3>
                        <p><?php echo esc_html($benefit['description']); ?></p>
                    </div>
                </article>
                <?php
            endforeach;
            ?>
        </div>
    </div>
</section>

<section class="latest-from-blog" data-animate>
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow"><?php esc_html_e('Glow Journal', 'glow-curated'); ?></p>
            <h2 class="text-center"><?php esc_html_e('Latest on Luxury Sun Care', 'glow-curated'); ?></h2>
            <p class="section-subhead"><?php esc_html_e('Explore in-depth guides crafted to make premium beauty choices effortless.', 'glow-curated'); ?></p>
        </div>
        <?php
        $featured_query = new WP_Query(array(
            'posts_per_page' => 1,
            'ignore_sticky_posts' => true,
        ));

        if ($featured_query->have_posts()) :
            while ($featured_query->have_posts()) :
                $featured_query->the_post();
                $is_new = (time() - get_post_time('U')) <= DAY_IN_SECONDS * 30;
                ?>
                <article class="blog-feature-card">
                    <div class="blog-feature-card__content">
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo esc_html(wp_strip_all_tags(get_the_excerpt())); ?></p>
                        <ul class="blog-feature-card__highlights">
                            <li><?php esc_html_e('Premium product breakdowns curated by the Glow team.', 'glow-curated'); ?></li>
                            <li><?php esc_html_e('Application rituals and ingredient insights tailored to luxury formulas.', 'glow-curated'); ?></li>
                            <li><?php esc_html_e('Shop-the-routine product lists with FTC-compliant affiliate links.', 'glow-curated'); ?></li>
                        </ul>
                        <a class="btn btn-primary" href="<?php the_permalink(); ?>"><?php esc_html_e('Read the full guide', 'glow-curated'); ?></a>
                    </div>
                    <div class="blog-feature-card__meta">
                        <?php if ($is_new) : ?>
                            <span class="tag"><?php esc_html_e('New', 'glow-curated'); ?></span>
                        <?php endif; ?>
                        <dl>
                            <div>
                                <dt><?php esc_html_e('Published', 'glow-curated'); ?></dt>
                                <dd><?php echo esc_html(get_the_date('F Y')); ?></dd>
                            </div>
                            <div>
                                <dt><?php esc_html_e('Reading time', 'glow-curated'); ?></dt>
                                <dd><?php echo esc_html(glow_reading_time()); ?> <?php esc_html_e('minutes', 'glow-curated'); ?></dd>
                            </div>
                        </dl>
                    </div>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p class="text-center"><?php esc_html_e('Stay tuned for our latest luxury beauty features.', 'glow-curated'); ?></p>
            <?php
        endif;
        ?>
    </div>
</section>

<section class="pinterest-cta" data-animate>
    <div class="container">
        <h2><?php esc_html_e('Join Our Pinterest Community', 'glow-curated'); ?></h2>
        <p><?php esc_html_e('Get daily luxury beauty inspiration and honest product recommendations.', 'glow-curated'); ?></p>
        <div class="stats">
            <div><p>📌 <strong><?php esc_html_e('1000+ Curated Pins', 'glow-curated'); ?></strong></p></div>
            <div><p>✨ <strong><?php esc_html_e('20 Expert Boards', 'glow-curated'); ?></strong></p></div>
            <div><p>💎 <strong><?php esc_html_e('Daily Updates', 'glow-curated'); ?></strong></p></div>
        </div>
        <a class="btn-large-cta" href="<?php echo esc_url($pinterest_url); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Follow @glowcurated', 'glow-curated'); ?></a>
    </div>
</section>
<?php
get_footer();
