<?php
/**
 * The Template for displaying all single posts
 *
 * Please see /external/bootstrap-utilities.php for info on CoOwner::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Co-Owner
 * @autor 		TechXperts
 */
get_header();

?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div class="center-area">

		<div class="main-section bg-white public-title border-bottom py-20px">
		<div class="container">
			<div class="row">
								<div class="col-sm-12 inner-section-grey list-view-title">
					<div class="title-area">
						<h3 class="d-flex align-items-center"><span><?php the_title(); ?></span></h3>
					</div>
				</div>
			</div>
		</div>
	</div>

	 <div class="main-section mt-5 blog-detal-pg">
        <div class="container ">

<div class="row">
	<div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-12">
        	<div class="card custom-card blog-detil-info mb-5">
        		<div class="card-body">
        	 <div class="blog-detil-img">
				<?php 
					if ( has_post_thumbnail() ) { 
					// check if the post has a Post Thumbnail assigned to it.
						the_post_thumbnail();
					}				
				?>
            </div>
            <h3>
                    <a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title(); ?>" rel="bookmark">
                        <?php the_title(); ?>
                    </a>
                </h3>
		 
		<time datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate>
			<?php the_date(); ?> <?php the_time(); ?>
		</time>

		 
		<?php the_content(); ?>

		<?php if ( get_the_author_meta( 'description' ) ) : ?>
			<?php echo get_avatar( get_the_author_meta( 'user_email' ) ); ?>
			<h3>
				<?php echo __('About', 'co_owner'); ?> <?php echo get_the_author() ; ?>
			</h3>
			<?php the_author_meta( 'description' ); ?>
		<?php endif; ?>

		
 </div> </div>
</div>
<div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12">
	<div class="card custom-card public-view-card">
        		<div class="card-body">
<?php  $related = ci_get_related_posts( get_the_ID(), 3 );

if ( $related->have_posts() ):
	?>
	<div class="related-posts">
		<h3>Related posts</h3>
		<ul>
			<?php while ( $related->have_posts() ): $related->the_post(); ?>
				<li>
					 <div class="blog-related-img">
					 	<a href="<?php esc_url( the_permalink() ); ?>">
				<?php 
					if ( has_post_thumbnail() ) { 
					// check if the post has a Post Thumbnail assigned to it.
						the_post_thumbnail();
					}				
				?>
			</a>
            </div>
             <div class="blog-related-tex">
					<h5><?php the_title(); ?></h5>
					
				</div>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
	<?php
endif;
wp_reset_postdata();

?></div></div>
</div>
	</div></div>

<div class="comment-add">
			<div class="container">
				 
				<?php comments_template( '', true ); ?>
</div></div> 


</div>
</div>
	

<?php endwhile; ?>

<?php get_footer() ?>
