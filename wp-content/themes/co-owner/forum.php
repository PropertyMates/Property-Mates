<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Please see /external/bootsrap-utilities.php for info on CoOwner::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Co-Owner
 * @autor 		TechXperts
 */
get_header();
$user = wp_get_current_user();
$user_status = $user->exists() ? get_user_status($user->ID) : 0;
?>


<div class="center-area">
    <div class="main-section bg-white public-title for-my-list py-20px">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 inner-section-grey list-view-title">
                    <div class="title-area pb-0">
                        <h3 class="d-flex align-items-center">
                            <span class="pe-2 f-size-30px">
                                <?php the_title(); ?>
                            </span>
                            <div class="ms-auto d-flex align-items-center float-end">
                                <?php if($user->exists() && $user_status == 1) : ?>
                                    <a href="#" class="btn btn-orange rounded-pill has-pencil d-sm-none" data-bs-toggle="modal" data-bs-target="#topic-create-modal">New Post</a>
                                <?php elseif($user_status == 2) : ?>
<!--                                    <p class="d-sm-none">(Your account is deactivated.)</p>-->
                                <?php else: ?>
<!--                                    <p class="d-sm-none">(Please sign in to replay or post anything.)</p>-->
                                <?php endif; ?>
                            </div>
                        </h3>
                        <p>Welcome to the Forum where you're free to ask any property-related questions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-section mt-5 pb-5">
        <div class="container forum-index">
            <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
                <?php comments_template( '', true ); ?>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
