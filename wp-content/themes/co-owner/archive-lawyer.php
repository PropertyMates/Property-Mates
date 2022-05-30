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
<span class="pb-1"> <?php echo __('Lawyers', 'co_owner')?></span>
</h3>
</div>
</div>
</div>
</div>
</div>

<div class="main-section mt-5 pb-5 blog-main-pg">
<div class="container ">

<div class="blog-items-main row">
<?php while ( have_posts() ) : the_post(); 
	
	$google_rating = get_field('google_ratings');
	$total_based_review = get_field('total_based_review');
	$hilighted_points = get_field('hilighted_points');
	$book_consultation_fee = get_field('book_consultation_fee');
	$book_consultation = get_field('book_consultation');
	$website_address = get_field('website_address');
	$lawyer_logo = get_field('logo');
	$google_rating_image = get_field('google_rating_image');
	
	$book_consultation_link = get_field('book_consultation_link');
	$enquiry_on_off= get_field('enquiry_on_off');
				   $inquiry_label= get_field('inquiry_label');
			   $view_button= get_field('view_button');
	      $short_description= get_field('short_description');
?>
<div class="blog-articls lawyr-articls">
<div class="blog-articls-in">
<!--  <time datetime="<?php //the_time( 'Y-m-d' ); ?>" pubdate>
<?php //the_date(); ?> <?php //the_time(); ?>
</time> -->
<div class="blog-articls-img lawyer_img">
<?php 
	if ( has_post_thumbnail() ) { 
		// check if the post has a Post Thumbnail assigned to it.
		//the_post_thumbnail();
	} 
	
?>
<img src="<?php echo $lawyer_logo['url'];?>">
</div>

<h4><a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>

<?php //comments_popup_link(__('Leave a Comment', 'co_owner'), __('1 Comment', 'co_owner'), __('% Comments', 'co_owner')); ?>
<div class="short-des-blog"><?php  echo $short_description; ?></div>

<?php if($enquiry_on_off) :?>
<div class="book-consult">
<span><p><?php echo $inquiry_label; ?></p></span>
<?php if(is_user_logged_in()){ ?>
	<span id="enquirey" lawyer_id="<?php echo get_the_ID(); ?>"><a href="#" data-bs-toggle="modal" >Enquire Now <img class="load-custom"  src="<?php echo get_template_directory_uri(); ?>/images/loading-buffering.gif"></a></span>
	<?php }else { ?>
	<span><a href="<?php echo site_url().'/login'; ?>" >Enquire Now <img  class="load-custom" src="<?php echo get_template_directory_uri(); ?>/images/loading-buffering.gif"></a></span>
	
<?php } ?>
</div>
<?php endif; ?>

<div class="action-btn-row action-btn-row-lwyr">
<!-- <a title="Sutton  Laurence King Lawyers Website" class="btn btn-dark rounded-pill ms-auto" target="_blank" href="https://slklawyers.com.au/" >Website</a> -->
<?php if($website_address): ?>
<a title="<?php the_title(); ?>" class="btn btn-dark rounded-pill ms-auto" target="_blank" href="<?php echo $website_address; ?>" >Website</a>
<?php endif; ?>

<?php if($book_consultation):?>
<a class="btn btn-orange rounded-pill ms-auto" href="/booking-process/?book_id=<?php echo get_the_ID(); ?>" >Book Consultation</a>
<?php endif; ?>
<a class="blog-readmore btn btn-orange rounded-pill ms-auto min-w-120px " href="<?php esc_url( the_permalink() ); ?>" rel="bookmark"><?php echo $view_button; ?></a>
<?php //echo do_shortcode('[accept_stripe_payment name="Payments (powered by Stripe). This is a 60 mins consultation with our law firm. You can discuss anything in this call." price="250" url="http://example.com/downloads/my-script.zip" button_text="Book Consultation"]'); ?>
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
