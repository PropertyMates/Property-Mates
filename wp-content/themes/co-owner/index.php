<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file
 *
 * Please see /external/bootstrap-utilities.php for info on Starkers_Utilities::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Co-Owner
 * @autor 		TechXperts
 */
get_header();
?>
<div class="center-area">
    <?php if ( have_posts() ): ?>
        <div class="main-section bg-white public-title for-my-list py-20px">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 inner-section-grey list-view-title">
                    <div class="title-area pb-0">
                        <h3 class="d-flex align-items-center">
                            <span class="pb-1"> <?php echo __('Latest Blogs', 'co_owner')?></span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="main-section mt-5 pb-5 blog-main-pg">
        <div class="container ">

        <div class="blog-items-main row">
            <?php while ( have_posts() ) : the_post(); ?>
            <div class="blog-articls">
                 <div class="blog-articls-in">
               <!--  <time datetime="<?php //the_time( 'Y-m-d' ); ?>" pubdate>
                    <?php //the_date(); ?> <?php //the_time(); ?>
                </time> -->
				  <div class="blog-articls-img">
				<?php 
					if ( has_post_thumbnail() ) { 
					// check if the post has a Post Thumbnail assigned to it.
						the_post_thumbnail();
					} 
				
				?>
            </div>
                 <div class="blog-articls-text">
                <h4>
                    <a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title(); ?>" rel="bookmark">
                        <?php the_title(); ?>
                    </a>
                </h4>
				
                <?php //comments_popup_link(__('Leave a Comment', 'co_owner'), __('1 Comment', 'co_owner'), __('% Comments', 'co_owner')); ?>
                <div class="short-des-blog"><?php the_excerpt(); ?></div>

                <a class="blog-readmore btn btn-orange rounded-pill ms-auto min-w-120px " href="<?php esc_url( the_permalink() ); ?>" rel="bookmark">View Blog</a>
				
		</div> 
            </div></div> 
            <?php endwhile; ?>
        </div>

   <div class="navigation">
        <div class="next-posts"><?php next_posts_link('&laquo; Older Posts') ?></div>
        <div class="prev-posts"><?php previous_posts_link('Newer Posts &raquo;') ?></div>
    </div>		
</div></div> 		
    <?php else: ?>
	
	
	
        <h1>
            <?php echo __('Nothing to show yet.', 'co_owner')?>
        </h1>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
