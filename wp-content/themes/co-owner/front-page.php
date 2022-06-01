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
	$front_page = get_option('page_on_front');
	$auth_user = wp_get_current_user();
	$auth_user_id = (isset($auth_user->ID) ? (int) $auth_user->ID : 0);
?>

<div class="banner-main w-100 d-block text-center" <?php if (wp_get_attachment_image_url(get_post_meta($front_page, '_front_page_banner', true))) {
	echo "style='background: url(" . wp_get_attachment_image_url(get_post_meta($front_page, '_front_page_banner', true), 'full') . ") center center no-repeat;background-size: cover;'";
} ?>>
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-12 banner-cnt">
			<h1><?php echo get_post_meta($front_page, '_front_page_banner_title', true); ?></h1>
			<p>
				<?php echo get_post_meta($front_page, '_front_page_banner_description', true); ?>
			</p>
			<?php if (get_post_meta($front_page, '_front_page_banner_whow_how_its_works', true) == 'yes') : ?>
			<!-- <a href="#" class="btn btn-rounded btn-grey mt-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<g fill="none" fill-rule="evenodd">
				<g fill="#FE7400" fill-rule="nonzero">
				<g>
				<g>
				<path d="M32 10c3.205 0 6.219 1.248 8.485 3.515C42.752 15.78 44 18.795 44 22c0 2.369-.703 4.672-2.033 6.662-.288.43-.87.546-1.3.258-.431-.288-.547-.87-.259-1.3 1.123-1.68 1.717-3.624 1.717-5.62 0-5.583-4.542-10.125-10.125-10.125S21.875 16.417 21.875 22 26.417 32.125 32 32.125c1.852 0 3.663-.504 5.238-1.458.442-.268 1.019-.127 1.287.316.269.443.127 1.02-.316 1.288C36.341 33.402 34.194 34 32 34c-3.205 0-6.219-1.248-8.485-3.515C21.248 28.22 20 25.205 20 22c0-3.205 1.248-6.219 3.515-8.485C25.78 11.248 28.795 10 32 10zm-3.111 7.094c.661-.38 1.45-.38 2.11.004l5.29 3.07c.66.382 1.055 1.068 1.055 1.832s-.394 1.45-1.054 1.833L31 26.902c-.331.192-.695.288-1.059.288-.36 0-.722-.095-1.052-.284-.664-.383-1.06-1.07-1.06-1.836v-6.14c0-.767.396-1.453 1.06-1.836zm1.053 1.592c-.047 0-.087.016-.117.033-.055.032-.121.094-.121.212v6.138c0 .118.066.18.12.212.054.03.138.055.235 0l5.29-3.07c.099-.058.12-.147.12-.211s-.021-.153-.12-.21l-5.29-3.07c-.042-.025-.082-.034-.117-.034z" transform="translate(-80 -378) translate(60 120) translate(0 248)"></path>
				</g>
				</g>
				</g>
				</g>
				</svg>
				<span class="ps-1">
				
				<?php if ($text = get_option('_how_its_works_button_title')) : ?>
				<?php echo $text; ?>
				<?php else : ?>
				How it works
				<?php endif; ?>
				</span>
			</a> -->
			
			
			<p class="work-videos">How it works <a title="Watch Video" class="pop-video" href="#"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Watch</a>  or <a title="Read the steps" class="on-scroll" href="#how-id"> Read</a>
				
				
				<div class="video-banners">
                <div class="video-banners-in" title="Introductory video">
					<?php
						$link = get_option('_how_its_works_button_link');
						echo do_shortcode("[videojs_video url='{$link}' controls poster='https://test.propertymates.io/wp-content/themes/co-owner/images/video-poster.jpg']");
					?>
				</div></div>
                <?php endif; ?>
			</div>
			
		</div>
	</div>
</div>

