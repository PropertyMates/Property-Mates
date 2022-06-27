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
?>
<?php include('parts/modals/alert-model.php') ?>
<?php include('parts/modals/edit-email.php') ?>
<?php include('parts/modals/edit-mobile.php') ?>

<div class="center-area">

    <?php include(CO_OWNER_THEME_DIR.'/parts/my-account-page-header.php') ?>

    <div class="main-section bg-white my-listings-main">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12">
                    <?php include(CO_OWNER_THEME_DIR.'/parts/my-account-page-aside.php') ?>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-12 py-40px pt-30px">
                    <div class="row">
                        <div class="col-md-12 my-listings-title">
                            <h4 class="d-flex align-items-center">
                                <span class="pe-2">Account Verification</span>
                            </h4>
                            <small>Mobile and email verification.</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <label class="text-secondary">
                                Mobile No
                                <span class="mobile-verified-symbol"><?php echo $user->is_mobile_verified ? co_owner_get_svg('verified') : null; ?></span>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#edit-mobile-modal"><?php echo co_owner_get_svg('pencil'); ?></a>
                            </label>
                            <?php if(!$user->is_mobile_verified): ?>
                                <h6 class="alert alert-danger m-0 p-2 mobile-verified-error">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><a href="#" data-bs-toggle="modal" data-bs-target="#edit-mobile-modal" class="text-orange">Please add your mobile number.</a><br></li>
                                        <li><strong>Note: </strong>We are working hard to give you only serious and trusted users on Property Mates.</li>
                                    </ul>
                                </h6>
                            <?php endif; ?>
                            <input readonly class="bg-white form-control border-0 p-0" name="_mobile" value="<?php echo !empty($user->mobile) ? $user->mobile : ''; ?>">
                        </div>
                        <div class="col-md-12 mt-4">
                            <label class="text-secondary">
                                Email
                                <span class="email-verified-symbol"><?php echo $user->is_email_verified ? co_owner_get_svg('verified') : null; ?></span>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#edit-email-modal"><?php echo co_owner_get_svg('pencil'); ?></a>
                            </label>

                            <input readonly class="bg-white form-control border-0 p-0" name="user_email" value="<?php echo $user->user_email; ?>">
                        </div>

                        <div class="col-md-12 mt-5">
                            <h6 class="h6 text-secondary">LINKED ACCOUNTS (OPTIONAL)</h6>
                            <p class="mb-0">Linking to your social networks adds credibility to your profile. You may add more than one. </p>
                            <strong>Note: Your personal information will not be displayed to other members.</strong>

                            <div class="row mt-5">
                                <div class="col-md-12 py-4 border border-1 border-start-0 border-end-0">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">Facebook</div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                            <div class="verify-message">
                                                <?php
                                                    $is_facebook_verified = get_user_meta($user->ID,'_user_facebook_id',true);
                                                    if($is_facebook_verified){
                                                        echo co_owner_get_svg('verified').' Verified';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3 text-end">
                                            <a style="<?php echo $is_facebook_verified ? '' : 'display:none;'; ?>" href="#" class="text-orange remove-social-account" data-social-account="facebook">Remove</a>
                                            <a style="<?php echo !$is_facebook_verified ? '' : 'display:none;'; ?>" href="<?php echo home_url(); ?>/?action=redirect_to_facebook_login" class="text-orange link-social-account" data-social-account="facebook">Link</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 py-4 border border-1 border-start-0 border-top-0 border-end-0">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">Linkedin</div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                            <div class="verify-message">
                                                <?php
                                                $is_linkedin_verified = get_user_meta($user->ID,'_user_linkedin_id',true);
                                                if($is_linkedin_verified){
                                                    echo co_owner_get_svg('verified').' Verified';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3 text-end">
                                            <a href="#" class="text-orange remove-social-account" style="<?php echo $is_linkedin_verified ? '' : 'display:none;'; ?>" data-social-account="linkedin">Remove</a>
                                            <a href="<?php echo home_url(); ?>/?action=redirect_to_linkedin_login" style="<?php echo !$is_linkedin_verified ? '' : 'display:none;'; ?>" class="text-orange link-social-account" data-social-account="google">Link</a>
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
</div>
<?php
endif;
get_footer(); ?>
