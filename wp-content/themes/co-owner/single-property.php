<?php
	
	get_header();
	
	global $post;
	$user_id = get_current_user_id();
	$current_user = get_person_detail_by_id($user_id);
	$property = get_property_detail_by_id($post->ID);
	
	
	if($property && get_user_meta($property->post_author,'_user_status',true) != 1 && $user_id != $property->post_author){
		$property = null;
	}
	
	
	
	
	$is_my_property = ($property && ($property->post_author == $user_id));
	$user_status = get_user_status($user_id);
	//$is_matching = $property ? check_is_maching_property($user_id,$property->ID) : false;
	$show_connection_button = true;
	if($property){
		$connection_link = ($user_id > 0) ? ( $user_status != 1 ? home_url(CO_OWNER_MY_ACCOUNT_PAGE."/?alert=your_account_is_inactive") : '#') : home_url('login?redirect_to='.base64_encode(home_url(CO_OWNER_PROPERTY_DETAILS_PAGE."?id=$property->ID")));
		if($connection_link == '#'){
			$is_requested = CoOwner_Connections::check_user_has_already_requested_in_property($property->post_author,$user_id,$property->ID,false);
			if($is_requested){
				$url = home_url(CO_OWNER_MESSAGE_PAGE);
				if($is_requested->status != 1){
					$url .= "?request={$is_requested->id}&is_received=".($user_id == $is_requested->sender_user ? 'false' : 'true');
					} else {
					$url .= "?is_pool=false&with={$property->post_author}";
				}
				$connection_link = $url;
				$show_connection_button = false;
			}
		}
	}
	
	$cropedImages = get_post_meta($post->ID, '_pl_images_cropped', true);
	//pr($cropedImages);
	
	$is_liked = get_property_is_liked($property->ID);
?>

<div class="modal fade property-modal-custom" id="property-images-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-xl">
<div class="modal-content">
<div class="modal-body">
<div class="row">
<div class="col-sm-12 col-12 d-flex">
<button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="col-sm-12 col-12">
<div class="custom-flx-slider">
<div id="slider" class="flexslider">
<ul class="slides">
<?php foreach ($property->images as $key => $image): ?>
<li>
<img src="<?php echo $image['url']; ?>" alt="">
</li>
<?php endforeach; ?>
<!-- items mirrored twice, total of 12 -->
</ul>
</div>
<div id="carousel" class="flexslider">
<ul class="slides">
<?php foreach ($property->images as $key => $image): ?>
<li>
<img src="<?php echo $image['url']; ?>" alt="">
</li>
<?php endforeach; ?>
<!-- items mirrored twice, total of 12 -->
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<?php if($property && $user_id): ?>
<?php if($user_status == 1 && $property->post_author == $user_id && $property->enable_pool && $property->available_share > 0): ?>
<?php
	$connected_users = get_connected_connections();
	$people_requested = get_people_requested_for_the_same_pool($property->ID, $user_id);
	
include 'parts/modals/my-connection.php'; ?>

<div class="modal fade default-modal-custom" id="add-member-to-pool" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-body">
<div class="row">

<div class="col-sm-12 col-12 d-flex">
<button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="col-sm-12 col-12 pb-4">
<h6>Add New Member</h6>
</div>

<div class="col-sm-12 col-12 pb-3">
<h6 class="pt-2 bb-1 pb-3">
Pool: <?php echo $property->post_title; ?>
<span class="coman-orange-sub d-block pt-1">
<?php echo 'Pool Member(s): '.count($property->members); ?> |
<?php if($property->available_price > 0): ?>
Available Portion: <?php echo $property->available_share."% at";   ?>
<?php echo CO_OWNER_CURRENCY_SYMBOL." ".number_format( (float) $property->available_price); ?>
<?php else: ?>
Portions of the property are not available
<?php endif; ?>
</span>
</h6>
</div>

