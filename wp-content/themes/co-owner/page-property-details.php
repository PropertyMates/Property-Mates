<?php
	get_header();
	
	$user_id = get_current_user_id();
	$current_user = get_person_detail_by_id($user_id);
	$property = get_property_detail();
	
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
	<div class="main-section bg-white public-title border-bottom py-20px">
		<div class="container">
			<div class="row">
				<?php // $is_matching = $property ? check_is_maching_property($user_id,$property->ID) : false; ?>
				<div class="col-sm-12 inner-section-grey list-view-title">
					<div class="title-area">
						<h3 class="d-flex align-items-center">
							
							<?php echo $property->enable_pool ? co_owner_get_svg('enable_pool').'&nbsp;' : ''; ?>
							<span><?php echo $property->post_title; ?></span>
							
							<?php if($is_my_property): ?>
							<?php if($property->post_status == 'publish'): ?>
							<div class="ms-auto pt-3 pt-md-0">
								<div class="">
									<a href="<?php echo home_url(CO_OWNER_CREATE_A_PROPERTY_PAGE) ?>/?id=<?php echo $property->ID; ?>" class="btn btn-orange-bordered rounded-pill my-1">Edit Listing</a>
									<a href="#" data-id="<?php echo $property->ID; ?>" class="btn btn-orange-bordered rounded-pill my-1 property-delete-listing">
										Delete Listing
									</a>
									<?php if($property->post_status != 'completed'): ?>
									<a href="#" data-url="<?php echo home_url() ?>?action=property_mark_as_completed&id=<?php echo $property->ID; ?>" class="btn btn-orange rounded-pill my-1 confirm-to-property-mark-as-completed">
										Mark as Completed
									</a>
									<?php endif; ?>
								</div>
							</div>
							<?php else: ?>
							<div class="ms-auto pt-3 pt-md-0">
								<a href="<?php echo home_url(CO_OWNER_CREATE_A_PROPERTY_PAGE) ?>/?id=<?php echo $property->ID; ?>" class="btn btn-orange-bordered rounded-pill my-1">
									Continue Edit
								</a>
								<a href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE) ?>/?id=<?php echo $property->ID; ?>&update_property_status=publish" class="btn btn-orange-bordered rounded-pill my-1 post_now_job">
									Post Listing
								</a>
							</div>
							<?php endif; ?>
							<?php else: ?>
							<div class="ms-auto pt-3 pt-md-0">
								<div class="">
									<?php if(!current_user_can('administrator') && $property->available_share > 0): ?>
									<a href="<?php echo $connection_link; ?>" data-id="<?php echo $property->ID; ?>" class="btn btn-orange-bordered <?php echo !$property->is_liked ? 'add-to-shortlist' : 'remove-to-shortlist'; ?> rounded-pill my-1 px-4"><?php echo $property->is_liked ? 'Remove From ' : null; ?>Shortlist</a>
									<?php if(!$property->is_already_member): ?>
									<a href="<?php echo $connection_link; ?>" data-id="<?php echo $property->ID; ?>" class="btn btn-orange rounded-pill px-4 my-1" <?php echo ($user_id && $user_status == 1 && !$property->is_already_member && $show_connection_button) ? 'data-bs-toggle="modal" data-bs-target="#property-connection-modal"' : ''; ?>>Connect</a>
									<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
							<?php endif; ?>
						</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php if($property->images && count($property->images) > 0): ?>
	<div class="main-section list-section pt-40px">
		<div class="container">
			<div class="row">
				<div class="col-xl-7 col-lg-7 col-md-12 col-12 pb-3 mb-1">
					<div class="big-thumb-property">
						<img src="<?php echo $property->images[0]['url']; ?>" alt="">
					</div>
				</div>
				
				<div class="col-xl-5 col-lg-5 col-md-12 col-12 property-show">
					<div class="row">
						<?php foreach ($property->images as $key => $image): ?>
						<?php if($key != 0 && $key < 5): ?>
						<?php $filename_from_url = parse_url($image['url']);
                           $ext = pathinfo($filename_from_url['path'], PATHINFO_EXTENSION); 
						   if($ext){
						   ?>
						<div class="col-sm-6 col-6 pb-3 mb-1">
							<div class="medium-thumb-property">
							
								<img src="<?php echo $image['url']; ?>" alt="">
							</div>
						</div>
						   <?php } ?>
						<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<a href="#" class="btn btn-white rounded-pill" data-bs-toggle="modal" data-bs-target="#property-images-modal">show all Photos</a>
				</div>
				
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<div class="main-section py-40px pt-4 pb-0">
		<div class="container">
			<div class="row">
				<div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-12">
					<div class="card custom-card public-view-card">
						<div class="card-body">
							<h3 class="d-flex">
								<div>
									Property Information
									<?php if($property->enable_pool == true && $property->available_share > 0): ?>
									<span class="badge bg-dark-teal">Looking for people to join</span>
									<?php endif; ?>
									<?php if($property->available_share == 0): ?>
									<span class="badge bg-danger pool-is-full">POOL IS FULL</span>
									<?php endif; ?>
								</div>
							</h3>
							<h4>Address </h4>
							<p><?php echo $property->address; ?></p>
							<div class="property-facility-area">
								<?php if($property->property_category == 'residential'): ?>
								<a href="#">
									<?php echo co_owner_get_svg('bedroom'); ?>
									<span><?php echo $property->bedroom; ?></span>
								</a>
								<a href="#">
									<?php echo co_owner_get_svg('bathroom'); ?>
									<span><?php echo $property->bathroom; ?></span>
								</a>
								<a href="#">
									<?php echo co_owner_get_svg('parking'); ?>
									<span><?php echo $property->parking; ?></span>
								</a>
								<?php endif; ?>
							</div>
							
							<div class="row">
								<div class="col-sm-12 col-12 pt-40px">
									<h4>Description </h4>
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
							
							<div class="row pt-40px">
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
									<h4>Property Type</h4>
									<p><?php echo ucfirst($property->property_category).' - '.ucfirst($property->property_type); ?></p>
								</div>
								
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
									<h4>Building Area</h4>
									<p><?php echo ucfirst($property->building_area) ?? ''; ?> </p>
								</div>
							</div>
							
							<div class="row pt-40px">
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
									<h4>Land Area</h4>
									<p><?php echo ucfirst($property->land_area) ?? ''; ?> </p>
								</div>
								
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
									<h4>Age/year built</h4>
									<p><?php echo ucfirst($property->age_year_built) ?? ''; ?> </p>
								</div>
							</div>
							
							<?php if(is_array($property->property_features) && count($property->property_features) > 0): ?>
							<div class="row pt-40px">
								<div class="col-sm-12 col-12">
									<h4>Property Features</h4>
								</div>
								<?php
                                    $array1 = (is_array($property->property_features) && count($property->property_features) > 0) ? $property->property_features : array();
                                    $array2 = (is_array($property->manually_features) && count($property->manually_features) > 0) ? $property->manually_features : array();
								foreach (array_merge($array1,$array2)  as $features): ?>
								<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
									<p><?php echo $features; ?></p>
								</div>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>
							
							<div class="d-flex align-items-center pt-5 pb-3">
								<h3 class="pb-0">Member<?php echo (count($property->members) > 1) ? '(s)' : ''; ?> Information</h3>
								<?php if($user_status == 1 && $property->post_author == $user_id && $property->enable_pool && $property->available_share > 0 && $property->post_status == 'publish'): ?>
								<a href="#" class="btn btn-orange rounded-pill ms-auto text-nowrap" data-bs-toggle="modal" data-bs-target="#my-members-modal">Add Member</a>
								<?php endif; ?>
							</div>
							
							<div class="row">
								<?php foreach($property->members as $key => $member): ?>
								<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 mb-4 member-box">
									<div class="card member-card <?php echo $member->is_admin ? 'green' : ( ($key % 2) ? 'red' : 'yellow'); ?>">
										<div class="card-body">
											<div class="mbr-title d-flex w-100">
												<h6>Member <?php echo ($key)+1; ?></h6>
												<div class="dropdown member-drop ms-auto">
													<?php if(!$member->is_admin && $property->post_author == $user_id): ?>
													<button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
														<?php echo co_owner_get_svg('3-dots'); ?>
													</button>
													<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
														<!--                                                            <li><a class="dropdown-item" data-id="--><?php //echo $member->id; ?><!--" href="#">Make Admin</a></li>-->
														<li><a class="dropdown-item remove-group-member" data-group-id="<?php echo $member->group_id; ?>" data-id="<?php echo $member->id; ?>" href="#">Remove </a></li>
													</ul>
													<?php  endif; ?>
												</div>
											</div>
											
											<div class="mbr-detail-area">
												<div class="<?php echo get_user_shield_status($member->id) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''?> mt--46px">
													<div class="mbr-thumb mx-auto">
														<img src="<?php  echo esc_url( get_avatar_url($member->id));  ?>" alt="">
													</div>
												</div>
												<a href="<?php echo home_url('/'.CO_OWNER_PERSON_DETAILS_PAGE).'?id='.$member->id; ?>">
                                                    <h4 class="text-center"><?php echo $member->display_name; ?></h4>
												</a>
												<div class="property-own text-center">
													<?php if($member->is_admin && $property->interested_in_selling == 'full_property'): ?>
													<?php echo $property->posted_by; ?>  | Admin
													<?php elseif($member->is_admin && $property->interested_in_selling == 'portion_of_it'): ?>
													<?php echo $property->posted_by; ?>  | Admin | holds <?php echo get_admin_hold_pr($property->ID); ?>%
													<?php else: ?>
													holds <?php echo $member->interested_in; ?>% Portions
													<?php endif; ?>
												</div>
												
												<?php if($user_id): ?>
												<span class="title text-center">Email id</span>
												<span class="cnt text-center">
													<a href="mailto:<?php echo $member->user_email; ?>">
														<?php echo $member->user_email; ?>
													</a>
												</span>
												<?php if($member->mobile): ?>
												<span class="title text-center">Phone No</span>
												<span class="cnt text-center"><?php echo $member->mobile; ?></span>
												<?php endif; ?>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
							
							<!-- black bar section start -->
							
							<div class="book-nn">
								<div class="logo-engury"><img src="<?php echo get_template_directory_uri(); ?>/images/sutton-logo-white.png"></div>
								<div class="book-enq">
									<p>Ready to proceed ahead with this property? We have you covered!</p>
									
									<?php 
										if(is_user_logged_in()){    ?>
										<a class="btn btn-orange rounded-pill ms-auto" href="#" data-bs-toggle="modal" data-bs-target="#enquire-popup-detail">Enquire Now </a>
										<?php
										} else { ?>
										<span><a class="btn btn-orange rounded-pill ms-auto" href="<?php echo site_url().'/login?vs=1'; ?>" >Enquire Now </a></span>
										
									<?php }  ?>
								</div>
								
								
								<div class="modal enquire-pp fade default-modal-custom" id="enquire-popup-detail" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-xl">
										<div class="modal-content">
											<div class="modal-body">
												<div class="row">
													<div class="col-sm-12 col-12 d-flex">
														<button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<?php 
														if(is_user_logged_in()){
															$cu = wp_get_current_user(); 
															//print_r($cu);
															
														?>
														<div class="col-12 enquiry-confirmation-form">
															<h4>Please Confirm</h4>
															
															<form id="enquirey">
																<div class="field-form">
																	<label>Property Details:</label>
																	<input type="text" value="<?php echo $property->address; ?>">
																</div>
																<div class="field-form">
																	<label>User Details:</label>
																	
																	<?php
																		echo '<input  type="hidden" value="'.$cu->full_name.'" name="full_name"><br/>
																			  <input  type="hidden" value="'.$cu->full_name.'" name="email"> 
																			  <input  type="hidden" value="'.$cu->_mobile.'" name="phone_number"> 
																		<p>'.$cu->full_name.'</p>
																		<p>'.$cu->user_email.' | '.$cu->_mobile.'</p>';
																		//echo '   Paras Kumar  paras.kumar@gmail.com | 0452375990';
																	?>
																	
																</div>
																<div class="field-form">
																	<label>Please Select the reason Below:</label>
																	<select required="required" id="agreement" name="agreement" required>
																		<option value="">Select Option</option>
																		<option value="Co-ownership agreement">Co-ownership agreement</option>
																		<option value="Review of sale contract">Review of sale contract</option>
																		<option value="Conveyancing">Conveyancing</option>
																	</select>
																</div>
																
																<div style="display:none" class="form-btn-bar">
																	<a id="cancel-enq" class="btn btn-orange-outline rounded-pill ms-auto" href="#">Cancel</a>
																	<!--       <a class="btn btn-orange rounded-pill ms-auto" href="#">Submit</a>-->
																	<button  type="submit" value="submit">Submit</button>
																	<img  class="load-custom" src="<?php echo get_template_directory_uri(); ?>/images/loading-buffering.gif">
																	<div id="enquire-sent" ></div>
																</div>
															</form>
														</div>
														
													<?php } ?>
													<div class="col-12 enquiry-data tankyu-enqury">
														<img src="<?php echo get_template_directory_uri(); ?>/images/tick.jpg">
														<h3>Thank you for showing your Interest.</h3>
														<p>We have received your message. <br> Our team will get in touch with you soon.</p>
														<br>
														<p>Please check your email for future updates.</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								
								<!-- black bar section end -->
								
								
								
								
								
							</div>
							
							<h3 class="pt-5">Location</h3>
							<div class="row">
								<div class="col-sm-12 col-12">
									<div class="public-view-map" id="property-map-view" data-id="<?php echo $property->ID; ?>" data-address="<?php echo get_property_full_address($property->ID,true); ?>" style="height: 450px;width: 100%;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12">
					<div class="card custom-card public-view-card">
						<div class="card-body pb-4">
							<h3>Price Information</h3>
							<h4>Property Market Price</h4>
							<h2><?php echo CO_OWNER_CURRENCY_SYMBOL; ?> <?php echo number_format($property->property_original_price); ?></h2>
							<h2 class="text-black-50 preview-get-real-market-value"></h2>
							<!--                                <a href="#" class="blue-link get-real-market-value" data-address="--><?php //echo get_property_full_address($property->ID,true); ?><!--">-->
							<!--                                    Get real market value of this property-->
							<!--                                </a>-->
							<a href="<?php echo WWW_DOMAIN_COM_AU_PROPERTY_PROFILE; ?>" target="_blank" class="fs-6">Get real market value</a>
							<h2 class="text-black-50 d-none">
								<div class="domain-logo">
									<span>Powered by</span>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 97 22"><path class="domain-logo__svg-icon-tow" fill="#0ea800" d="M72.51 18.27v-7c0-4.28-2.63-5.51-6-5.51-3.62 0-6 1.89-6 5h2.79c0-1.55 1-2.42 3.29-2.42 2.49 0 3.21 1.08 3.21 2.09 0 .84-.18 1.29-1.65 1.56l-3.32.63c-2.91.54-5 1.86-5 4.91 0 2.82 2.34 4.47 5 4.47A6.15 6.15 0 0070 19.62V20a1.54 1.54 0 001.41 1.66 1.37 1.37 0 00.35 0H74v-2.34h-.36c-.86 0-1.13-.32-1.13-1.05zm-2.66-2.69c0 2.09-1.77 3.89-4.53 3.89-1.64 0-2.54-.84-2.54-2.22s1-2 3.11-2.48l2.67-.57a4.21 4.21 0 001.29-.45zM7 .12H0V21.6h6.71c6.62 0 11-4.53 11-10.76C17.7 3.83 12.67.12 7 .12zm-.29 18.81H2.94V2.82h3.47c4.91 0 8.33 2.66 8.33 8s-3.69 8.11-8.03 8.11zM75.66 6.15h2.68V21.6h-2.68zM87.87 5.75a5.53 5.53 0 00-4.42 2v-1.6h-2.68V21.6h2.68v-9.75a3.73 3.73 0 016-2.95 4 4 0 01.48.44l.07.09a3.68 3.68 0 01.89 2.41v9.76h2.68v-9.53c.02-3.98-2.1-6.32-5.7-6.32zM77 0a1.81 1.81 0 101.82 1.8A1.82 1.82 0 0077 0zM52.18 5.48L47.4 8.73l-4.8-3.25-3.45 2.33V6.15h-2.68V21.6h2.68V11.06l3.45-2.34 3.43 2.34V21.6h2.68V11.06l3.47-2.34 3.46 2.34V21.6h2.68V9.62l-6.14-4.14zM27 5.74a7.93 7.93 0 00-8.08 7.78v.36a7.91 7.91 0 007.7 8.12H27a7.91 7.91 0 008-7.82v-.3a7.93 7.93 0 00-7.72-8.14zm0 13.54a5.41 5.41 0 115.4-5.42 5.42 5.42 0 01-5.4 5.42z" fill-rule="evenodd"></path></svg>
								</div>
							</h2>
							
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
							<h4 class="pt-3">Pool Information</h4>
							<div class="pl-list">
								<ul>
									<?php foreach ($property_members as $p_member): if($p_member->is_admin == 0): ?>
									<li class="d-flex align-items-center">
										<span class="name"><?php echo $p_member->display_name; ?> holds</span>
										<span class="percent ms-auto"><?php echo $p_member->interested_in; ?>%</span>
									</li>
									<?php endif; endforeach; ?>
								</ul>
							</div>
							<?php endif; ?>
							
							<?php if($property->available_share == 0): ?>
							<span class="badge bg-danger pool-is-full" style="font-size: 10px;">POOL IS FULL</span>
							<?php endif; ?>
							
							<?php if($property->available_share > 0): ?>
							<h4 class="pt-3">Available portion</h4>
							<h3><?php echo $property->available_share; ?> %</h3>
							
							<h4 class="pt-3">Will Cost</h4>
							<h3><?php echo CO_OWNER_CURRENCY_SYMBOL.' '.number_format($property->available_price) ; ?></h3>
							<?php else: ?>
							<h4 class="pt-3 text-error">Portions of the property are not available</h4>
							<?php endif; ?>
							<hr>
							<?php endif; ?>
							
							<h4 class="pt-3">CURRENTLY LEASED</h4>
							<h3 class="text-capitalize"><?php echo $property->currently_on_leased; ?></h3>
							
							<?php if(strtolower($property->currently_on_leased) == 'yes'): ?>
							<h4 class="pt-3">MONTHLY RENT</h4>
							<h3><?php echo CO_OWNER_CURRENCY_SYMBOL.' '.number_format($property->rent_per_month); ?><strong class="str-list text-orange">PM</strong></h3>
							<?php endif; ?>
							
							<h4 class="pt-3">NEGOTIABLE</h4>
							<h3 class="text-capitalize"><?php echo $property->negotiable ? 'Yes' : 'No'; ?></h3>
							
							
							<?php if(!$is_my_property): ?>
							<?php if(!$property->enable_pool) : ?>
							<div class="investor-cnt mt-3">
								<h3 class="mb-2">Interested in this listing?</h3>
								<p class="pb-3">Connect with the Admin to learn more about the listing.</p>
								<a href="<?php echo $connection_link; ?>" data-id="<?php echo $property->ID; ?>" class="btn btn-orange rounded-pill" <?php echo ($user_id && $user_status == 1 && !$property->is_already_member) ? 'data-bs-toggle="modal" data-bs-target="#property-connection-modal"' : '' ?>>Show your interest</a>
							</div>
							<?php elseif($property->available_share > 0): ?>
							<div class="inner-price-cal">
								<span class="badge bg-dark-teal mb-3">Looking for people to join</span>
								<p class="pb-3">Below is a calculator for your convenience to calculate the portion v/s budget you can get.</p>
								
								<h3 class="mb-3">Price Calculator</h3>
								
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
					</div>
					<?php
                        $filters = array(
						'price' => $property->property_market_price,
						'state' => $property->state,
						'exclude' => array($property->ID)
                        );
                        $similar_properties = get_similar_properties($filters);
					?>
					<?php if(count($similar_properties) > 0): ?>
					<div class="side-property-main pt-30px">
						<h3>Similar Properties </h3>
						<?php
                            foreach ($similar_properties as $sm_property):
						?>
						<div class="card custom-card side-card mb-4">
							<div class="card-body">
								<div class="side-property-thumb">
									<a href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE).'?id='.$sm_property->ID ?>">
										<img src="<?php echo $sm_property->image; ?>" alt="">
									</a>
								</div>
								<div class="side-property-cnt">
									<a href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE).'?id='.$sm_property->ID ?>">
										<p><?php echo $sm_property->address; ?></p>
									</a>
									<h2><?php echo CO_OWNER_CURRENCY_SYMBOL.' '.number_format($sm_property->price); ?></h2>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
					
					<?php endif; ?>
					<div class="side-new">
						<img src="<?php echo get_template_directory_uri(); ?>/images/sutton-logo.png">
						<h5>Need advice or assistance with a legal matter?</h5>
						<a class="btn btn-orange rounded-pill ms-auto" href="/thanku-consultation" >Book Consultation</a>
						<?php //echo do_shortcode('[accept_stripe_payment name="Payments (powered by Stripe). This is a 60 mins consultation with our law firm. You can discuss anything in this call." price="250" url="http://example.com/downloads/my-script.zip" button_text="Book Consultation"]'); ?>
					</div>
				</div>
				
				
				
			</div>
			 
			
		</div>
		<div class="comment-add">
			<div class="container">
				<h2>Add Comments</h2>
		<?php comments_template( '', true ); ?>
				
				
				
	
		</div></div>
	</div>
	
    <?php else: ?>
	
	<?php include CO_OWNER_THEME_DIR.'parts/404.php'; ?>
	
    <?php endif; ?>
</div>
<?php 
$postid = "";
if(isset($_GET['id'])){
$postid = $_GET['id'];	
}
	
?>
<?php if(is_user_logged_in()){
$cu = wp_get_current_user(); ?>
<script> 
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
	jQuery('#enquirey').on('submit' , function(e){ 
		e.preventDefault();
		jQuery('.load-custom').show();
		
		var formData = {name:"<?php echo $cu->user_firstname; ?>", email:"<?php echo $cu->user_email; ?>",title:"<?php echo get_the_title(); ?>", urls:"<?php echo get_the_permalink().'?id='.$postid; ?>",
		'agreement' : jQuery('#agreement').val() ,action :"my_action_name"}; //Array 
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
		
	});
</script>
<?php } 
get_footer(); ?>