<div class="center-area">
    <!--  FRONT PAGE CENTER CONTENT  -->
    <div class="main-section mt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col col-sm-12 col-12 hm-title-section text-center">
                    <h3 class="text-center"><?php the_title(); ?></h3>
                    
					<?php the_content(); ?>
					
				</div>
                <div class="col col-sm-12 col-12 justify-content-center">
                    <div class="card property-btn mx-auto">
                        <div class="card-body">
                            <div class="row">
                                <?php
									if (
                                    ($block_1_label = get_post_meta($front_page, '_front_page_block_1_label', true)) &&
                                    ($block_1_title = get_post_meta($front_page, '_front_page_block_1_title', true)) &&
                                    ($block_1_link = get_post_meta($front_page, '_front_page_block_1_link', true))
									) :
									
									$block_1_link = (is_user_logged_in()) ? $block_1_link : site_url('/login');
									
									
								?>
								<div class="col-md-6 col-sm-12 col-12 position-relative">
									<a  title="Create a buyer profile" href="<?php echo $block_1_link; ?>" class="d-flex property-inner-btn align-items-center br-1">
										<div class="lnk-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
												<g fill="none" fill-rule="evenodd">
													<g>
														<g>
															<path d="M0 0H40V40H0z" transform="translate(-751 -901) translate(751 901)"></path>
															<path fill="#FE7400" fill-rule="nonzero" d="M20 3C8.954 3 0 12.16 0 23.458 0 28.382 1.701 32.9 4.534 36.43c.437-3.58 3.932-6.607 8.834-8.04 1.79 1.706 4.103 2.741 6.632 2.741 2.47 0 4.737-.983 6.51-2.618 4.943 1.559 8.363 4.763 8.475 8.488C38.103 33.392 40 28.653 40 23.458 40 12.16 31.046 3 20 3zm0 27.2c-1.53 0-2.971-.42-4.251-1.149-3.085-1.755-5.209-5.347-5.209-9.494 0-5.868 4.244-10.642 9.46-10.642 5.217 0 9.46 4.774 9.46 10.642 0 4.211-2.19 7.847-5.352 9.572-1.245.68-2.635 1.07-4.108 1.07z" transform="translate(-751 -901) translate(751 901)"></path>
														</g>
													</g>
												</g>
											</svg>
										</div>
										<div class="w-100 lnk-cnt">
											<?php echo $block_1_label; ?>
											<span class="d-block">
												<?php echo $block_1_title; ?>
											</span>
										</div>
										<div class="lnk-arrow">
											<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
												<g fill="none" fill-rule="evenodd">
													<g>
														<g>
															<g>
																<path d="M36 0L0 0 0 36 36 36z" transform="translate(-1392 -1340) translate(0 1012) translate(1392 328)"></path>
																<path fill="#FE7400" d="M19.795 31.108c-.42.458-.388 1.17.07 1.589.459.42 1.17.388 1.59-.07L34.16 18.749c.394-.43.394-1.09 0-1.52L21.454 3.365c-.42-.458-1.131-.49-1.59-.07-.457.42-.488 1.132-.068 1.59L31.805 17.99l-12.01 13.118z" transform="translate(-1392 -1340) translate(0 1012) translate(1392 328)"></path>
															</g>
														</g>
													</g>
												</g>
											</svg>
										</div>
									</a>
								</div>
                                <?php endif; ?>
								
                                <?php
									if (
                                    ($block_2_label = get_post_meta($front_page, '_front_page_block_2_label', true)) &&
                                    ($block_2_title = get_post_meta($front_page, '_front_page_block_2_title', true)) &&
                                    ($block_2_link = get_post_meta($front_page, '_front_page_block_2_link', true))
									) :
									$block_2_link = (is_user_logged_in()) ? $block_2_link : site_url('/login');
								?>
								<div class="col-md-6 col-sm-12 col-12">
									<a title="Create a seller profile" href="<?php echo $block_2_link; ?>" class="d-flex property-inner-btn align-items-center">
										<div class="lnk-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
												<g fill="none" fill-rule="evenodd">
													<g fill="#FE7400" fill-rule="nonzero">
														<path d="M380.625 940.994h-21.25c-5.17 0-9.375-4.205-9.375-9.375v-13.146c0-2.906 1.38-5.692 3.69-7.454l10.625-8.104c3.347-2.553 8.023-2.553 11.37 0l3.377 2.573v-1.994c.083-2.073 3.044-2.071 3.125 0v5.15c0 .593-.336 1.136-.869 1.4-.532.263-1.168.202-1.64-.158l-5.887-4.486c-2.232-1.702-5.35-1.702-7.581 0l-10.625 8.104c-1.54 1.174-2.46 3.032-2.46 4.969v13.146c0 3.446 2.804 6.25 6.25 6.25h21.25c3.446 0 6.25-2.804 6.25-6.25v-13.146c0-1.96-.91-3.825-2.432-4.986-.686-.524-.818-1.504-.295-2.19.524-.687 1.504-.819 2.19-.295 2.293 1.749 3.662 4.542 3.662 7.471v13.146c0 5.17-4.206 9.375-9.375 9.375zm-13.75-21.953c-1.079 0-1.953.875-1.953 1.953.103 2.592 3.804 2.59 3.906 0 0-1.079-.874-1.953-1.953-1.953zm8.203 1.953c-.103 2.592-3.804 2.59-3.906 0 .103-2.591 3.804-2.59 3.906 0zm-6.25 6.25c-.103 2.592-3.804 2.59-3.906 0 .103-2.591 3.804-2.59 3.906 0zm6.25 0c-.103 2.592-3.804 2.59-3.906 0 .103-2.591 3.804-2.59 3.906 0z" transform="translate(-350 -901)"></path>
													</g>
												</g>
											</svg>
											
										</div>
										
										<div class="w-100 lnk-cnt">
											<?php echo $block_2_label; ?>
											<span class="d-block"><?php echo $block_2_title; ?></span>
										</div>
										
										<div class="lnk-arrow">
											<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
												<g fill="none" fill-rule="evenodd">
													<g>
														<g>
															<g>
																<path d="M36 0L0 0 0 36 36 36z" transform="translate(-1392 -1340) translate(0 1012) translate(1392 328)"></path>
																<path fill="#FE7400" d="M19.795 31.108c-.42.458-.388 1.17.07 1.589.459.42 1.17.388 1.59-.07L34.16 18.749c.394-.43.394-1.09 0-1.52L21.454 3.365c-.42-.458-1.131-.49-1.59-.07-.457.42-.488 1.132-.068 1.59L31.805 17.99l-12.01 13.118z" transform="translate(-1392 -1340) translate(0 1012) translate(1392 328)"></path>
															</g>
														</g>
													</g>
												</g>
											</svg>
										</div>
									</a>
								</div>
                                <?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	
	
	
	
	<div class="col-sm-12 col-12 custom-banner-card custom-banner-fomr">
		<div class="container">
			<div class="title-area">
				<h3>Start your Search</h3>
			</div>
			<div class="card ms-auto custom-banner-tabs">
				<div class="card-body">
					<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
						<li class="label-li">I want to</li>
						<li class="nav-item" role="presentation">
							<button title="Buy Property" class="nav-link active" id="invest-tab" data-bs-toggle="pill" data-bs-target="#invest" type="button" role="tab" aria-controls="invest" aria-selected="true">Buy</button>
						</li>
						
						<li class="nav-item" role="presentation">
							<button title="Sell Property" class="nav-link" id="sell-tab" data-bs-toggle="pill" data-bs-target="#sell" type="button" role="tab" aria-controls="sell" aria-selected="false">Sell</button>
						</li>
					</ul>
					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="invest" role="tabpanel" aria-labelledby="sell-tab">
							<form action="<?php echo home_url('/' . CO_OWNER_PROPERTY_SEARCH_PAGE); ?>" method="get" id="front-page-property-filter-box">
								<div class="row">
									<div class="col-sm-4 mt-3">
										<label for="iam" class="form-label">I am</label>
										<div class="w-100 custom-select">
											<select class="form-select single-select2" data-search="false" id="iam" aria-label="Default select example">
												<option value="Individual">Individual</option>
												<option value="Agent">Agent</option>
											</select>
										</div>
									</div>
									
									<div class="col-sm-4 mt-3">
										<label for="my-budget" class="form-label">My Budget</label>
										<div class="w-100 custom-select">
											<select name="p_price" class="form-control single-select2" data-search="false">
												<option value="">Price</option>
												<?php foreach (get_price_dropdown_options() as $p_value => $p_key) : ?>
												<option value="<?php echo $p_value; ?>"><?php echo $p_key; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<label id="p_price-error" class="text-error" for="p_price"></label>
									</div>
									
									<div class="col-sm-4 mt-3">
										<label for="location" class="form-label">State</label>
										<div class="w-100 custom-select">
											<select name="p_state" class="form-control single-select2" id="location" aria-label="Default select example">
												<option value="">All</option>
												<?php foreach (get_all_states() as $value => $name) : ?>
												<option value="<?php echo $value; ?>"><?php echo $name; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<label id="location-error" class="text-error" for="location"></label>
									</div>
									
									<div class="col-sm-12 col-12 text-center mt-4">
										<button  title="See results" type="submit" class="btn btn-orange btn-rounded">Search</button>
									</div>
								</div>
							</form>
						</div>
						
						<div class="tab-pane fade" id="sell" role="tabpanel" aria-labelledby="sell-tab">
							<form action="<?php echo home_url('/' . CO_OWNER_PEOPLE_LIST_PAGE); ?>" method="get" id="front-page-people-filter-box">
								<div class="row max-h-392px">
									<div class="col-sm-4 mt-3">
										<label for="iam1" class="form-label">I am</label>
										<div class="w-100 custom-select">
											<select class="form-select single-select2" data-search="false" id="iam1" aria-label="Default select example">
												<option value="Individual">Individual</option>
												<option value="Agent">Agent</option>
											</select>
										</div>
									</div>
									
									<div class="col-sm-4 mt-3">
										<label for="property-value" class="form-label">Property Value</label>
										<input name="p_budget" type="text" class="form-control property-value-input input-only-price" data-property-share-input=".property-share-input" data-calculated-value-input=".calculated-value-input" id="property-value" placeholder="$">
									</div>
									
									<div class="col-sm-4 mt-3">
										<label for="user-selling" class="form-label">Selling (Full Or Portion)</label>
										<div class="w-100 custom-select">
											<select class="form-select single-select2" data-search="false" id="user-selling" aria-label="Default select example">
												<option value="full_property">Full Property</option>
												<option value="share">Portion</option>
											</select>
										</div>
									</div>
									
									<div class="col-sm-4 full-property-input  mt-3">
										<label for="share" class="form-label">Portion %</label>
										<div class="w-100 custom-select">
											<select class="form-select single-select2 property-share-input" data-property-value-input=".property-value-input" data-calculated-value-input=".calculated-value-input" id="share" aria-label="Default select example">
												<option selected>Selected value</option>
												<?php for ($i = 1; $i <= 100; $i++) : ?>
												<option value="<?php echo $i; ?>"><?php echo $i; ?>%</option>
												<?php endfor; ?>
											</select>
										</div>
									</div>
									
									<div class="col-sm-4 full-property-input  mt-3">
										<label for="calculated-value" class="form-label">Calculated value</label>
										<input type="text" class="form-control calculated-value-input" id="calculated-value" placeholder="$">
									</div>
									
									<div class="col-md-4 mt-3">
										<label for="location2" class="form-label">Location</label>
										<div class="w-100 custom-select">
											<select name="location" class="form-control single-select2" id="location2" aria-label="Default select example">
												<option value="">All</option>
												<?php foreach (get_all_states() as $value => $name) : ?>
												<option value="<?php echo $value; ?>"><?php echo $name; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<label id="location2-error" class="text-error" for="location2"></label>
									</div>
									
									<div class="col-md-12 col-12 text-center mt-3">
										<button title="See results" type="submit" class="btn btn-orange btn-rounded">Search</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
	
    <!--  FRONT PAGE CENTER CONTENT  -->
	
	<!--  FRONT PAGE PEOPLE LOOKING FOR PROPERTY  -->
    <?php if (get_post_meta($front_page, '_front_page_show_people_looking_for_properties', true) == 'yes') : ?>
	<div class="main-section bg-white w-100 d-block py-40px">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 inner-section-grey">
					<div class="title-area">
						<h3 class="d-flex">
							<?php echo get_post_meta($front_page, '_front_page_people_looking_for_properties_title', true); ?>
							<?php if ($link = get_post_meta($front_page, '_front_page_people_looking_for_properties_link', true)) : ?>
							<a title="All buyers" href="<?php echo $link; ?>" class="d-md-block d-none ms-auto text-nowrap">View All</a>
							<?php endif; ?>
						</h3>
						<?php echo get_post_meta($front_page, '_front_page_people_looking_for_properties_description', true); ?>
					</div>
					<div class="owl-carousel owl-theme property-two people-looking-for-properties">
						<?php
                            foreach (get_people_looking_for_properties() as $user) :
							$user_property_category = get_user_meta($user->ID, '_user_property_category', true);
							$property_category = empty($user_property_category) ? array() : $user_property_category;
							$user_preferred_location = get_user_meta($user->ID, '_user_preferred_location', true);
							$preferred_location = empty($user_preferred_location) ? array() : $user_preferred_location;
							$budget_range = get_user_meta($user->ID, '_user_budget_range', true);
							// $budget = get_user_budget($user->ID);
							$is_liked = get_people_is_liked($user->ID);
						?>
						<div class="h-100">
							<div class="card property-card-one h-100">
								<div class="card-body">
									<div class="property-one-thumb">
										<div class="property-thumb-top d-flex align-items-center">
											<?php foreach ($property_category as $category) : ?>
											<a href="#" title="<?php echo $category != 'commercial' ? 'Intended for private occupancy' : 'Investment Property'; ?>" class="btn btn-<?php echo $category != 'commercial' ? 'orange' : 'primary'; ?> rounded-pill me-1">
												<?php echo $category; ?>
											</a>
											<?php endforeach; ?>
											<a title="Favorite/Shortlist" href="#" data-id="<?php echo $user->ID; ?>" class="btn btn-favourite ms-auto people <?php echo $is_liked ? 'active make-property-dislike' : 'make-property-like' ?>"></a>
										</div>
										<a href="<?php echo CO_OWNER_PERSON_DETAILS_PAGE . '?id=' . $user->ID; ?>">
											<div class="property-thumb-bottom">
												<div class="d-flex align-items-end">
													<span class="small-title">Budget</span>
													<h4 class="ms-auto">
														<?php echo price_range_show($budget_range); ?>
													</h4>
												</div>
											</div>
											<img src="<?php echo esc_url(get_avatar_url($user->ID));  ?>" class="img-fluid" alt="">
										</a>
									</div>
									
									<div class="property-detail-area">
										<a href="<?php echo CO_OWNER_PERSON_DETAILS_PAGE . '?id=' . $user->ID; ?>">
											<h6>
												<?php echo get_user_full_name($user->ID); ?>
												<?php if (get_user_shield_status($user->ID)) {
													echo "<span class='user-shield-tooltip'>" . co_owner_get_svg('shield') . "</span>";
												} ?>
											</h6>
										</a>
										<?php if (count($preferred_location) > 0) : ?>
										<div class="property-detail-cnt">
											<p>Preferred Location(s):</p>
											<?php foreach ($preferred_location as $key => $location) : ?>
											<?php if ($key < 5) : ?>
											<span class="badge bg-light-grey rounded-pill"><?php echo get_state_full_name($location); ?></span>
											<?php endif; ?>
											<?php endforeach; ?>
										</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
						<div class="h-100 h-100-lastitem">
						<?php if ($link = get_post_meta($front_page, '_front_page_people_looking_for_properties_link', true)) : ?>
							<a href="<?php echo $link; ?>" class="d-md-block d-none ms-auto text-nowrap">View All</a>
							<?php endif; ?>	
						</div>
					</div>
					
					<?php if ($link = get_post_meta($front_page, '_front_page_people_looking_for_properties_link', true)) : ?>
					<div class="pb-0 title-area">
						<h3 class="text-center p-0">
							<a href="<?php echo $link; ?>" class="d-md-none ms-auto">View All</a>
						</h3>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
    <?php endif; ?>
    <!--  FRONT PAGE PEOPLE LOOKING FOR PROPERTY  -->
	
    
	
	
	
    <!--  CHECKOUT THE POOLS ALREADY CREATED  -->
    <?php if (get_post_meta($front_page, '_front_page_show_pools_already_created', true) == 'yes') : ?>
	<div class="main-section bg-black w-100 d-block py-40px">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 inner-section-grey">
					<div class="title-area">
						<h3 class="d-flex">
							<?php echo get_post_meta($front_page, '_front_page_pools_already_created_title', true); ?>
							<?php if ($link = get_post_meta($front_page, '_front_page_pools_already_created_link', true)) : ?>
							<a title="See all property pools" href="<?php echo $link; ?>" class="d-md-block d-none ms-auto text-nowrap">View All</a>
							<?php endif; ?>
						</h3>
						<?php echo get_post_meta($front_page, '_front_page_pools_already_created_description', true); ?>
					</div>
					
					<div class="owl-carousel owl-theme property-three checkout-the-pools-already-created">
						<?php
                            foreach (get_checkout_the_pools_already_created() as $pool_property) :
							$property_category = get_post_meta($pool_property->ID, '_pl_property_category', true);
							$posted_by = get_post_meta($pool_property->ID, '_pl_posted_by', true);
							$title = ucfirst($pool_property->post_title);
							$image_url = get_property_first_image($pool_property->ID);
							$interested_in_selling = get_post_meta($pool_property->ID, '_pl_interested_in_selling', true);
							$market_price = get_post_meta($pool_property->ID, '_pl_property_original_price', true);
							$bathroom = get_post_meta($pool_property->ID, '_pl_bathroom', true);
							$bedroom = get_post_meta($pool_property->ID, '_pl_bedroom', true);
							$parking = get_post_meta($pool_property->ID, '_pl_parking', true);
							$is_liked = get_property_is_liked($pool_property->ID);
							$available_share = get_property_available_share($pool_property->ID);
							$members = get_property_total_members($pool_property->ID);
						?>
						<div class="h-100">
							<div class="card property-card-one h-100">
								<div class="card-body">
									<div class="property-one-thumb">
										<div class="property-thumb-top d-flex align-items-center">
											<?php echo co_owner_get_svg('enable_pool'); ?>
											
											<a href="#" title="<?php echo $property_category != 'commercial' ? 'Intended for private occupancy' : 'Investment Property'; ?>" class="btn btn-<?php echo $property_category == 'residential' ? 'orange' : 'blue'; ?> rounded-pill ms-1 me-1"><?php echo $property_category; ?></a>
											<a href="#" title="<?php echo $posted_by != 'Agent' ? 'Leased by Proprietor' : 'Leased by an Agent'; ?>" class="btn btn-orange-outline rounded-pill">Posted by: <?php echo $posted_by; ?></a>
											
											<a  title="Favorite/Shortlist" href="#" data-id="<?php echo $pool_property->ID; ?>" class="btn btn-favourite ms-auto <?php echo $is_liked ? 'active make-property-dislike' : 'make-property-like' ?>"></a>
										</div>
										<div class="property-thumb-bottom">
											<div class="property-mbr">
												<?php if (count($members) > 0) : ?>
												<?php if(count($members) == 1){ ?>
												<span class="mbr-tite"><?php echo count($members); ?> Member</span>
												<?php }else{ ?>
												<span class="mbr-tite"><?php echo count($members); ?> Members</span>
												
												<?php } endif; ?>
												<div class="pt-2 d-flex">
													<?php foreach ($members as $key => $member) : ?>
													<?php if ($key <= 2) : ?>
													<div class="mbr-list d-flex align-items-center pe-3">
														<div class="mbr-photo">
															<img src="<?php echo get_avatar_url($member->id) ?>" alt="" class="w-100">
														</div>
														<div class="mbr-hold d-sm-inline-block d-none">
															<?php if (get_user_shield_status($member->id)) {
																echo "<span class='user-shield-tooltip'>" . co_owner_get_svg('shield') . "</span>";
															} ?>
															<?php if ($member->is_admin && $interested_in_selling == 'full_property') : ?>
															Admin
															<?php elseif ($member->is_admin && $interested_in_selling == 'portion_of_it') : ?>
															Admin holds <?php echo get_admin_hold_pr($pool_property->ID); ?>%
															<?php else : ?>
															holds <?php echo $member->interested_in; ?>%
															<?php endif; ?>
														</div>
													</div>
													<?php endif; ?>
													<?php endforeach; ?>
													<?php echo count($members) > 3 ? "<span class='align-content-center d-grid small-title'>+ " . (count($members) - 3) . " More</span>" : ''; ?>
												</div>
											</div>
											<div class="d-flex align-items-end">
												<?php if ($available_share > 0) : ?>
												<span class="small-title">Available <?php echo $available_share; ?>%</span>
												<h4 class="ms-auto"><?php echo CO_OWNER_CURRENCY_SYMBOL ?> <?php echo get_property_price_for_display($pool_property->ID); ?></h4>
												<?php endif; ?>
											</div>
										</div>
										<a href="<?php  echo get_the_permalink($pool_property->ID); /* CO_OWNER_PROPERTY_DETAILS_PAGE . '/?id=' . $pool_property->ID; */ ?>">
											<img src="<?php echo $image_url; ?>" class="img-fluid" alt="">
										</a>
									</div>
									
									<div class="property-detail-area">
										<div class="d-flex align-items-center">
											<a href="<?php echo get_the_permalink($pool_property->ID); /*CO_OWNER_PROPERTY_DETAILS_PAGE . '/?id=' . $pool_property->ID; */ ?>">
												<h6>
													<span class="d-block text-truncate" style="max-width: 90%;"># <?php echo $title; ?></span>
													Location: <?php echo get_property_full_address($pool_property->ID); ?>
												</h6>
											</a>
										</div>
										
										<div class="property-facility-area d-flex align-items-center">
											<?php if ($property_category == 'residential' && ($bathroom || $bedroom || $parking)) : ?>
											<a title="Bedroom">
												<?php echo co_owner_get_svg('bedroom'); ?>
												<span><?php echo $bedroom; ?></span>
											</a>
											
											<a title="Bathroom">
												<?php echo co_owner_get_svg('bathroom'); ?>
												<span><?php echo $bathroom; ?></span>
											</a>
											
											<a title="Parking">
												<?php echo co_owner_get_svg('parking'); ?>
												<span><?php echo $parking; ?></span>
											</a>
											<?php 
											$comments_count = wp_count_comments( $pool_property->ID );
											?>
											<a class="comment-icons like-icons" href="<?php  echo get_the_permalink($pool_property->ID); /*CO_OWNER_PROPERTY_DETAILS_PAGE . '/?id=' . $pool_property->ID;*/ ?>">

												<?php echo getTotalVoteByPostId($pool_property->ID); ?>
											</a>
											<a class="comment-icons" href="<?php echo get_the_permalink($pool_property->ID); /*CO_OWNER_PROPERTY_DETAILS_PAGE . '/?id=' . $pool_property->ID; */ ?>">
												<span class="sbs-count-comments"><?php echo $comments_count->total_comments; /*do_shortcode( '[sbs_comments]' ); */ ?> </span> 
								
											</a>
											<!--<a class="comment-icons toggle_property_comment" href="javascript:void(0);">
												<span class="sbs-count-comments">Comments</span> 
								
											</a>-->

											<?php endif; ?>
											<?php if (get_user_status($auth_user_id) != 1) : ?>
											<a class="btn btn-orange rounded-pill ms-auto min-w-120px" href="<?php echo home_url(CO_OWNER_MY_ACCOUNT_PAGE) . '?alert=your_account_is_inactive'; ?>">Join the Pool</a>
											<?php elseif ($available_share > 0) : ?>
											<a href="<?php echo ($pool_property->post_author == $auth_user_id || ($auth_user && in_array('administrator', $auth_user->roles))) ? '#' : (home_url(CO_OWNER_PROPERTY_DETAILS_PAGE . "?id={$pool_property->ID}&action=open_property_connection_modal")); ?>" class="btn btn-orange rounded-pill ms-auto min-w-120px <?php echo $pool_property->post_author == $auth_user_id ? 'is-your-property-alert' : null; ?>">Join the Pool</a>
											<?php else : ?>
											<a href="#" class="btn fw ms-auto text-danger pb-0">POOL IS FULL</a>
											<?php endif; ?>
											<ul class="comment_hover" style="display:none;">
											<?php

											//display comments
											wp_list_comments( array(
											'style'      => 'ul',
											'short_ping' => true,
											'callback' => 'better_comments'
											), $comments);?>						
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
					
					<?php if ($link = get_post_meta($front_page, '_front_page_pools_already_created_link', true)) : ?>
					<div class="pb-0 title-area">
						<h3 class="text-center p-0">
							<a href="<?php echo $link; ?>" class="d-md-none ms-auto">View All</a>
						</h3>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
    <?php endif; ?>
    <!--  CHECKOUT THE POOLS ALREADY CREATED  -->
	
	
	<!-- FRONT PAGE PROPERTY NEED CO-OWNERS -->
    <?php if (get_post_meta($front_page, '_front_page_show_need_co_owners', true) == 'yes') : ?>
	<div class="main-section bg-grey w-100 d-block py-40px">
		<div class="container">
			<div class="row">
				<div class="col col-sm-12 inner-section-grey">
					<div class="title-area">
						<h3 class="d-flex">
							<?php echo get_post_meta($front_page, '_front_page_need_co_owners_title', true); ?>
							<?php if ($link = get_post_meta($front_page, '_front_page_need_co_owners_link', true)) : ?>
							<a title="See all properties" href="<?php echo $link; ?>" class="d-md-block d-none ms-auto text-nowrap">View All</a>
							<?php endif; ?>
						</h3>
						<?php echo get_post_meta($front_page, '_front_page_need_co_owners_description', true); ?>
					</div>
					<div class="owl-carousel owl-theme property-one properties-need-co-owners">
						<?php
                            foreach (get_properties_need_co_owners() as $key => $property_co_owner) :
							$property_category = get_post_meta($property_co_owner->ID, '_pl_property_category', true);
							$posted_by = get_post_meta($property_co_owner->ID, '_pl_posted_by', true);
							
							$title = ucfirst($property_co_owner->post_title);
							$images = get_post_meta($property_co_owner->ID, '_pl_images', true);
							$image_url = (isset($images[0]) && isset($images[0]['url'])) ? $images[0]['url'] :  get_template_directory_uri() . '/images/property-1.jpg';
							$market_price = get_post_meta($property_co_owner->ID, '_pl_property_original_price', true);
							$interested_in_selling = get_post_meta($property_co_owner->ID, '_pl_interested_in_selling', true);
							$i_want_to_sell = $interested_in_selling !== 'full_property' ? get_post_meta($property_co_owner->ID, '_pl_i_want_to_sell', true) : null;
							
							$bathroom = get_post_meta($property_co_owner->ID, '_pl_bathroom', true);
							$bedroom = get_post_meta($property_co_owner->ID, '_pl_bedroom', true);
							$parking = get_post_meta($property_co_owner->ID, '_pl_parking', true);
							$is_liked = get_property_is_liked($property_co_owner->ID);
						?>
						<div class="h-100">
							<div class="card property-card-one h-100">
								<div class="card-body">
									<div class="property-one-thumb">
										<div class="property-thumb-top d-flex align-items-center">
											<a title="<?php echo $property_category != 'residential' ? 'Investment Property' : 'Intended for private occupancy'; ?>" href="#" class="btn btn-<?php echo $property_category == 'residential' ? 'orange' : 'blue'; ?> rounded-pill me-1"><?php echo $property_category; ?></a>
											<a title="<?php echo $posted_by != 'Agent' ? 'Leased by Proprietor' : 'Leased by an Agent'; ?>" href="#" class="btn btn-orange-outline rounded-pill">Posted by: <?php echo $posted_by; ?></a>
											<a  title="Favorite/Shortlist" href="#" data-id="<?php echo $property_co_owner->ID; ?>" class="btn btn-favourite ms-auto <?php echo $is_liked ? 'active make-property-dislike' : 'make-property-like' ?>"></a>
										</div>
										<a href="<?php echo get_the_permalink($property_co_owner->ID); /* CO_OWNER_PROPERTY_DETAILS_PAGE . '/?id=' . $property_co_owner->ID; */ ?>">
											<div class="property-thumb-bottom">
												<div class="d-flex align-items-end">
													<span class="small-title">Selling Price</span>
													<h4 class="ms-auto"><?php echo CO_OWNER_CURRENCY_SYMBOL . ' ' . get_property_price_for_display($property_co_owner->ID) ?></h4>
												</div>
											</div>
											<img src="<?php echo $image_url; ?>" class="img-fluid" alt="">
										</a>
									</div>
									
									<div class="property-detail-area">
										<h6>
											<span class="d-block">
												Owner wants to sell: <?php echo $i_want_to_sell ? $i_want_to_sell . '%' : 'Full Property'; ?>
											</span>
											<?php echo $title; ?>
										</h6>
										
										<?php if ($property_category == 'residential') : ?>
										<div class="property-facility-area">
											<a title="Bedroom">
												<?php echo co_owner_get_svg('bedroom'); ?>
												<span><?php echo $bedroom; ?></span>
											</a>
											<a title="Bathroom">
												<?php echo co_owner_get_svg('bathroom'); ?>
												<span><?php echo $bathroom; ?></span>
											</a> 
											<a title="Parking">
												<?php echo co_owner_get_svg('parking'); ?>
												<span><?php echo $parking; ?></span>
											</a>
											<?php 
											 $comments_count = wp_count_comments( $property_co_owner->ID );
											 
											?>
											<a class="comment-icons like-icons" href="<?php echo get_the_permalink($property_co_owner->ID); /*CO_OWNER_PROPERTY_DETAILS_PAGE . '/?id=' . $property_co_owner->ID; */ ?>">
												<?php echo getTotalVoteByPostId($property_co_owner->ID); ?>
											</a> 
											<a class="comment-icons" href="<?php echo get_the_permalink($property_co_owner->ID);  /*CO_OWNER_PROPERTY_DETAILS_PAGE . '/?id=' . $property_co_owner->ID; */ ?>">
												<span class="sbs-count-comments"><?php echo $comments_count->total_comments; /*do_shortcode( '[sbs_comments]' );*/ ?> </span> 
											</a>
										</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
						
						<div class="h-100 h-100-lastitem">
						<?php if ($link = get_post_meta($front_page, '_front_page_need_co_owners_link', true)) : ?>
							<a href="<?php echo $link; ?>" class="d-md-block d-none ms-auto text-nowrap">View All</a>
							<?php endif; ?>	
						</div>
						
					</div>
					
					<?php if ($link = get_post_meta($front_page, '_front_page_need_co_owners_link', true)) : ?>
					<div class="pb-0 title-area">
						<h3 class="text-center p-0">
							<a href="<?php echo $link; ?>" class="d-md-none ms-auto">View All</a>
						</h3>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
    <?php endif; ?>
    <!-- FRONT PAGE PROPERTY NEED CO-OWNERS -->
	
	<!--start how it work -->
	<div id="how-id" class="main-section bg-grey w-100 d-block py-40px how-work-it">
		<div class="container">
			<div class="title-area"><h3>How it works</h3></div>
			
			<div class="row step-row">
                <div class="col-sm-2">
                    <div class="works-steps">
                        <div class="step-top-data no-data"></div>
                        <div class="step-number"><strong>Step 1</strong> Sign Up</div>
                        <div class="normal-step ">
							<p>Create an account</p>
						</div>
					</div>
				</div>
				
                <div class="step-arow"></div>
				
                <div class="col-sm-2">
                    <div class="works-steps">
                        <div class="step-top-data">
                            <p><strong>If you’re a Buyer:</strong><br> Create a Buyer Profile</p>
						</div>
                        <div class="step-number"><strong>Step 2</strong> Listings</div>
                        <div class="step-btm-data">
							<p><strong> <?php echo "If you're a Seller:" ?></strong><br> Create a Property Listing </p>
						</div>
					</div>
				</div>
				
				
				<div class="step-arow"></div>
				
                <div class="col-sm-2">
                    <div class="works-steps">
                        <div class="step-top-data">
                            <p><strong>If you’re a Buyer:</strong><br> Search for available properties to buy, or look for other investors to buy a property with</p>
						</div>
                        <div class="step-number"><strong>Step 3</strong> Search</div>
                        <div class="step-btm-data">
							<p><strong>If you’re a Seller:</strong><br> Search for Potential Buyers</p>
						</div>
					</div>
				</div>
				
				
                <div class="step-arow"></div>
				
                <div class="col-sm-2">
                    <div class="works-steps">
                        <div class="step-top-data">
                            <p>Connect with <br>members and use the chat functionality to start a conversation</p>
						</div>
                        <div class="step-number"><strong>Step 4</strong> Chat</div>
						
					</div>
				</div>
				
				
				<div class="step-arow"></div>
				
                <div class="col-sm-2">
                    <div class="works-steps">
                        <div class="step-top-data no-data"></div>
                        <div class="step-number"><strong>Step 5</strong> Purchase/Sell</div>
                        <div class="step-btm-data">
							<p>Once you’ve found your new co-owners or buyers, begin the property purchase or sale process</p>
						</div>
					</div>
				</div>
				
				
				
				
				
				
			</div>    
		</div>
	</div>
	
	<!--end how it work -->
	
	
	<!--  our community -->
	
	<div id="our-community" class="main-section w-100 d-block py-40px bg-white our-comm">
		<div class="container">
			<div class="title-area"><h3>Our Community</h3></div>
			
			<?php 
			
				$args = array(  
				'post_type' => 'community',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'order' => 'DESC', 
				);

				$lawyerObj = new WP_Query( $args ); 


			
			
			?>
			
			<div class="owl-carousel owl-theme our-community-owlslider">
			<?php 
			
			  while ( $lawyerObj->have_posts() ) : $lawyerObj->the_post(); 	
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
			  //pr($lawyer_logo);
			
			?>
                <div class="item-lawyer">
                    <div class="commnunity-inner">
					    <?php if($lawyer_logo):?>
                        <div class="logo-comminuty">
						<a href="<?php the_permalink();?>">
						<img src="<?php echo $lawyer_logo['url'];?>">
						</a>
						</div>
						<?php endif; ?>
						<div class="title-area">
						<a href="<?php the_permalink();?>">
						<h3 class="d-flex align-items-center"><span><?php the_title(); ?></span></h3>
						</a>
					    </div>
						<?php 
						$contentline = "threelinecontent";	
						if(!$book_consultation_fee && !$enquiry_on_off ){
							$contentline = "sixlinecontent";
						}elseif(!$book_consultation_fee || !$enquiry_on_off){
						$contentline = "fivelinecontent";
						}
							
						?>
                        <div class="logo-comminuty-excerp <?php echo $contentline; ?>"><?php echo $short_description; ?></div>
						
                        <?php if($google_rating_image):?>
                        <!--<div class="g-review">
							<img src="<?php echo $google_rating_image['url'];?>">
							
						</div>-->
						<?php endif;?>
						
						<?php if($hilighted_points): ?>
						<?php //echo $hilighted_points; ?>
						<?php endif; ?>
						
						<?php if($book_consultation_fee):?>
                        <div class="book-consult book-consult-first">
                            <span><p>Book Consultation</p></span>
                            <span class="consult-price"><?php echo $book_consultation_fee; ?></span>
						</div>
						<?php endif; ?>
						
						<?php if($enquiry_on_off) :?>
                        <div class="book-consult book-consult-last">
                            <span><p><?php echo $inquiry_label; ?></p></span>
							
								<span class="enquirey" lawyer_id="<?php echo get_the_ID(); ?>"><a href="#" data-bs-toggle="modal" >Enquire Now <img class="load-custom"  src="<?php echo get_template_directory_uri(); ?>/images/loading-buffering.gif"></a></span>
								
						</div>
						<?php endif; ?>
						
                        <div class="action-btn-row">
                           <!-- <a title="Sutton  Laurence King Lawyers Website" class="btn btn-dark rounded-pill ms-auto" target="_blank" href="https://slklawyers.com.au/" >Website</a> -->
                            <?php if($website_address): ?>
							<a title="<?php the_title(); ?>" class="btn btn-dark rounded-pill ms-auto" target="_blank" href="<?php echo $website_address; ?>" >Website</a>
							<?php endif; ?>
							
							<?php if($book_consultation):?>
							
							<a  class="btn btn-orange rounded-pill ms-auto" href="/booking-process/?book_id=<?php echo get_the_ID(); ?>" >Book Consultation</a>
							<?php endif; ?>
							
							<?php //echo do_shortcode('[accept_stripe_payment name="Payments (powered by Stripe). This is a 60 mins consultation with our law firm. You can discuss anything in this call." price="250" url="http://example.com/downloads/my-script.zip" button_text="Book Consultation"]'); ?>
						</div>
						
					</div>
				</div>
				
				<?php 	endwhile;

				wp_reset_postdata(); ?>
				<div class="item-lawyer">
				<div class="item-lawyer-viewall">	
				<a href="<?php echo home_url('/community');?>" class="d-md-block d-none ms-auto text-nowrap">View All</a>
				</div>	
				</div>
				
				
			</div>
			
		</div>
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
                                    <div class="require-fielderor lastname">This is a required field</div>
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
	<!-- end our community -->
	
	
	
	
    <!--  WHY CHOOSE US -->
    <div class="main-section bg-custom-one w-100 d-block py-40px hide-all">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 inner-section-grey">
                    <div class="title-area">
                        <h3>Why choose us?</h3>
					<h5 class="pb-3 text-uppercase">If you’re a buyer</strong></h5>
					
					<p class="pb-3">If you’re interested in buying property, the three most popular ways to do this are:</p>
					
					<p class="pb-3">
						<span class="is-small orange-circle"></span>
						Purchasing property directly (Direct Real Estate Investing)<br>
						<span class="is-small orange-circle"></span>
						Purchasing property through a Real Estate Investment Trust (REIT)<br>
						<span class="is-small orange-circle"></span>
						Using Property Mates to connect with like-minded investors to buy property together.
					</p>
					
					<p class="pb-5">Buying a property with other co-owners helps you get on the property ladder faster, but with less risk as you decide the budget that you’re comfortable spending. No need to take out a large home loan that will take the next 30 years to pay off, or have to buy in a less desirable area. With Property Mates, you can buy a portion of a quality property – from a 1% stake to 100% ownership – and start earning rental income straight away.</p>
					
					<h5 class="pb-3 text-uppercase">If you’re a seller</h5>
					
					<p class="pb-5">If you already own a property, then Property Mates gives you more flexibility when it comes time to sell. Property Mates is the only platform in Australia which enables property owners to list a portion of a property for sale, connecting the seller with potential buyers who are interested in co-owning the property. Or, if you want to sell the entire property, Property Mates enables you to do that as well by connecting you with an individual buyer. It really depends on your financial and lifestyle goals.</p>
					<p>The below table compares the five main methods of buying and selling property in Australia:</p>
				</div>
				
				
				
				<div class="owl-carousel owl-theme why-choose-us">
					<div>
						<div class="section-box-main">
							<div class="card">
								<div class="card-body">
									<div class="box-title">
										<h3 class="text-uppercase">Purchasing</h3>
									</div>
									<div class="box-part-main">
										<h4>Direct Real Estate Investing</h4>
										<p>With a direct real estate investment, you buy a specific property</p>
									</div>
									
									<div class="box-part-main">
										<h4>Real Estate Investment Trust (REIT)</h4>
										<p>REITs are actively managed and pool together investors’ money to invest in properties</p>
									</div>
									
									<div class="box-part-main bg-cream">
										<h4>Property Mates</h4>
										<p>With Property Mates you can buy or sell a portion of a property – from a 1% stake to 100% ownership</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div>
						<div class="section-box-main">
							<div class="card">
								<div class="card-body">
									<div class="box-title">
										<h3 class="text-uppercase">Control</h3>
									</div>
									<div class="box-part-main">
										<h4>Direct Real Estate Investing</h4>
										<p>Your choice of property is determined by your budget</p>
									</div>
									
									<div class="box-part-main">
										<h4>Real Estate Investment Trust (REIT)</h4>
										<p>No control over which property the trust buys</p>
									</div>
									
									<div class="box-part-main bg-cream">
										<h4>Property Mates</h4>
										<p>You invest in your own choice of property</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div>
						<div class="section-box-main">
							<div class="card">
								<div class="card-body">
									<div class="box-title">
										<h3 class="text-uppercase">ROI & Risk</h3>
									</div>
									<div class="box-part-main">
										<h4>Direct Real Estate Investing</h4>
										<p>Slow ROI with more risk</p>
									</div>
									
									<div class="box-part-main">
										<h4>Real Estate Investment Trust (REIT)</h4>
										<p>Fast but less ROI with less risk</p>
									</div>
									
									<div class="box-part-main bg-cream">
										<h4>Property Mates</h4>
										<p>Flexible ROI with less risk based on your investment</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div>
						<div class="section-box-main">
							<div class="card">
								<div class="card-body">
									<div class="box-title">
										<h3 class="text-uppercase">Finance</h3>
									</div>
									<div class="box-part-main">
										<h4>Direct Real Estate Investing</h4>
										<p>Risk of financing default</p>
									</div>
									
									<div class="box-part-main">
										<h4>Real Estate Investment Trust (REIT)</h4>
										<p>Sensitive to interest rate fluctuations</p>
									</div>
									
									<div class="box-part-main bg-cream">
										<h4>Property Mates</h4>
										<p>No need for finance as you can invest based on your savings</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div>
						<div class="section-box-main">
							<div class="card">
								<div class="card-body">
									<div class="box-title">
										<h3 class="text-uppercase">Revenue</h3>
									</div>
									<div class="box-part-main">
										<h4>Direct Real Estate Investing</h4>
										<p>Main revenue through rental income, equity and appreciation of property</p>
									</div>
									
									<div class="box-part-main">
										<h4>Real Estate Investment Trust (REIT)</h4>
										<p>Main revenue through dividends</p>
									</div>
									
									<div class="box-part-main bg-cream">
										<h4>Property Mates</h4>
										<p>Main revenue through rental income, equity and appreciation of property</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--  WHY CHOOSE US -->