<form action="" id="add-new-member-form">
<input type="hidden" name="property_id" value="<?php echo $property->ID; ?>">
<div class="col-12">
<div class="row property-share-inputs">
<div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-3">
<label for="property-share-options" class="form-label">I am interested in %</label>
<div class="w-100 custom-select">
<select
data-calculated-input="#member-calculated-price"
data-property-available-share="<?php echo $property->available_share; ?>"
data-property-available-price="<?php echo $property->available_price; ?>"
class="form-select single-select2 property-share-selection"
name="interested_in"
>
<?php echo get_property_share_options_by_id($property->ID); ?>
</select>
</div>
<label id="interested_in-error" class="text-error" for="interested_in"></label>
<label id="property-share-options-error" class="text-error" for="property-share-options" style=""></label>
</div>
<div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-3">
<label for="price" class="form-label">Calculated Price</label>
<input name="calculated_price" type="text" class="form-control" readonly id="member-calculated-price">
</div>
</div>
</div>
<div class="col-sm-12 col-12 mb-4">
<label for="add-comment" class="form-label">Welcome message for user (visible in the pool chat)</label>
<textarea name="description" class="form-control" id="add-comment" rows="3" placeholder="Comment"></textarea>
</div>
<div class="col-sm-12 col-12 mb-3 text-end bottom-btns">
<a href="#" class="btn btn-orange-text rounded-pill" data-bs-dismiss="modal">Cancel</a>
<button type="submit" class="btn btn-orange rounded-pill">Submit</button>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
<?php endif; ?>

<?php if($user_status == 1 &&$property->post_author != $user_id && !$property->is_already_member): ?>
<div class="modal fade default-modal-custom" id="property-connection-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-body">
<div class="row">

<div class="col-sm-12 col-12 d-flex">
<button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="col-sm-12 col-12 pb-4">
<h6>Connect with member</h6>
</div>

<div class="col-sm-12 col-12 pb-3">
<h5 class="double-bb-title">
<span>Buyer</span>
</h5>
<h6 class="pt-2 bb-1 pb-3">
<?php echo $property->enable_pool ? 'Pool: ' : ''; echo $property->address; ?>
<span class="coman-orange-sub d-block pt-1">
<?php echo $property->enable_pool ? 'Pool Member(s): '.count($property->members).' |' : ''; ?>
<?php if($property->available_share > 0): ?>
Available Portion: <?php echo $property->available_share."% at";   ?>
<?php echo CO_OWNER_CURRENCY_SYMBOL." ".number_format( (float) $property->available_price); ?>
<?php else: ?>
Portions of the property are not available
<?php endif; ?>
</span>
</h6>
</div>

<?php if($property->available_share > 0): ?>
<form action=""
id="person-connection-form"
data-id="<?php echo $property->ID ?>"
data-available-share="<?php echo $property->available_share; ?>"
data-available-price="<?php echo $property->available_price; ?>"
>
<div class="col-12">
<div class="row property-share-inputs <?php echo $property->enable_pool ? '' : 'd-none'; ?>">
<div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-3">
<label for="property-share-options" class="form-label">I am interested in %</label>
<div class="w-100 custom-select">
<select id="property-share-options"
data-calculated-input="[name='calculated_price']"
data-property-available-share="<?php echo $property->available_share; ?>"
data-property-available-price="<?php echo $property->available_price; ?>"
class="form-select single-select2" name="interested_in">
<?php echo get_property_share_options_by_id($property->ID,$property->available_share); ?>
</select>
</div>
<label id="property-share-options-error" class="text-error" for="property-share-options" style=""></label>
</div>
<div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-3">
<label for="price" class="form-label">Calculated Price</label>
<input name="calculated_price" type="text" class="form-control" readonly id="price">
</div>
</div>
</div>
<div class="col-sm-12 col-12 mb-4">
<label for="add-comment" class="form-label">Add Comment</label>
<textarea name="description" class="form-control" id="add-comment" rows="3" placeholder="Comment"></textarea>
</div>
<div class="col-sm-12 col-12 mb-3 text-end bottom-btns">
<a href="#" class="btn btn-orange-text rounded-pill" data-bs-dismiss="modal">Cancel</a>
<button type="submit" class="btn btn-orange rounded-pill">Send Request</button>
</div>
</form>
<?php endif; ?>
</div>
</div>
</div>
</div>
</div>
<?php endif; ?>
<?php endif; ?>


