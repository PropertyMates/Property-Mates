<?php

/**
 * Archive Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
$user = wp_get_current_user();
$user_status = $user->exists() ? get_user_status($user->ID) : 0;
?>

<div id="bbpress-forums" class="bbpress-wrapper">

	<?php if ( bbp_allow_search() ) : ?>

		<div class="bbp-search-form">

			<?php bbp_get_template_part( 'form', 'search' ); ?>

		</div>
        <div class="bbp-user-post">
            <?php if($user->exists() && $user_status == 1) : ?>
                <a href="#" class="btn btn-orange has-pencil rounded-pill" data-bs-toggle="modal" data-bs-target="#topic-create-modal">New Post</a>
            <?php elseif($user_status == 2) : ?>
                <p>(Your account is deactivated.)</p>
            <?php else: ?>
                <p>(Please log in to post or reply.)</p>
            <?php endif; ?>
        </div>

	<?php endif; ?>

	<?php bbp_breadcrumb(); ?>

	<?php do_action( 'bbp_template_before_topic_tag_description' ); ?>

	<?php if ( bbp_is_topic_tag() ) : ?>

		<?php bbp_topic_tag_description( array( 'before' => '<div class="bbp-template-notice info"><ul><li>', 'after' => '</li></ul></div>' ) ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_topic_tag_description' ); ?>

	<?php do_action( 'bbp_template_before_topics_index' ); ?>

	<?php if ( bbp_has_topics() ) : ?>

		<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

		<?php bbp_get_template_part( 'loop',       'topics'    ); ?>

		<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

	<?php else : ?>

		<?php bbp_get_template_part( 'feedback',   'no-topics' ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_topics_index' ); ?>

</div>
<?php if($user->exists() && $user_status == 1) : ?>
<div class="modal fade default-modal-custom" id="topic-create-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-12 d-flex">
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <?php echo do_shortcode('[bbp-topic-form]');?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
