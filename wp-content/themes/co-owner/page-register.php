<?php

/**
 * Default header file
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
is_user_login_redirect();
CoOwner::get_template_parts(array('parts/shared/html-header'));
$errors = new WP_Error();
include_once('parts/modals/subscription.php');
?>
<div class="d-flex align-items-center">
    <div class="container login-main">
        <div class="top-logo text-center">
            <a href="<?php echo home_url('/') ?>">
                <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40.58 40.58">
                    <defs>
                        <style>
                            .cls-1 {
                                fill: #272626;
                                fill-rule: evenodd;
                            }
                        </style>
                    </defs>
                    <path id="Combined-Shape" class="cls-1" d="M40.79,34.46a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,40.79,34.46ZM9.54,34.3a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,9.54,34.3Zm27.63-9.69a9.37,9.37,0,0,1,.53,7.74,4.91,4.91,0,0,0-5.42,5.41,9.31,9.31,0,0,1-9.86-2.12l-2.35-2.35a9.29,9.29,0,0,1-2.59-8.15,3.54,3.54,0,0,0,.64.92l.08.08,6.67,6.62a4.22,4.22,0,0,0,.61.49,3.69,3.69,0,0,0,5.11-1.13Q34.85,25.48,37.17,24.61ZM18.86,17.48a3.54,3.54,0,0,0-.92.64l-.08.08-6.62,6.67a4.22,4.22,0,0,0-.49.61,3.69,3.69,0,0,0,1.13,5.11q6.64,4.26,7.51,6.58a9.37,9.37,0,0,1-7.74.53,4.91,4.91,0,0,0-5.41-5.42,9.31,9.31,0,0,1,2.12-9.86l2.35-2.35A9.29,9.29,0,0,1,18.86,17.48ZM32.35,6.3a4.91,4.91,0,0,0,5.41,5.42,9.31,9.31,0,0,1-2.12,9.86l-2.35,2.35a9.29,9.29,0,0,1-8.15,2.59,3.54,3.54,0,0,0,.92-.64l.08-.08,6.62-6.67a4.22,4.22,0,0,0,.49-.61,3.69,3.69,0,0,0-1.13-5.11q-6.64-4.26-7.51-6.58A9.37,9.37,0,0,1,32.35,6.3ZM21.58,8.36l2.35,2.35a9.29,9.29,0,0,1,2.59,8.15,3.54,3.54,0,0,0-.64-.92l-.08-.08-6.67-6.62a4.22,4.22,0,0,0-.61-.49,3.69,3.69,0,0,0-5.11,1.13q-4.26,6.64-6.58,7.51a9.37,9.37,0,0,1-.53-7.74,4.91,4.91,0,0,0,5.42-5.41A9.31,9.31,0,0,1,21.58,8.36ZM41,3.21a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,41,3.21ZM9.7,3.05a4.59,4.59,0,1,1-6.49,0A4.59,4.59,0,0,1,9.7,3.05Z" transform="translate(-1.71 -1.71)" />
                </svg>
            </a>
        </div>


        <h4 class="text-center">Sign Up</h4>

        <div class="row justify-content-center mb-5">
            <div class="col col-xxl-6 col-xl-7 col-lg-8 col-md-10">
                <div class="card login-box">
                    <div class="card-body">
                        <h6 class="text-center">Enter via social media</h6>

                        <div class="row">
                            <div class="col text-center">
                                <a title="Sign in with Facebook" href="<?php echo home_url(); ?>/?action=redirect_to_facebook_login" class="btn btn-custom-pills btn-facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <g fill="none" fill-rule="evenodd">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path d="M0 0H16V16H0z" transform="translate(-501 -441) translate(483 431) translate(18 10)" />
                                                        <path fill="#FFF" d="M8.658 4.306c0-.629.065-.966 1.033-.966h1.323V1h-2.1C6.426 1 5.552 2.17 5.552 4.14v1.864H4V8h1.552v7h3.103V8h2.07l.304-1.996H8.655l.003-1.698z" transform="translate(-501 -441) translate(483 431) translate(18 10)" />
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    Log in with Facebook
                                </a>

                                <a title="Sign in with Google" href="<?php echo home_url(); ?>/?action=redirect_to_google_login" class="btn btn-custom-pills btn-g-plus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16">
                                        <g fill="none" fill-rule="evenodd">
                                            <g fill="#FFF" fill-rule="nonzero">
                                                <g>
                                                    <g>
                                                        <path d="M0 7.978C0 .938 7.907-2.51 12.525 2.09l-2.036 2.078c-2.693-2.755-7.633-.643-7.633 3.809 0 6.18 8.154 6.597 8.902 1.599H7.501V6.836h7.08c.071.401.122.804.122 1.327C14.703 18.88 0 18.27 0 7.978zm21.823-3.19v2.314H24v2.326h-2.177v2.315h-2.187V9.428h-2.177V7.102h2.177V4.788h2.187z" transform="translate(-735 -441) translate(483 431) translate(234) translate(18 10)" />
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    Log in with Google+
                                </a>
                            </div>
                        </div>

                        <div class="frm-title">
                            <h6>Or Sign up with email</h6>
                        </div>


                        <form class="w-75 frm-custom" method="post" action="" id="co-owner-user-register">
                            <?php $plan = get_subscription_information(isset($_GET['subscription']) ? $_GET['subscription'] : null); ?>
                            <div class="row">
                                <div class="col-md-12" id="plan-html-view">
                                    <?php if ($plan) : ?>
                                        <div class="card plan-card <?php echo $plan->slug == 'trial' ? 'standard' : $plan->slug; ?> shadow-sm">
                                            <h5 class="card-header p-2">
                                                <?php echo $plan->name; ?>
                                            </h5>
                                            <div class="card-body p-3">
                                                <h4 class="text-start pt-2 pb-3 text-capitalize">
                                                    <?php if ($plan->slug != 'trial') : ?>
                                                        <?php echo CO_OWNER_CURRENCY_SYMBOL . $plan->amount; ?><sub>/<?php echo $plan->duration; ?></sub>
                                                    <?php else : ?>
                                                        <?php echo $plan->duration; ?>
                                                    <?php endif; ?>
                                                    <a id="change-plan" class="btn btn-<?php echo $plan->slug == 'professional' ? 'blue' : 'orange'; ?> btn-rounded float-end btn-sm" data-bs-target="#plan-modal" data-bs-toggle="modal" href="#">Change</a>
                                                </h4>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="text-center">
                                            <a href="#plan-modal" class="btn btn-orange btn-rounded" data-bs-toggle="modal" data-bs-target="#plan-modal"> Choose your Plan</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <input type="hidden" value="<?php echo $plan ? $plan->slug : ""; ?>" name="_user_plan_type">
                            <div class="row" id="error-block" style="display:none;"></div>
                            <div class="row">
                                <div class="col col-sm-12 col-12 mb-3">
                                    <div class="text-error"></div>
                                </div>

                                <!--                                <div class="col col-sm-12 col-12 mb-3">-->
                                <!--                                    <input name="username" type="text" maxlength="25" class="form-control input-username" placeholder="Username" >-->
                                <!--                                </div>-->

                                <div class="col col-sm-6 col-12 mb-3">
                                    <input name="first_name" type="text" maxlength="20" class="form-control" id="firstname" placeholder="First Name">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col col-sm-6 col-12 mb-3">
                                    <input name="last_name" type="text" maxlength="20" class="form-control" id="lastname" placeholder="Last Name">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col col-sm-12 col-12 mb-3">
                                    <div class="verify-email-sec">
                                        <input name="email" id="user-email" type="text" maxlength="50" class="form-control" placeholder="Email id" aria-describedby="button-addon2">
                                        <input name="email_verified_" type="hidden" value="false">
                                        <a class="d-none btn btn-outline-secondary verified-user-email" href="#">
                                            <?php echo co_owner_get_svg('verified'); ?>
                                            Verify
                                        </a>
                                    </div>
                                    <label id="user-email-error" class="text-error" for="user-email"></label>
                                </div>
                            
                                <div class="col col-sm-12 col-12 mb-3 email-verify-code-input verify-code d-none" id="verify-code-input">
                                    <div class="row">
                                        <div class="col col-sm-12 col-12">
                                            <span class="otp-cnt">Verify with OTP sent to <span id="temp-email"></span> <a href="#" class="resend-verification-code">Resend Code</a></span>
                                        </div>
                                        <div class="col col-sm-12 col-12 otp-fld">
                                            <input type="text" id="verify_code_1" class="form-control input-only-number next-focus passInput" data-next-focus="#verify_code_2" maxlength="1" name="verify_code_1" onkeyup="autoTab('verify_code_1', '1', 'verify_code_2')" pattern="[0-9]*" inputmode="numeric">
                                            <input type="text" id="verify_code_2" class="form-control input-only-number next-focus passInput" data-next-focus="#verify_code_3" maxlength="1" name="verify_code_2" onkeyup="autoTab('verify_code_2', '1', 'verify_code_3')" pattern="[0-9]*" inputmode="numeric">
                                            <input type="text" id="verify_code_3" class="form-control input-only-number next-focus passInput" data-next-focus="#verify_code_4" maxlength="1" name="verify_code_3" onkeyup="autoTab('verify_code_3', '1', 'verify_code_4')" pattern="[0-9]*" inputmode="numeric">
                                            <input type="text" id="verify_code_4" class="form-control input-only-number passInput" maxlength="1" name="verify_code_4" pattern="[0-9]*" inputmode="numeric">
                                            <div class="text-error"></div>
                                            <label id="verify_code_1-error" class="text-error verify-input" for="verify_code_1"></label>
                                            <label id="verify_code_2-error" class="text-error d-none verify-input" for="verify_code_2"></label>
                                            <label id="verify_code_3-error" class="text-error d-none verify-input" for="verify_code_3"></label>
                                            <label id="verify_code_4-error" class="text-error d-none verify-input" for="verify_code_4"></label>
                                            <a class="disabled btn btn-outline-secondary verify-user-email" href="#" data-email="" data-verified="false" id="button-addon2">
                                                Verify
                                            </a>
                                        </div>
                                    </div>
                                </div>
							

                                <!--<div class="col col-sm-12 col-12 mb-3">
                                    <div class="verify-mobile-sec">
                                        <input name="mobile" type="text" class="form-control user-mobile-no" id="mobile-no" placeholder="Mobile No">
                                        <input name="mobile_verified_" type="hidden" value="false">
                                        <a class="btn btn-outline-secondary verified-user-mobile" href="#" style="display: none;">
                                            <?php //echo co_owner_get_svg('verified'); ?>
                                            Verified
                                        </a>
                                    </div>
                                    <label id="mobile-no-error" class="text-error" for="mobile-no"></label>
                                </div>-->

                                <div class="col col-sm-12 col-12 mb-3 user-mobile-no-verify-code-input verify-code" style="display:none;">
                                    <div class="row">
                                        <div class="col col-sm-12 col-12">
                                            <span class="otp-cnt">Verify with OTP sent to <span id="temp-mobile"></span> <a href="#" class="resend-mobile-verification-code">Resend Code</a></span>
                                        </div>
                                        <div class="col col-sm-12 col-12 otp-fld">
                                            <input type="text" id="mobile_verify_code_1" class="form-control input-only-number next-focus" data-next-focus="#mobile_verify_code_2" maxlength="1" name="mobile_verify_code_1" pattern="[0-9]*" inputmode="numeric">
                                            <input type="text" id="mobile_verify_code_2" class="form-control input-only-number next-focus" data-next-focus="#mobile_verify_code_3" maxlength="1" name="mobile_verify_code_2" pattern="[0-9]*" inputmode="numeric">
                                            <input type="text" id="mobile_verify_code_3" class="form-control input-only-number next-focus" data-next-focus="#mobile_verify_code_4" maxlength="1" name="mobile_verify_code_3" pattern="[0-9]*" inputmode="numeric">
                                            <input type="text" id="mobile_verify_code_4" class="form-control input-only-number" maxlength="1" name="mobile_verify_code_4" pattern="[0-9]*" inputmode="numeric">
                                            <div class="text-error"></div>
                                            <label id="mobile_verify_code_1-error" class="text-error" for="mobile_verify_code_1"></label>
                                            <label id="mobile_verify_code_2-error" class="text-error d-none" for="mobile_verify_code_2"></label>
                                            <label id="mobile_verify_code_3-error" class="text-error d-none" for="mobile_verify_code_3"></label>
                                            <label id="mobile_verify_code_4-error" class="text-error d-none" for="mobile_verify_code_4"></label>
                                            <a  class=" btn btn-outline-secondary verify-user-mobile" href="#" data-mobile="" data-verified="false" style="display: none;">
                                                Verify
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col col-sm-12 col-12 mb-3 pro-eye pro-eye-register">
                                    <input name="password" type="password" class="form-control" id="password" placeholder="Password">
                                    <span class="LoginPasswordToggle"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/show-pwd.svg'); ?>" class="login-password-toggle" /><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/hide-pwd.svg'); ?>" class="login-password-toggle" style="display: none;" /></span>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col col-sm-12 col-12 mt-5">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check check-link mx-auto">
                                            <input name="terms_and_condition" class="form-check-input" type="checkbox" value="" id="terms_and_condition">
                                            <label class="form-check-label" for="terms_and_condition">
                                                I agree to the <a target="_blank" href="<?php echo home_url('terms-of-use'); ?>">Terms of Use</a> & <a target="_blank" href="<?php echo home_url('privacy-policy'); ?>">Privacy Policy</a>
                                            </label>
                                            <br>
                                            <label id="terms_and_condition-error" class="text-error" for="terms_and_condition"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col col-sm-12 col-12 text-center mt-4">
                                    <button id="user_login_attempt" class="btn btn-orange btn-rounded w-180px" type="submit" disabled>Register Now</button>
                                </div>
                            </div>

                        </form>


                        <div class="box-bottom-cnt text-center">
                            Already have an account? <a href="<?php echo home_url('login'); ?>">Sign In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if (is_active_sidebar('copyright_disclaimer')) {
            dynamic_sidebar('copyright_disclaimer');
        }
        ?>
    </div>
</div>

<?php 
/* #change007  */
include('register_modal.php'); ?>

<?php include('parts/modals/croppie.php'); ?>

<?php
CoOwner::get_template_parts(array('parts/shared/html-footer'));
?>
	<script>
    jQuery(document).ready(function(){
      

        jQuery('#plan-html-view').on('click', function() {
            
            var plandisable = jQuery('[name="_user_plan_type"]').val();
			
			
			if(plandisable == "trial"){
			    jQuery('.professional-subscription').removeClass('plan-disable'); 
				jQuery('.standard-subscription').removeClass('plan-disable');
				jQuery('.trial-subscription').addClass('plan-disable');
				}
				if(plandisable == "professional"){
			    jQuery('.trial-subscription').removeClass('plan-disable'); 
				jQuery('.standard-subscription').removeClass('plan-disable');
				jQuery('.professional-subscription').addClass('plan-disable');
				}
				if(plandisable == "standard"){
			    jQuery('.trial-subscription').removeClass('plan-disable'); 
				jQuery('.professional-subscription').removeClass('plan-disable');
				jQuery('.standard-subscription').addClass('plan-disable');
				}
           
            
        });
        
    });
</script>