<div class="center-area">
<?php if($property): ?>




<div class="proerty-singl-pg">
<div class="container">
	<div class="back-page-button">
		<a class="bck-pgbtn" href="<?php echo get_site_url(); ?>/property-search/"> <img src="<?php echo get_template_directory_uri(); ?>/images/bk-pgarrow.png"> Back to properties</a>
	</div>
	<div class="propert-singl-nameaddress mobile">
		<h2><?php echo $property->post_title; ?></h2>
	</div>

 <?php if($property->images && count($property->images) > 0): ?>
		<div class="proerty-singl-pics proerty-singl-picsmobile hidden">
		
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-12 col-12 pb-3 mb-1">
						<div class="big-thumb-property">
							<img src="<?php echo $property->images[0]['url']; ?>" alt="">
						</div>
					</div>

					<div class="col-xl-6 col-lg-6 col-md-12 col-12 property-show">
						<div class="row">
							<?php
								//pr($cropedImages);
							?>
							<?php /*foreach ($cropedImages as $key => $image):  */
								foreach ($property->images as $key => $image): 
							?>

							<?php if($key != 0 && $key < 5): ?>
							<?php $filename_from_url = parse_url($image['url']);
								$ext = pathinfo($filename_from_url['path'], PATHINFO_EXTENSION); 
								//if($ext){
							?>
					<div class="col-sm-6 col-6 pb-3 mb-1">
						<div class="medium-thumb-property">
							<img src="<?php echo $image['url']; ?>" alt="">
						</div>
					</div>
				<?php //} ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
<a href="#" class="btn btn-white rounded-pill show_all_pics"  data-bs-toggle="modal" data-bs-target="#property-images-modal">show all Photos</a>

</div>

</div>
 
</div>
<?php endif; ?>	
	<div class="row">
		<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12  property-details-left">
 
 <?php if($property->images && count($property->images) > 0): ?>
		<div class="proerty-singl-pics">
		
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-12 col-12 pb-3 mb-1">
						<div class="big-thumb-property">
							<img src="<?php echo $property->images[0]['url']; ?>" alt="">
						</div>
					</div>

					<div class="col-xl-6 col-lg-6 col-md-12 col-12 property-show">
						<div class="row">
							<?php
								//pr($cropedImages);
							?>
							<?php /*foreach ($cropedImages as $key => $image):  */
								foreach ($property->images as $key => $image): 
							?>

							<?php if($key != 0 && $key < 5): ?>
							<?php $filename_from_url = parse_url($image['url']);
								$ext = pathinfo($filename_from_url['path'], PATHINFO_EXTENSION); 
								//if($ext){
							?>
					<div class="col-sm-6 col-6 pb-3 mb-1">
						<div class="medium-thumb-property">
							<img src="<?php echo $image['url']; ?>" alt="">
						</div>
					</div>
				<?php //} ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
<a href="#" class="btn btn-white rounded-pill show_all_pics"  data-bs-toggle="modal" data-bs-target="#property-images-modal">show all Photos</a>

</div>

</div>
 
</div>
<?php endif; ?>

<div class="propert-singl-nameaddress">
	<div class="ps-nameaddress-left">
