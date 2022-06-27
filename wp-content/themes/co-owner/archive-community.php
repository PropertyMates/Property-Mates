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
<span class="pb-1"> <?php echo __('Our community', 'co_owner')?></span>
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
	<span class="enquirey" lawyer_id="<?php echo get_the_ID(); ?>">
	<a href="#" data-bs-toggle="modal" class="inq_cl" >Enquire Now 
	<img class="load-custom"  src="<?php echo get_template_directory_uri(); ?>/images/loading-buffering.gif"></a></span>
</div>
<?php endif; ?>

<div class="action-btn-row action-btn-row-lwyr">
<!-- <a title="Sutton  Laurence King Lawyers Website" class="btn btn-dark rounded-pill ms-auto" target="_blank" href="https://slklawyers.com.au/" >Website</a> -->


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


	<div class="modal enquire-pp fade default-modal-custom" id="enquire-popup-form" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            	<div class="modal-header">
            		<h4>Enquiry Now</h4>
            		      <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            	</div>
                <div class="modal-body">
				<form class="frm-custom" method="post" action="" id="co-owner-user-enquire" novalidate="novalidate">
				  <input type="hidden" name="lawyer_id_extra" id="lawyer_id_extra">
                    <div class="row">
                        <div class="col col-sm-6 col-12 mb-3">
                        	<label>First Name <span style="color:#f00">*</span></label>
                           <input name="first_name" type="text" maxlength="20" class="form-control" id="firstname" placeholder="First Name">
                             <div class="require-fielderor formfname">This is a required field</div>
                         </div>
						 <div class="col col-sm-6 col-12 mb-3">
						 	<label>Last Name <span style="color:#f00">*</span></label>
                                    <input name="last_name" type="text" maxlength="20" class="form-control" id="lastname" placeholder="Last Name">
                                    <div class="invalid-feedback lastname">This is a required field</div>
                                </div>
								<div class="col col-sm-12 col-12 mb-3">
									<label>Email Id <span style="color:#f00">*</span></label>
                                    <div class="verify-email-sec">
                                        <input name="email" id="user-email" type="text" maxlength="50" class="form-control" placeholder="Email id" aria-describedby="button-addon2">
                                        
                                       
                                    </div>
                                    <div class="require-fielderor formemail">Email is not valid</div>
                                </div>
								<div class="col col-sm-12 col-12">
                              <button id="user_form-enquire" class="btn btn-orange btn-rounded w-180px" type="button">Submit Enquiry</button>
                                </div>
							
					</div>
					</form>	
				</div>
			</div>
		</div>
	</div>


    <div class="modal enquire-pp fade default-modal-custom" id="enquire-popup" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-12 d-flex">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
                        <div class="col-12 enquiry-data">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/tick.jpg">
                            <h3><?php echo "Thank you for showing your interest."; ?></h3>
                            <p>We have received your message. <br> Our team will get in touch with you soon.</p>
                            <br>
                            <p>Please check your email for future updates.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
function ValidateEmail(email) 
{
    var re = /\S+@\S+\.\S+/;
        return re.test(email);
}


</script>
	
<?php if(is_user_logged_in()){
$cu = wp_get_current_user(); ?>

<script> jQuery('.enquirey').on('click' , function(){ 
	jQuery('.load-custom',this).show();
	
	
	
	var lawyerId = jQuery(this).attr('lawyer_id');
	var formData = {name:"<?php echo $cu->user_firstname; ?>", email:"<?php echo $cu->user_email; ?>",action :"my_action_name",lawyer_id:lawyerId }; //Array 
	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
	
	jQuery.ajax({
		url : ajaxurl,
		type: "POST",
		data : formData,
		dataType : 'json',
		success: function(data, textStatus, jqXHR)
		{
		jQuery('.load-custom').hide();
		jQuery('#enquire-popup').modal('show');
			//data - response from server
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			
		}
	});
});
</script>
<?php  }else{ 	?>
<script> jQuery('.enquirey').on('click' , function(){ 
    //jQuery('.load-custom',this).show();
    var lawyerId = jQuery(this).attr('lawyer_id');
	jQuery('#lawyer_id_extra').val(lawyerId);
	jQuery('#enquire-popup-form').modal('show');	
});
</script>
<script> jQuery('#user_form-enquire').on('click' , function(){ 
	
	var lawyerId = jQuery('#lawyer_id_extra').val();
	var user_firstname = jQuery('#firstname').val();
	var user_lastname = jQuery('#lastname').val();
	var user_email = jQuery('#user-email').val();
	if(user_firstname == ""){
	jQuery('.require-fielderor.formfname').addClass('require-error-show');
	return false;
	} else if (!ValidateEmail(user_email)){
	
	jQuery('.require-fielderor.formemail').addClass('require-error-show');
	return false;
	}else if (user_lastname == "" ){
	jQuery('.require-fielderor.lastname').addClass('require-error-show');
	}
	
    jQuery('.load-custom',this).show();
	jQuery('#enquire-popup-form').modal('hide');
	
	var formData = {name:user_firstname, email:user_email,action :"my_action_name",lawyer_id:lawyerId }; //Array 
	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
	
	jQuery.ajax({
		url : ajaxurl,
		type: "POST",
		data : formData,
		dataType : 'json',
		success: function(data, textStatus, jqXHR)
		{
		jQuery('.load-custom').hide();
		jQuery('#enquire-popup').modal('show');
			//data - response from server
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			
		}
	});
});
</script>


<?php } get_footer(); ?>
