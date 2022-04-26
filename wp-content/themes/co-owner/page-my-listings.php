<?php

/**
 * Default my account file
 *
 * This file is needed in case you wan't to use this theme for Woocommerce.
 * In favor of the parts structure, this file is constructed with parts.
 * Also this file is NOT used by default
 *
 * Please see /external/bootstrap-utilities.php for info on CoOwner::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Co-Owner
 * @autor 		TechXperts
 */
get_header();
$view = isset($_GET['view']) && in_array($_GET['view'], ['create_by_my', 'joined_pools', 'completed']) ? $_GET['view'] : 'create_by_my';
?>
<div class="center-area">
    <?php include(CO_OWNER_THEME_DIR . '/parts/my-account-page-header.php') ?>

    <div class="main-section bg-white my-listings-main">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12">
                    <?php include(CO_OWNER_THEME_DIR . '/parts/my-account-page-aside.php') ?>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-12 py-40px pt-30px">
                    <div class="row">
                        <div class="col-md-12 my-listings-title">
                            <h4 class="d-flex align-items-center">
                                <span class="pe-2">My Listings</span>
                            </h4>

                            <div class="my-list-links w-100">
                                <ul>
                                    <li class="<?php echo $view == 'create_by_my' ? 'active' : ''; ?>"><a href="<?php echo home_url('my-listings?view=create_by_my') ?>">Created by me</a></li>
                                    <li class="<?php echo $view == 'joined_pools' ? 'active' : ''; ?>"><a href="<?php echo home_url('my-listings?view=joined_pools') ?>">Joined Pools</a></li>
                                    <li class="<?php echo $view == 'completed' ? 'active' : ''; ?>"><a href="<?php echo home_url('my-listings?view=completed') ?>">Completed</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?php if ($view == 'create_by_my') : ?>
                        <h4 class="pt-40px">My Profile</h4>

                        <?php $person = get_person_detail_by_id(get_current_user_id()); ?>
                        <div class="row">
                            <div class="create-person-list-link col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 pt-30px" style="display: <?php echo $person->listing_status == 0 ? 'block;' : 'none;'; ?>">
                                <a href="<?php echo home_url(CO_OWNER_CREATE_A_PERSON_PAGE); ?>" class="btn btn-orange-bordered">Create Buyer Profile</a>
                            </div>
                        </div>

                        <div class="row list-section pt-30px">
                            <?php if ($person->listing_status == 1 || $person->listing_status == 2) : ?>
                                <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 pb-30px person-list-box">
                                    <div class="card property-card-one">
                                        <div class="property-thumb-top d-flex align-items-center">
                                            <?php foreach ($person->property_category as $category) : ?>
                                                <a href="#" class="btn btn-<?php echo $category == 'residential' ? 'orange' : 'blue'; ?> rounded-pill me-1" title="<?php echo ucfirst($category); ?>">
                                                    <?php echo $category; ?>
                                                </a>
                                            <?php endforeach; ?>
                                            <a href="#" class="btn btn-green rounded-pill user-listing-status-button" style="display: <?php echo $person->listing_status != 1 ? 'none;' : 'unset;'; ?>">Live</a>
                                            <div class="dropdown member-drop ms-auto">
                                                <button title="Open more actions" class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <?php echo co_owner_get_svg('3_dots'); ?>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                    <li><a class="dropdown-item" href="<?php echo home_url('create-a-person-listing'); ?>">Edit</a></li>
                                                    <li><a class="dropdown-item delete-my-person-list" href="#">Delete</a></li>
                                                    <li>
                                                        <a class="dropdown-item <?php echo $person->listing_status == 1 ? 'hide-my-person-list' : 'show-my-person-list'; ?>" href="#">
                                                            <?php echo $person->listing_status == 1 ? 'Hide My Profile Listing' : 'Show My Profile Listing'; ?>
                                                        </a>
                                                    </li>
                                                    <!--<li><a class="dropdown-item" href="#">Duplicate</a></li>-->
                                                    <!--<li><a class="dropdown-item" href="#">Mark as Completed</a></li>-->
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="property-one-thumb">
                                                <div class="property-thumb-bottom">
                                                    <div class="d-flex align-items-end">
                                                        <span class="small-title">Budget</span>
                                                        <h4 class="ms-auto"> <?php echo (price_range_show(($person->price_range))) ?></h4>
                                                    </div>
                                                </div>
												
													<?php 
													/* #changed 11*/
													$profile_url = avatar_default();
													if(avatar_exist($person->ID)){
													$profile_url =  get_avatar_url($person->ID); 
													}

													?>
                                                <img src="<?php echo $profile_url; ?>" class="img-fluid" alt="">
                                            </div>
                                            <div class="property-detail-area">
                                                <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE . "?id={$person->ID}"); ?>">
                                                    <h6><?php echo ucfirst($person->first_name) . ' ' . $person->last_name; ?></h6>
                                                    <div class="property-detail-cnt">
                                                        <p>Preferred Location(s):</p>
                                                        <?php foreach ($person->preferred_location as $location) { ?>
                                                            <span class="badge bg-light-grey rounded-pill">
                                                                <?php echo get_state_full_name($location); ?>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($view == 'create_by_my' || $view == 'completed') : ?>
                        <?php
                        $properties = get_my_properties($view == 'create_by_my' ? 'publish' : 'completed');

                        if ($view == 'create_by_my') {
                            $drafts_properties = get_my_properties('draft');
                            $properties = array_merge($drafts_properties, $properties);
                        }
                        if ($view == 'create_by_my') : ?>
                            <h4 class="pt-30px">My Properties</h4>

                            <div class="row pt-30px">
                                <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <a href="<?php echo home_url(CO_OWNER_CREATE_A_PROPERTY_PAGE); ?>" class="btn btn-orange-bordered">Create Property Listing</a>
                                </div>
                            </div>
                        <?php endif; ?>
					
                        <div class="row list-section pt-40px">
                            <?php foreach ($properties as $property) : 
						   $posted_by = get_post_meta($property->ID, '_pl_posted_by', true);
							if ($posted_by == 'Agent') {
								$posted_by = 'Agent/Non Owner' ;
								
							}
							?>				
							
                                <div class="property-box col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 pb-30px">
                                    <div class="card property-card-one">
                                        <div class="property-thumb-top d-flex align-items-center">
                                            <?php echo $property->enable_pool ? co_owner_get_svg('enable_pool') : null; ?>
                                            <a href="#" class="btn btn-<?php echo $property->property_category == 'commercial' ? 'blue' : 'orange'; ?> rounded-pill ms-1 me-1"><?php echo $property->property_category; ?></a>
                                            <?php if ($property->post_status == 'draft') : ?>
                                                <a href="#" class="btn btn-light property-completed rounded-pill">Draft</a>
                                            <?php elseif ($property->post_status == 'publish') : ?>
                                                <a href="#" class="btn btn-green rounded-pill user-listing-status-button">Live</a>
                                            <?php endif; ?>
                                            <a href="#" style="<?php echo ($property->post_status == 'completed') ? "" : 'display: none;' ?>" class="btn btn-green property-completed rounded-pill">Completed</a>
                                            <div class="dropdown member-drop ms-auto">
                                                <button title="Open more actions" class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <?php echo co_owner_get_svg('3_dots'); ?>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                    <?php if ($property->post_status == 'draft') : ?>
                                                        <li><a class="dropdown-item" href="<?php echo home_url(CO_OWNER_MY_LISTINGS_PAGE . "?id={$property->ID}&update_property_status=publish"); ?>">Post Listing</a></li>
                                                    <?php endif; ?>
                                                    <li><a class="dropdown-item" href="<?php echo home_url(CO_OWNER_CREATE_A_PROPERTY_PAGE . "?id={$property->ID}"); ?>">Edit</a></li>
                                                    <li><a class="dropdown-item property-delete-listing" data-id="<?php echo $property->ID; ?>" href="#">Delete</a></li>
                                                    <li><a class="dropdown-item duplicate-my-property" data-id="<?php echo $property->ID; ?>" href="#">Duplicate</a></li>
                                                    <li><a class="dropdown-item complete mark-as-complete-my-property" data-id="<?php echo $property->ID; ?>" style="<?php echo ($property->post_status != 'completed') ? "" : 'display: none;' ?>" href="#">Mark as Completed</a></li>
                                                    <li><a class="dropdown-item incomplete mark-as-complete-my-property" data-id="<?php echo $property->ID; ?>" style="<?php echo ($property->post_status == 'completed') ? "" : 'display: none;' ?>" href="#">Mark as In-Completed</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="property-one-thumb">
                                                <div class="property-thumb-bottom">
                                                    <div class="d-flex align-items-end">
                                                        <?php $price = get_property_price_for_display($property->ID); ?>
                                                        <?php if ($price > 0) : ?>
                                                            <span class="small-title">Selling Price</span>
                                                            <h4 class="ms-auto"><?php echo CO_OWNER_CURRENCY_SYMBOL ?> <?php echo $price; ?></h4>
                                                        <?php else : ?>
                                                            <span class="small-title text-danger mt-2">PORTIONS OF THE PROPERTY ARE NOT AVAILABLE</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <img src="<?php echo $property->image; ?>" class="img-fluid" alt="">
                                            </div>
                                            <div class="property-detail-area">
											  <!-- change 004 -->
											     <?php 
												  $property_link =get_the_permalink($property->ID);
												  if($property->post_status=="draft"){
													  $property_link = CO_OWNER_PROPERTY_DETAILS_PAGE . '/?id=' . $property->ID;
												  }
												 
												 ?>
												 <!-- change 004 -->
                                                <a href="<?php echo $property_link; /* CO_OWNER_PROPERTY_DETAILS_PAGE . '/?id=' . $property->ID; */ ?>">
												<!-- change 004 end -->
                                                    <h6>
                                                        <span class="d-block mb-2">
                                                      	 
															<?php echo $posted_by; ?> wants to sell: <?php echo $property->i_want_to_sell ? $property->i_want_to_sell . '%' : 'Full Property'; ?>
															
                                                        </span>
                                                        <p class="mb-2"><?php echo $property->post_title; ?></p>
                                                        <p class="mb-2"><?php echo $property->address; ?></p>
                                                    </h6>
                                                </a>
                                                <div class="property-detail-cnt">
                                                    <?php if ($property->property_category == 'residential' && ($property->bathroom || $property->bedroom || $property->parking)) : ?>
                                                        <div class="property-facility-area">
                                                            <?php if ($property->bedroom) : ?>
                                                                <a href="#">
                                                                    <?php echo co_owner_get_svg('bedroom'); ?>
                                                                    <span><?php echo $property->bedroom; ?></span>
                                                                </a>
                                                            <?php endif; ?>

                                                            <?php if ($property->bathroom) : ?>
                                                                <a href="#">
                                                                    <?php echo co_owner_get_svg('bathroom'); ?>
                                                                    <span><?php echo $property->bathroom; ?></span>
                                                                </a>
                                                            <?php endif; ?>

                                                            <?php if ($property->parking) : ?>
                                                                <a href="#">
                                                                    <?php echo co_owner_get_svg('parking'); ?>
                                                                    <span><?php echo $property->parking; ?></span>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
							 
                        </div>
                    <?php else : ?>
                        <div class="row list-section pt-40px">
                            <?php
                            $joined_pools = get_connected_connections(true);
                            foreach ($joined_pools as $pool) {
                                $pool->property_category = get_post_meta($pool->property_id, '_pl_property_category', true);
                                $pool->image = get_property_first_image($pool->property_id);
                                $pool->original_price = get_post_meta($pool->property_id, '_pl_property_original_price', true);
                                $pool->address = get_property_full_address($pool->property_id);
                                $pool->bathroom = get_post_meta($pool->property_id, '_pl_bathroom', true);
                                $pool->bedroom = get_post_meta($pool->property_id, '_pl_bedroom', true);
                                $pool->parking = get_post_meta($pool->property_id, '_pl_parking', true);
                            ?>
                                <div class="property-box col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 pb-30px">
                                    <div class="card property-card-one">
                                        <div class="property-thumb-top d-flex align-items-center">
                                            <?php echo co_owner_get_svg('enable_pool'); ?>
                                            <a href="#" class="btn btn-<?php echo $pool->property_category == 'commercial' ? 'blue' : 'orange'; ?> rounded-pill ms-1 me-1">
                                                <?php echo $pool->property_category; ?>
                                            </a>
                                            <div class="dropdown member-drop ms-auto">
                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <?php echo co_owner_get_svg('3-dots'); ?>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="">
                                                    <li><a class="dropdown-item incomplete" href="<?php echo home_url("messages/?is_pool=true&with={$pool->id}") ?>">Message</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="property-one-thumb">
                                                <div class="property-thumb-bottom">
                                                    <div class="d-flex align-items-end">
                                                        <?php $price = get_property_price_for_display($pool->property_id); ?>
                                                        <?php if ($price > 0) : ?>
                                                            <span class="small-title">Selling Price</span>
                                                            <h4 class="ms-auto"><?php echo CO_OWNER_CURRENCY_SYMBOL ?> <?php echo $price; ?></h4>
                                                        <?php else : ?>
                                                            <span class="small-title text-danger mt-2">PORTIONS OF THE PROPERTY ARE NOT AVAILABLE</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <img src="<?php echo $pool->image; ?>" class="img-fluid" alt="">
                                            </div>
                                            <div class="property-detail-area">
                                                <a href="<?php  echo get_the_permalink($pool->property_id); /*echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE . "?id={$pool->property_id}"); */ ?>">
                                                    <h6>
                                                        <span class="d-block"><?php echo $pool->name; ?></span>
                                                    </h6>
                                                </a>
                                                <div class="property-detail-cnt">
                                                    <?php if ($pool->property_category == 'residential' && ($pool->bathroom || $pool->bedroom || $pool->parking)) : ?>
                                                        <div class="property-facility-area">
                                                            <?php if ($pool->bedroom) : ?>
                                                                <a href="#">
                                                                    <?php echo co_owner_get_svg('bedroom'); ?>
                                                                    <span><?php echo $pool->bedroom; ?></span>
                                                                </a>
                                                            <?php endif; ?>

                                                            <?php if ($pool->bathroom) : ?>
                                                                <a href="#">
                                                                    <?php echo co_owner_get_svg('bathroom'); ?>
                                                                    <span><?php echo $pool->bathroom; ?></span>
                                                                </a>
                                                            <?php endif; ?>

                                                            <?php if ($pool->parking) : ?>
                                                                <a href="#">
                                                                    <?php echo co_owner_get_svg('parking'); ?>
                                                                    <span><?php echo $pool->parking; ?></span>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <?php if (count($joined_pools) == 0) : ?>
                                <div class="col-md-12">
                                    <a href="<?php echo home_url(); ?>" class="btn btn-orange btn-big rounded-pill">Explore Now</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>