<h2><?php echo $property->post_title; ?></h2>
<p><img src="<?php echo get_template_directory_uri(); ?>/images/properrt-address.svg"><?php echo $property->address; ?></p>
</div>
<div class="ps-nameaddress-right">
	 <div class="share_widget">
 <a title="Share" href="#"  data-id="<?php echo $property->ID; ?>" class="btn ms-auto share_box_pop"><span>Share</span></a>
 
   <div class="share_widget_box" style="display:none;">
       <h3>Share Listing</h3>
       <ul class="share_items">
			<li class="share_item icon_email"><a href="mailto:?Subject=<?php echo get_the_title();?>&amp;body=<?php echo get_the_permalink();?>" id="send_by_email">Send by Email</a></li>
			<li class="share_item icon_copy_link"><a href="<?php echo get_the_permalink();?>" id="copy_link">Copy Link</a></li>
			<li class="share_item icon_fb"><a href="" id="share_fb">Share to Facebook</a></li>
			<!-- <li class="share_item icon_wapp"><a href="" id="share_wapp">Share to Whatsapp</a></li> -->
	   </ul>
   </div>
  </div>

 <a  title="Favorite/Shortlist" href="#" data-id="<?php echo $property->ID; ?>" data-type='property' class="btn btn-favourite btn-favourite-new ms-auto <?php echo $is_liked ? 'active make-property-dislike-pop' : 'make-property-like' ?>"> </a>
 </div>
 </div>

 
<!-- black bar section start -->

<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="Summary-tab" data-bs-toggle="tab" data-bs-target="#Summary" type="button" role="tab" aria-controls="Summary" aria-selected="true">Summary</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="Details-tab" data-bs-toggle="tab" data-bs-target="#Details" type="button" role="tab" aria-controls="Details" aria-selected="false">Details</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="Members-tab" data-bs-toggle="tab" data-bs-target="#Members" type="button" role="tab" aria-controls="Members" aria-selected="false">Members</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="Location-tab" data-bs-toggle="tab" data-bs-target="#Location" type="button" role="tab" aria-controls="Location" aria-selected="false">Location</button> 
	</li>
</ul>

<div class="tab-content" id="myTabContent">
	<div class="tab-pane fade active show" id="Summary" role="tabpanel" aria-labelledby="Summary-tab">

		<div class="facility-properrt">
		   <?php if($property->property_category == 'residential'): ?>
		       <a title="Bedroom">
		            <img src="<?php echo get_template_directory_uri(); ?>/images/bedroom-ico.svg">
		            <span><?php echo $property->bedroom; ?></span>
		        </a>
		        
		        <a title="Bathroom">
		            <img src="<?php echo get_template_directory_uri(); ?>/images/bathroom-ico.svg">
		            <span><?php echo $property->bathroom; ?></span>
		        </a>
		        
		        <a title="Parking">
		            <img src="<?php echo get_template_directory_uri(); ?>/images/vhicle-ico.svg">
		            <span><?php echo $property->parking; ?></span>
		        </a>
				 
		    <?php endif; ?>
		</div>
<div class="property-detail-description">
<?php $description = apply_filters('the_content',$property->post_content); ?>
<?php if(!empty($description)): ?>
	<div class="description-box">
		<div class="description-small" style="height: 3em;"><?php echo $description; ?>
			<div class="blur-line" id="blur-line"></div>
		</div>
	<div class="description-full" style="display: none;"><?php echo $description; ?></div>
	</div>
<a href="#" class="btn view-full-description btn-orange-bordered rounded-pill btn-sm <?php echo strlen($description) > 80 ?: 'd-none';?>">Read more</a>
<?php else: ?>
-
<?php endif; ?>		
</div>
	</div>




<div class="tab-pane fade" id="Details" role="tabpanel" aria-labelledby="Details-tab">
<div class="row propertytypes">		
<div class="col-sm-12 col-md-6 col-6">
	<h4>Property Type</h4>
	<p><?php echo ucfirst($property->property_category).' - '.ucfirst($property->property_type); ?></p>
	<h4>Building Area</h4>
	<p><?php echo ucfirst($property->building_area) ?? ''; ?> </p>	
	<h4>Land Area</h4>
	<p><?php echo ucfirst($property->land_area) ?? ''; ?> </p>
	<h4>Age/year built</h4>
	<p><?php echo ucfirst($property->age_year_built) ?? ''; ?> </p>

</div>
<div class="col-sm-12 col-md-6 col-6">
<?php if(is_array($property->property_features) && count($property->property_features) > 0): ?>
 <h4>Property Features</h4>
<?php
	$array1 = (is_array($property->property_features) && count($property->property_features) > 0) ? $property->property_features : array();
	$array2 = (is_array($property->manually_features) && count($property->manually_features) > 0) ? $property->manually_features : array();
