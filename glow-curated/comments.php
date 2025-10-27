<?php
/**
 * Comments template.
 *
 * @package Glow_Curated
 */

if ( post_password_required() ) {
    return;
}
?>
<section id="comments" class="comments-area" data-animate>
    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $count = get_comments_number();
            printf( _nx( 'One comment', '%1$s comments', $count, 'comments title', 'glow-curated' ), number_format_i18n( $count ) );
            ?>
        </h2>
        <ol class="comment-list">
            <?php
            wp_list_comments(
                array(
                    'style'      => 'ol',
                    'short_ping' => true,
                    'avatar_size'=> 60,
                )
            );
            ?>
        </ol>
        <?php the_comments_navigation(); ?>
    <?php endif; ?>

    <?php if ( comments_open() ) : ?>
        <div class="comment-form-wrapper">
            <?php comment_form(); ?>
        </div>
    <?php endif; ?>
</section>