<!-- PROPERTY SHARE(S) UNDER -->

<?php if (get_post_meta($front_page, '_front_page_show_property_shares_under', true) == 'yes') : ?>

<div class="main-section bg-white w-100 d-block py-40px show-property-shares-under">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 inner-section-grey">
				<div class="title-area">
					<h3 class="title-select">
						<?php echo get_post_meta($front_page, '_front_page_property_shares_under_title', true) ?>
						<select class="single-select2 property-shares-price" name="p_state" title="Compare Property Prices">
							<option title="Compare Property Prices" value="">Price</option>
							<?php foreach (get_price_dropdown_options() as $p_value => $p_key) : ?>
							<option value="<?php echo $p_value; ?>"><?php echo $p_key; ?></option>
							<?php endforeach; ?>
						</select>
					</h3>
				</div>
				<div class="owl-carousel owl-theme property-four property-shares-sliders">
					<?php foreach (property_shares_under() as $state) : ?>
					<div class="h-100">
						<div class="card property-card-one h-100">
							<div class="card-body">
								<div class="property-one-thumb">
									<img src="<?php echo get_template_directory_uri(); ?>/images/states/<?php echo strtolower($state['state']); ?>.jpeg" class="img-fluid" alt="">
								</div>
								
								<div class="property-detail-area d-flex align-items-start">
									<h6>
										<span class="d-block"><?php echo get_state_full_name($state['state']); ?></span>
										<?php if ($state['count'] > 0) : ?>
										<?php echo $state['count']; ?>+ Properties
										<?php endif; ?>
									</h6>
									<a href="#" class="btn btn-orange rounded-pill ms-auto property-shares-view-property text-nowrap" data-state="<?php echo $state['state']; ?>">View Property</a>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<!-- PROPERTY SHARE(S) UNDER -->

</div>
<script>
function ValidateEmail(email) 
{
    var re = /\S+@\S+\.\S+/;
        return re.test(email);
}


</script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
    $("#invest-tab").click(function(event){ 
		location.reload();
    });
	
 
    
});
</script>
<?php /* #change007  */ 
include('register_modal.php'); ?>
<?php include('parts/modals/croppie.php'); ?>
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
<?php } else{ ?>	
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
	
<?php }


get_footer(); ?>