foreach (array_merge($array1,$array2)  as $features): ?>
<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
<p><?php echo $features; ?></p>
</div>
<?php endforeach; ?>
 
<?php endif; ?>	
</div>
</div>
</div>




<div class="tab-pane fade" id="Members" role="tabpanel" aria-labelledby="Members-tab">
		

<div class="row"> 
<?php foreach($property->members as $key => $member): ?>
<div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-4 mb-4 member-box">
	<div class="card member-card <?php echo $member->is_admin ? 'green' : ( ($key % 2) ? 'red' : 'yellow'); ?>">
		<div class="mbr-detail-area">
			<div class="<?php echo get_user_shield_status($member->id) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''?> ">
				<a href="<?php echo home_url('/'.CO_OWNER_PERSON_DETAILS_PAGE).'?id='.$member->id; ?>">
				<div class="mbr-thumb mx-auto">
					<img src="<?php  echo esc_url( get_avatar_url($member->id));  ?>" alt="">
				</div>
			</a>
			</div>
				<a class="membername-crd" href="<?php echo home_url('/'.CO_OWNER_PERSON_DETAILS_PAGE).'?id='.$member->id; ?>">
				<h4><?php echo $member->display_name; ?></h4>
				<div class="shield-star"><img src="<?php echo get_template_directory_uri(); ?>/images/codicon_verified-filled.svg"></div> 				
			</a>
			<div class="property-own text-center">
				<span >
				<?php if($member->is_admin && $property->interested_in_selling == 'full_property'): ?>
					<?php echo $property->posted_by; ?> 
						<?php elseif($member->is_admin && $property->interested_in_selling == 'portion_of_it'): ?>
						<?php echo $property->posted_by; ?>  <?php echo get_admin_hold_pr($property->ID); ?>%
					<?php else: ?>
	 				<?php echo $member->interested_in; ?>% 
				<?php endif; ?>
				</span>
			</div>
		</div>
 
	</div>
</div>
<?php endforeach; ?>
<div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-4 mb-4 member-box addmember-box">
	<?php if($user_status == 1 && $property->post_author == $user_id && $property->enable_pool && $property->available_share > 0 && $property->post_status == 'publish'): ?>
<a href="#" class="btn btn-orange btn-orange-gradiant addmember" data-bs-toggle="modal" data-bs-target="#my-members-modal">Add Member</a>
<?php endif; ?>

</div>
</div>
	</div>

	<div class="tab-pane fade" id="Location" role="tabpanel" aria-labelledby="Location-tab">
		<div class="public-view-map" id="property-map-view" data-id="<?php echo $property->ID; ?>" data-address="<?php echo get_property_full_address($property->ID,true); ?>" style="height: 450px;width: 100%;"></div>
	</div>
</div>

</div>
 

