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
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
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
<?php 
	
	$google_rating = get_field('google_ratings');
	$total_based_review = get_field('total_based_review');
	$hilighted_points = get_field('hilighted_points');
	$book_consultation_fee = get_field('book_consultation_fee');
	$book_consultation = get_field('book_consultation');
	$website_address = get_field('website_address');
	$lawyer_logo = get_field('logo');
	$google_rating_image = get_field('google_rating_image');
				   $inquiry_label= get_field('inquiry_label');
			   $view_button= get_field('view_button');	
?>
<div class="commnunity-inner">
	<div class="hf-comunity">
<div class="left-comunity">
<?php if($lawyer_logo):?>
<div class="logo-comminuty"><img src="<?php echo $lawyer_logo['url'];?>"></div>
<?php endif; ?>
</div>
<div class="right-comunity">
<?php if($hilighted_points): ?>
<?php echo $hilighted_points; ?>
<?php endif; ?>
</div>
</div>
<?php the_content(); ?>
<?php if($google_rating_image):?>
<div class="g-review">
<img src="<?php echo $google_rating_image['url'];?>">
</div>
<?php endif;?>
<?php if($book_consultation_fee):?>
<div class="book-consult">
<span><p>Book Consultation</p></span>
<span class="consult-price"><?php echo $book_consultation_fee; ?></span>
</div>
<?php endif; ?>
<div class="book-consult">
<span><p><?php echo $inquiry_label; ?></p></span>
<?php if(is_user_logged_in()){ ?>
	<span id="enquirey"><a href="#" data-bs-toggle="modal" >Enquire Now <img class="load-custom"  src="<?php echo get_template_directory_uri(); ?>/images/loading-buffering.gif"></a></span>
	<?php }else { ?>
	<span><a href="<?php echo site_url().'/login'; ?>" >Enquire Now <img  class="load-custom" src="<?php echo get_template_directory_uri(); ?>/images/loading-buffering.gif"></a></span>
	
<?php } ?>
</div>
<div class="action-btn-row">
<!-- <a title="Sutton  Laurence King Lawyers Website" class="btn btn-dark rounded-pill ms-auto" target="_blank" href="https://slklawyers.com.au/" >Website</a> -->
<?php if($website_address): ?>
<a title="<?php the_title(); ?>" class="btn btn-dark rounded-pill ms-auto" target="_blank" href="<?php echo $website_address; ?>" >Website</a>
<?php endif; ?>
<?php if($book_consultation):?>
<a class="btn btn-orange rounded-pill ms-auto" href="/booking-process/?book_id=<?php echo get_the_ID(); ?>" >Book Consultation</a>
<?php endif; ?>
<?php //echo do_shortcode('[accept_stripe_payment name="Payments (powered by Stripe). This is a 60 mins consultation with our law firm. You can discuss anything in this call." price="250" url="http://example.com/downloads/my-script.zip" button_text="Book Consultation"]'); ?>
</div>
</div>
<?php if ( get_the_author_meta( 'description' ) ) : ?>
<?php echo get_avatar( get_the_author_meta( 'user_email' ) ); ?>
<h3>
<?php echo __('About', 'co_owner'); ?> <?php echo get_the_author() ; ?>
</h3>
<?php the_author_meta( 'description' ); ?>
<?php endif; ?>
</div> </div>
</div>
</div></div>
</div>
</div>
<?php endwhile; ?>
<?php get_footer() ?>
