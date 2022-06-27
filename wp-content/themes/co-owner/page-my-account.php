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
$user = get_person_detail_by_id(get_current_user_id());
if($user):
$documents = get_user_meta($user->ID,'_user_profile_document',true);
$name = $url = null;
$status = get_user_meta($user->ID,'_user_status',true);
$role = co_owner_get_user_field('s2member_access_role', $user->ID);
$user_eot_date = co_owner_get_user_field('s2member_auto_eot_time', $user->ID);
?>
<?php include('parts/modals/alert-model.php'); ?>
<?php include('parts/modals/edit-email.php'); ?>
<?php include('parts/modals/edit-mobile.php'); ?>
<?php include('parts/modals/deactivate-or-delete-my-account-model.php'); ?>
<?php include('parts/modals/leave-account-feedback-model.php'); ?>
<?php include('parts/modals/change-password-model.php'); ?>
<?php include('parts/modals/croppie.php'); ?>


<div class="center-area">

    <?php include(CO_OWNER_THEME_DIR.'/parts/my-account-page-header.php'); ?>

    <div class="main-section bg-white my-listings-main">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12">
                    <?php include(CO_OWNER_THEME_DIR.'/parts/my-account-page-aside.php') ?>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-12 py-40px pt-30px">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12 my-listings-title">
                                <h4 class="d-flex align-items-center">
                                    <span class="pe-2">My Account</span>
                                </h4>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row list-section pt-10px">
                                <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 pb-30px person-list-box">
                                    <div class="card property-card-one pb-2">
									
									
									
                                        <div class="card-body">
										
										
										
										
										<?php
                                           	/* #changed 11*/									
										  if(avatar_exist($user->ID)){
										?>
										<a href="javascript:void(0);" data-id="<?php echo $user->ID; ?>" class="remove-user-image">Remove</a> 
										  <?php }  /* #changed 11*/ ?>
										<a href="javascript:void(0);" style="display:none;" data-id="<?php echo $user->ID; ?>" class="remove-user-image-temp">Remove</a>   
										  <?php 
										  /* #changed 11*/
										   $profile_url = avatar_default();
										  if(avatar_exist($user->ID)){
											 $profile_url =  get_avatar_url($user->ID); 
										  }
										  /* #changed 11*/
										  ?>
                                            <div class="property-one-thumb">
											     <?php /* #changed 11*/ ?>
                                                <img id="user-profile" src="<?php echo esc_url($profile_url);  ?>" data-default="<?php echo avatar_default(); ?>" data-old="<?php echo esc_url( get_avatar_url($user->ID));  ?>" alt="" class="img-fluid <?php echo $user->user_status == 2 ? 'deactivated-account' :  null; ?>">
                                          
										   </div>
                                        </div>
                                         <div class="browse-new panel panel-default">
      <!-- <div class="panel-heading">Select Profile Image</div> -->
      <div class="panel-body" align="center">
       <input type="file" name="upload_image" id="upload_image" accept="image/*" />
       <div id="uploaded_image">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve"><style type="text/css">.st0{fill:#0A7E80;}</style>
        <path class="st0" d="M28,18.1v6.4h0v2.2c0,0.8-0.7,1.5-1.5,1.5h-23c-0.8,0-1.5-0.7-1.5-1.5v-7.1l0-1.5l-1.9,0V27c0,1.7,1.3,3,3,3H27c1.7,0,3-1.3,3-3v-8.9H28z"></path>
        <path class="st0" d="M22.6,7.9L15.8,1c-0.4-0.4-1-0.4-1.4,0l-7,6.7l1.4,1.4l5.5-5.3v18.1h1.8V4.1l5.1,5.2L22.6,7.9z"></path>
        </svg>
        Browse</div>
      </div>
     </div>
                                        <!-- <span class="d-block" id="user-profile-browse">
                                            <a class="user-browse-svg text-orange d-block" href="#">
                                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve">
                                                <style type="text/css">
                                                    .st0{fill:#0A7E80;}
                                                </style>
                                                <path class="st0" d="M28,18.1v6.4h0v2.2c0,0.8-0.7,1.5-1.5,1.5h-23c-0.8,0-1.5-0.7-1.5-1.5v-7.1l0-1.5l-1.9,0V27c0,1.7,1.3,3,3,3H27
                                                    c1.7,0,3-1.3,3-3v-8.9H28z"></path>
                                                <path class="st0" d="M22.6,7.9L15.8,1c-0.4-0.4-1-0.4-1.4,0l-7,6.7l1.4,1.4l5.5-5.3v18.1h1.8V4.1l5.1,5.2L22.6,7.9z"></path>
                                                </svg>
                                                Browse
                                            </a>
											<input type="file" name="profile" accept=".png,.jpg,.jpeg" style="display: none;">
                                         </span> -->
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-12 pb-3 d-none">
                                    <div class="file-field" id="user-profile-drop-box">
                                        <div class="btn-floating text-center">
                                            <div class="file-icon">
                                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve">
                                                    <style type="text/css">
                                                        .st0{fill:#0A7E80;}
                                                    </style>
                                                    <path class="st0" d="M28,18.1v6.4h0v2.2c0,0.8-0.7,1.5-1.5,1.5h-23c-0.8,0-1.5-0.7-1.5-1.5v-7.1l0-1.5l-1.9,0V27c0,1.7,1.3,3,3,3H27
                                                        c1.7,0,3-1.3,3-3v-8.9H28z"/>
                                                    <path class="st0" d="M22.6,7.9L15.8,1c-0.4-0.4-1-0.4-1.4,0l-7,6.7l1.4,1.4l5.5-5.3v18.1h1.8V4.1l5.1,5.2L22.6,7.9z"/>
                                                </svg>
                                            </div>
                                            <div class="file-cnt text-center">
                                                Drag & Drop your image or <span class="d-block" id="user-profile-browse">Browse</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-12 my-account-main pt-20px">
                                    <h5 class="pb-3">Personal Info</h5>
                                    <form id="my-account-info" method="post">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <input type="hidden" name="action" value="update_my_account_info">
                                                    <label class="d-block">First Name</label>
                                                    <input class="form-control" name="first_name" value="<?php echo $user->first_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="d-block">Last Name</label>
                                                    <input class="form-control" name="last_name" value="<?php echo $user->last_name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <?php $user_subscription = get_user_subscription_level($user); ?>
                                    <?php

                                        if(!empty($user_subscription) && $user_subscription != 'subscriber'): $plan_info = get_subscription_information($user_subscription); ?>
                                        <h5 class="pb-0 pt-4">Current Subscription</h5>
                                        <span class="lbl-detail small-text">
										<?php if($plan_info->slug == 'trial'){ ?>
										<?php echo "4 week free trial"; ?>
										<?php }else{ ?>
                                            <?php echo $plan_info->name; ?>
										<?php } ?>	
                                            <?php if($plan_info->slug == 'trial'){
                                                if(user_plan_is_expire($user) == false){
                                                    $date = wp_date('d  M Y',strtotime(get_user_meta($user->ID,'_user_subscription_valid_at',true)));
                                                    echo ",   Expires on $date";
                                                } else {
                                                    echo ", Your trial time is expire. Please update yor plan <a href='#' class='text-orange' data-bs-target='#plan-modal' data-bs-toggle='modal'>now</a>.";
                                                }
                                            }
                                            if(empty($user_eot_date) && ($role == 's2member_level1' || $role == 's2member_level2')){
                                                echo do_shortcode('[s2Member-Pro-Stripe-Form cancel="1" desc="This will cancel your account. Are you sure?" unsub="0" captcha="0" success="/my-account/?canceled=true" response="/my-account/?canceled=true" /]');
                                            }
                                            elseif(!empty($user_eot_date)){
                                                $date = wp_date('d  M Y',$user_eot_date);
                                                echo ",   Ends on $date";
                                            }
                                            ?>
                                        </span>
                                        <?php if(!empty($user_eot_date) && isset($_GET['canceled'])): ?>
                                            <div class='alert alert-danger mb-0 mt-2 p-2'>
                                                <strong>Billing termination confirmed.</strong> Your subscription has been cancelled.<br>Your subscription will end on <?php echo wp_date('d  M Y',$user_eot_date); ?> date. Until then you have full access to the platform.
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <h5 class="pb-0 pt-4">Account Verification</h5>
                                    <span class="lbl-detail small-text">Mobile and email verifications are required to activate listings and contact members.</span>

                                    <label class="d-block pt-4">
                                        Phone no
                                        <span class="mobile-verified-symbol"><?php echo $user->is_mobile_verified ? co_owner_get_svg('verified') : null; ?></span>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#edit-mobile-modal"><?php echo co_owner_get_svg('pencil'); ?></a>
                                    </label>
                                    <input readonly class="bg-white form-control border-0 p-0" name="_mobile" value="<?php echo !empty($user->mobile) ? $user->mobile : ''; ?>">

                                    <label class="d-block pt-4">
                                        E-mail
                                        <span class="email-verified-symbol"><?php echo $user->is_email_verified ? co_owner_get_svg('verified') : null; ?></span>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#edit-email-modal"><?php echo co_owner_get_svg('pencil'); ?></a>
                                    </label>
                                    <input readonly class="bg-white form-control border-0 p-0" name="user_email" value="<?php echo $user->user_email; ?>">

                                    <?php if(!empty($user->facebook_linked)): ?>
                                    <label class="d-block pt-4">
                                        Facebook <?php echo co_owner_get_svg('verified'); ?>
                                    </label>
                                    <?php endif; ?>
                                    <?php if(!empty($user->linkedin_linked)): ?>
                                    <label class="d-block pt-4">
                                        Linkedin <?php echo co_owner_get_svg('verified'); ?>
                                    </label>
                                    <?php endif; ?>


                                    <h5 class="pb-1 pt-4">Submit ID proof</h5>
                                    <span class="lbl-detail small-text d-block">Submitting your ID will add credibility and trust to your profile.</span>
                                    <strong class="lbl-detail d-block pb-4">Note: Your ID will not be displayed to any member.</strong>

                                    <?php $document_shield_status = get_user_meta($user->ID,'_document_shield_status',true); ?>

                                    <div class="pb-20px">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php if((empty($document_shield_status) && $document_shield_status != 0) || $document_shield_status == 2 || $document_shield_status == 0): ?>
                                                    <div class="file-field-btn">
                                                        <span class="btn-floating text-center mt-4 text-orange" id="user_upload_document_file">
                                                            <div title="Insert a file" class="file-cnt text-center">+ Upload file</div>
                                                        </span>
                                                    </div>
                                                    <button class="btn btn-orange-bordered btn-rounded ms-4" id="user_document_send_request" style="display:none;">
                                                        Submit Request
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-12 mt-3 document-status-message">
                                                <?php
                                                if($document_shield_status == 0 && is_numeric($document_shield_status)): ?>
                                                    <span class="alert alert-info px-2 py-1">Document submitted and pending for approval. You will be notified as soon as your ID is approved.</span>
                                                <?php elseif($document_shield_status == 1): ?>
                                                    <span class="alert alert-success px-2 py-1">Approved</span>
                                                <?php elseif($document_shield_status == 2): ?>
                                                    <span class="alert alert-danger px-2 py-1">Request rejected</span>
                                                    <p class="alert alert-danger mt-3">
                                                        <strong>Reason :-</strong><?php echo get_user_meta($user->ID,'_document_shield_reject_reason',true); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $documents = get_user_meta($user->ID,'_user_profile_documents',true); ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul id="preview-user-documents" class="list-unstyled">
                                                <?php foreach ((!empty($documents) ? $documents : array()) as $key => $document): ?>
                                                <li>
                                                    - <?php echo wp_basename($document['url']); ?>
                                                    <?php if($document_shield_status != 1): ?>
                                                        <a href="#" class="text-danger delete-document" data-index="<?php echo $key; ?>>"><?php echo co_owner_get_svg('trash'); ?></a>
                                                    <?php endif; ?>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="bottom-btn-area pt-20px pb-20px ml--24px mr--24px">
                                        <?php if(!isset($_SESSION['social_login']) && empty($_SESSION['social_login'])): ?>
                                        <button class="btn btn-orange-bordered rounded-pill me-2 mb-2" data-bs-toggle="modal" data-bs-target="#change-password-modal">Change Password</button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-orange btn-rounded submit-profile-info me-2 mb-2">Save My Changes</button>
                                        <a href="#" style="display: <?php echo $status == 1 ? 'inline-block;' : 'none;';?>" data-bs-toggle="modal" data-bs-target="#deactivate-or-delete-my-account-model" class="text-orange me-2 mb-2 deactivate-my-account-button text-nowrap align-items-center ">Deactivate or delete my account.</a>
                                        <a href="#" style="display: <?php echo $status != 1 ? 'inline-block;' : 'none;';?>" class="text-orange me-2 mb-2 active-my-account-button text-nowrap align-items-center ">Activate My Account.</a>
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
<?php else: ?>
    <?php include CO_OWNER_THEME_DIR.'parts/404.php'; ?>
<?php endif; ?>
<?php get_footer(); ?>