<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 property-details-right">
<div class="sidebar-property-details">
<div class="sidebar-property-in">
	<div class="box-sellprice box-sellprice-totla box-shadow-new">
		<div class="price-boxsel">
			<h2><?php echo CO_OWNER_CURRENCY_SYMBOL; ?> <?php echo number_format($property->property_original_price); ?> <small>total</small></h2>
		</div>
		<div class="price-boxseinfo">
		<a href="<?php echo WWW_DOMAIN_COM_AU_PROPERTY_PROFILE; ?>" target="_blank" class="fs-6">Get real market value</a>
		<?php if($property->interested_in_selling == 'portion_of_it'): ?>
		<h4 class="pt-3 mt-2">I want to sell % </h4>
		<h3><?php echo $property->i_want_to_sell; ?> %</h3>

		<h4 class="pt-3">Selling Price</h4>
		<h3><?php echo CO_OWNER_CURRENCY_SYMBOL; ?> <?php echo number_format($property->calculated); ?></h3>

		<?php endif; ?>

		<?php if($property->enable_pool) :
			$property_members = array_filter($property->members,function($user) {
				if(!$user->is_admin){
					return $user;
				}
			});
		?>
		<?php if(count($property_members) > 0): ?>
		<h3 class="pt-3">Pool Information</h3>
		<div class="pl-list">
		<div>
		<?php foreach ($property_members as $p_member): if($p_member->is_admin == 0): ?>
		<h4><?php echo $p_member->display_name; ?> holds <span></span><?php echo $p_member->interested_in; ?>%</h4>
		<?php endif; endforeach; ?>
		</div>
		</div>
		<?php endif; ?>
		 
		<?php if($property->available_share == 0): ?>
		<span class="badge bg-danger pool-is-full" style="font-size: 10px;">POOL IS FULL</span>
		<?php endif; ?>

		<?php if($property->available_share > 0): ?>
		<h4>Available share <span></span> <?php echo $property->available_share; ?> %</h4>
		<h4>Share price <span></span><?php echo CO_OWNER_CURRENCY_SYMBOL.' '.number_format($property->available_price) ; ?></h4>
		<?php else: ?>
		<h4 class="pt-3 text-error">Portions of the property are not available</h4>
		<?php endif; ?>
		<hr>
		<?php endif; ?>

		<h4>Currently leased <span></span><?php echo $property->currently_on_leased; ?></h4>

		<?php if(strtolower($property->currently_on_leased) == 'yes'): ?>
		<h4 class="pt-3">MONTHLY RENT</h4>
		<h3><?php echo CO_OWNER_CURRENCY_SYMBOL.' '.number_format($property->rent_per_month); ?><strong class="str-list text-orange">PM</strong></h3>
		<?php endif; ?>
		<h4>Negotiable<span></span> <?php echo $property->negotiable ? 'Yes' : 'No'; ?></h4>
</div>
</div>

	<div class="box-sellprice box-sellprice-totla box-shadow-new">
		<h3 class="mb-3" style="text-align: center;">Calculate your share</h3>
		<div class="input-residential-badprki">
		   <label for="_pl_i_want_to_sell">Amount you want to invest</label>
		   <div class="badprki-fields-edit">
		   	<input type="text" name="_pl_i_want_to_sell" id="_pl_i_want_to_sell" placeholder="0" >
  		     <span class="edit-field"></span>
            </div>
		   <input type="text" name="_pl_i_want_to_sell_range" id="_pl_i_want_to_sell_range">
		</div>
		<div class="input-residential-badprki">
		   <label for="_pl_i_want_to_sell">Share in the property, %</label>
		   <div class="badprki-fields-edit">
		   	 <input type="text" name="_pl_price_sell" id="_pl_price_sell" placeholder="0" >
  		     <span class="edit-field"></span>
            </div>
		  <input type="text" name="_pl_price_sell_range" id="_pl_price_sell_range">
		</div>
 

	</div>



<div class="calculate-share-btn-mobile box-shadow-new hidden">Calculate your share</div>


<?php if(!$is_my_property): ?>
<?php if(!$property->enable_pool) : ?>

<div class="investor-cnt mt-3">
<h3 class="mb-2">Interested in this listing?</h3>
<p class="pb-3">Connect with the Admin to learn more about the listing.</p>
<a href="<?php echo $connection_link; ?>" data-id="<?php echo $property->ID; ?>" class="btn btn-orange rounded-pill" <?php echo ($user_id && $user_status == 1 && !$property->is_already_member) ? 'data-bs-toggle="modal" data-bs-target="#property-connection-modal"' : '' ?>>Show your interest</a>
</div>
 
<?php elseif($property->available_share > 0): ?>
	<div class="box-sellprice box-sellprice-share box-shadow-new">
<div class="inner-price-cal">
<h3 class="mb-3">Calculate your share</h3>

<div class="mb-3">
<label for="buy" class="form-label">Share I want to Buy</label>
<div class="custom-select">
<select name="_pl_i_want_to_sell" data-max="<?php echo $property->available_share; ?>" class="single-pr-select2 share form-select">
<option value="">Select I want to Buy</option>
<?php for ($i = 1; $i <= $property->available_share; $i++): ?>
<option value="<?php echo $i; ?>"><?php echo $i; ?>%</option>
<?php endfor; ?>
</select>
</div>
</div>

