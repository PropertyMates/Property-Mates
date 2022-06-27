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
?>


<div class="center-area">

    <div class="main-section bg-white public-title for-my-list py-20px">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 inner-section-grey list-view-title">
                    <div class="title-area pb-0">
                        <h3 class="d-flex align-items-center">
                            <span class="pe-2 f-size-30px">
                                <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                                    <?php the_title(); ?>
                                <?php endwhile; ?>
                            </span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-section bg-white public-title for-my-list py-20px">
        <div class="container">
            <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
                <?php comments_template( '', true ); ?>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
