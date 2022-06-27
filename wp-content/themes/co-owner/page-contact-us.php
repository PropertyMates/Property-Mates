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
    <div class="main-section bg-grey public-title for-my-list py-20px">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 inner-section-grey list-view-title">
                    <div class="title-area pb-0">
                        <h3 class="d-flex align-items-center">
                            <span class="pb-1"> <?php the_title(); ?></span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="main-section bg-white section-image-bg my-listings-main my-conn">
        <div class="container">
            <div class="row">
                <?php if(isset($_GET['submitted']) && $_GET['submitted'] == true): ?>
                    <div class="col-xxl-5 col-xl-6 col-lg-6 col-md-8 col-sm-11 py-40px mx-auto contact-section">
                        <h1 class="text-center pb-3">
                            <?php echo "Thank you for connecting with us !"; ?>
                           
                        </h1>
						<p><?php echo "We'll be in touch soon."; ?></p>
                    </div>
                <?php else: ?>
                    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                        <?php comments_template( '', true ); ?>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener( 'wpcf7mailsent', function( event ) {
        location = "<?php echo home_url(); ?>";
    }, false );
</script>
<?php get_footer(); ?>