<div class="mb-2">
<label for="price" class="form-label">Price</label>
<input type="text" class="form-control price input-only-price" data-max="<?php echo $property->available_price; ?>" placeholder="Input">
</div>

<div class="pt-3">
<a href="#"
data-id="<?php echo $property->ID; ?>"
data-available-share="<?php echo $property->available_share; ?>"
data-available-price="<?php echo $property->available_price; ?>"
class="btn btn-orange rounded-pill mb-2 calculate-price">Calculate Price</a>
<?php if($user_status == 1 && !$property->is_already_member): ?>
<a href="#" data-id="<?php echo $property->ID; ?>" class="btn btn-orange rounded-pill mb-2" data-bs-toggle="modal" data-bs-target="#property-connection-modal">Show your Interest</a>
<?php elseif($user_status == 2): ?>
<a href="<?php echo $connection_link; ?>" class="btn btn-orange rounded-pill mb-2">Show your Interest</a>
<?php endif; ?>
</div>
</div>
<?php endif; ?>
<?php endif; ?>
</div>

<?php
	$filters = array(
	'price' => $property->property_market_price,
	'state' => $property->state,
	'exclude' => array($property->ID)
	);
	$similar_properties = get_similar_properties($filters);
?>
 
<div class="findexpert-side">       
        <?php
            if(is_active_sidebar('findexpert')) {
                dynamic_sidebar('findexpert');
            }
        ?>          
    </div>
    </div>
<?php 
	
	$args = array(  
	'post_type' => 'community',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'order' => 'DESC', 
	);
	
	$lawyerObj = new WP_Query( $args ); 
	
	
	
	
?>
 
 
</div>



</div>
</div>

</div>

<!-- <div class="comment-add">
	<div class="container">
		<h2>Add Comments</h2>
		<?php //comments_template( '', true ); ?>
	</div>
</div> -->


</div>

<?php else: ?>

<?php include CO_OWNER_THEME_DIR.'parts/404.php'; ?>

<?php endif; ?>
</div>
<?php 
$postid = $post->ID;
if(isset($_GET['id'])){
$postid = $_GET['id'];	
}

?>
<?php 
$cu = wp_get_current_user(); ?>


<script> 

function ValidateEmail(email) 
{
    var re = /\S+@\S+\.\S+/;
        return re.test(email);
}
jQuery('#agreement').on('change', function() {
var agreementval = jQuery(this).val();
if(agreementval != ""){
jQuery('.form-btn-bar').show();	
}else{
jQuery('.form-btn-bar').hide();
}
});
jQuery('#cancel-enq').on('click', function() {
jQuery('.btn-close.ms-auto').trigger('click');
});
jQuery('#enquirey').on('submit' , function(){ 

jQuery('.load-custom').show();



/*
var formData = {name:"<?php echo $cu->user_firstname; ?>", email:"<?php echo $cu->user_email; ?>",title:"<?php echo get_the_title(); ?>", urls:"<?php echo get_the_permalink($post->ID); ?>",
'agreement' : jQuery('#agreement').val() ,action :"my_action_name"  , assistance: jQuery('#assistance').val(), partner: jQuery('#partner').val() }; //Array 
*/
var name = '';
var user_email = jQuery('#email').val();
var first_name = jQuery('#first_name').val();
var last_name = jQuery('#last_name').val();

var is_logged_in = jQuery('#is_logged_in').val();

if(is_logged_in=="No"){
	if(jQuery('#first_name').val()) {
	name = jQuery('#first_name').val()+' '+jQuery('#last_name').val();		
	}
}
else{
name = jQuery('#name').val();	
	
}

if(is_logged_in=="No"){
	
	if(first_name ==""){
		   jQuery('.require-fielderor.formemail').addClass('require-error-show');
	return false;
}
if(last_name ==""){
		   jQuery('.require-fielderor.formemail').addClass('require-error-show');
	return false;
}
else if (!ValidateEmail(user_email)){
    jQuery('.require-fielderor.formemail').addClass('require-error-show');
	return false;
	}

}


var formData = jQuery(this).serialize();
formData = formData+'&name='+name;

var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

jQuery.ajax({
url : ajaxurl,
type: "POST",
data : formData,
dataType : 'json',
success: function(data)
{ 
jQuery('.load-custom').hide();
jQuery('.enquiry-confirmation-form').hide();
jQuery('.tankyu-enqury').show();

//jQuery('#enquire-sent').html('Data has been '+data.sent);
//data - response from server
},
error: function (jqXHR, textStatus, errorThrown)
{

}


});
return false;

});

/* Drop down function */
jQuery('#assistance').change(function(){

if(jQuery(this).val() ==''){
jQuery('.form-btn-bar').hide();
}	

jQuery.ajax({
url : php_vars.ajax_url,
type: "POST",
data : {
id: jQuery(this).val(),
action : 'fetch_lawyer_data_dropdown'

},
success: function(data)
{ 

data = '<option value=""> Select Option </option>'+data;
jQuery('#partner').html(data);
},
error: function (jqXHR, textStatus, errorThrown)
{

}
});	

});

jQuery('body').on('change','#partner',function(){
if(jQuery(this).val() ==''){
jQuery('.form-btn-bar').hide();
}
else if(jQuery(this).val() && jQuery('#assistance').val()){
jQuery('.form-btn-bar').show();
}else{
jQuery('.form-btn-bar').hide(); 
}

});



//
jQuery(document).ready(function(){
  jQuery(".price-boxsel").click(function(){
  	jQuery(this).toggleClass("price-boxsel-active") ,
    jQuery(".price-boxseinfo").toggleClass("price-boxseinfo-active");
 });

  jQuery(".calculate-share-btn-mobile").click(function(){
  	jQuery(this).toggleClass("share-btn-active") ,
    jQuery(".box-sellprice-share").toggleClass("box-sellprice-share-active");
 });
 
 
 	var delay = (function(){
	  var timer = 0;
	  return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	  };
	})();
 

var _pl_i_want_to_sell_range = jQuery('#_pl_i_want_to_sell_range');
var _pl_price_sell_range = jQuery('#_pl_price_sell_range');
_pl_i_want_to_sell_range.ionRangeSlider({
        type: "single",
        grid: true,
        min: 1,
        max: 100,
        from: 1,
        to: 30,
        postfix: "",
		onChange: function (data) {
            // fired on every range slider update
			jQuery('#_pl_i_want_to_sell').val(data.from);
        },
        onUpdate: function (data) {
            // fired on pointer release
			jQuery('#_pl_i_want_to_sell').val(data.from);
        },		
    });
	

_pl_price_sell_range.ionRangeSlider({
        type: "single",
        grid: true,
        min: 1,
        max: 1000000,
        from: 5000,
        to: 500000,
        prefix: "$",
       onChange: function (data) {
            // fired on every range slider update
			jQuery('#_pl_price_sell').val(data.from);
        },	
        onUpdate: function (data) {
            // fired on pointer release
			jQuery('#_pl_price_sell').val(data.from);
        },
		
    });	

var _pl_i_want_to_sell_range_instance = _pl_i_want_to_sell_range.data("ionRangeSlider");
var pl_price_range_instance = _pl_price_sell_range.data("ionRangeSlider");


 
 jQuery('#_pl_i_want_to_sell').keyup(function() {
	var _this = jQuery(this);
    delay(function(){
        _pl_i_want_to_sell_range_instance.update({
            from: _this.val(),
        });	 
    }, 1000 );
});

jQuery('#_pl_price_sell').keyup(function() {
	var _this = jQuery(this);
    delay(function(){
        pl_price_range_instance.update({
            from: _this.val(),
        });	 
    }, 1000 );
})

 
 
 
 
 
});


/* Drop down function */

</script>

<?php 
get_footer(); ?>
<?php include('modals/favorite-remove-box.php'); ?>