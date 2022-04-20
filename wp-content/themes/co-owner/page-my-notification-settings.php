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
$user_id = get_current_user_id();

$notify_me_daily = get_user_meta($user_id,'_user_notify_when_have_new_notify_me_daily',true);
$notify_me_weekly = get_user_meta($user_id,'_user_notify_when_have_new_notify_me_weekly',true);
$notify_me_monthly = get_user_meta($user_id,'_user_notify_when_have_new_notify_me_monthly',true);

$newsletters_and_offers_email = get_user_meta($user_id,'_user_notify_when_have_new_newsletters_and_offers_email',true);
$newsletters_and_offers_mobile = get_user_meta($user_id,'_user_notify_when_have_new_newsletters_and_offers_mobile',true);

$connection_request_email = get_user_meta($user_id,'_user_notify_when_have_new_connection_request_email',true);
$connection_request_mobile = get_user_meta($user_id,'_user_notify_when_have_new_connection_request_mobile',true);

$matching_listing_email = get_user_meta($user_id,'_user_notify_when_have_new_matching_listing_email',true);
$matching_listing_mobile = get_user_meta($user_id,'_user_notify_when_have_new_matching_listing_mobile',true);

$message_email = get_user_meta($user_id,'_user_notify_when_have_new_message_email',true);
$message_mobile = get_user_meta($user_id,'_user_notify_when_have_new_message_mobile',true);

?>
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
                                <span class="pe-2">Notification Settings</span>
                            </h4>
                        </div>
                    </div>
                    <div class="row all-settings  list-section pt-1">
                        <div class="col-sm-12 my-account-main">
                            <h5 class="pb-3">Messages</h5>
                            <label class="d-block t-transform-none pb-3">Be notified when you have a new message</label>
                            <div class="pb-30px setting row m-0">
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $message_email ? 'checked' : null ?> name="_user_notify_when_have_new_message" class="form-check-input" type="checkbox" value="email" id="user_email_checkbox">
                                    <label class="form-check-label" for="user_email_checkbox">
                                        Email
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $message_mobile ? 'checked' : null ?> name="_user_notify_when_have_new_message" class="form-check-input" type="checkbox" value="mobile" id="user_mobile_checkbox">
                                    <label class="form-check-label" for="user_mobile_checkbox">
                                        Mobile
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox">
                                    <input <?php echo ($message_email == false && $message_mobile == false) ? 'checked' : null; ?> name="_user_notify_when_have_new_message" class="form-check-input" type="checkbox" value="no_thanks" id="user_no_thanks_checkbox">
                                    <label class="form-check-label" for="user_no_thanks_checkbox">
                                        No thanks
                                    </label>
                                </div>
                            </div>

                            <h5 class="pb-3">Listings</h5>
                            <label class="d-block t-transform-none pb-3">Be notified when you have a new matching listing.</label>
                            <div class="pb-30px setting row m-0">
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $matching_listing_email ? 'checked' : null ?> name="_user_notify_when_have_new_matching_listing" class="form-check-input" type="checkbox" value="email" id="matching_listing_user_email_checkbox">
                                    <label class="form-check-label" for="matching_listing_user_email_checkbox">
                                        Email
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $matching_listing_mobile ? 'checked' : null ?> name="_user_notify_when_have_new_matching_listing" class="form-check-input" type="checkbox" value="mobile" id="matching_listing_user_mobile_checkbox">
                                    <label class="form-check-label" for="matching_listing_user_mobile_checkbox">
                                        Mobile
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox">
                                    <input <?php echo ($matching_listing_email == false && $matching_listing_mobile == false) ? 'checked' : null; ?> name="_user_notify_when_have_new_matching_listing" class="form-check-input" type="checkbox" value="no_thanks" id="matching_listing_user_no_thanks_checkbox">
                                    <label class="form-check-label" for="matching_listing_user_no_thanks_checkbox">
                                        No thanks
                                    </label>
                                </div>
                            </div>

                            <h5 class="pb-0 pt-4 pb-3">Connection Request</h5>
                            <label class="d-block t-transform-none pb-3">Be notified when you have a new connection request.</label>
                            <div class="pb-40px bb-1px setting row m-0">
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $connection_request_email ? 'checked' : null ?> name="_user_notify_when_have_new_connection_request" class="form-check-input" type="checkbox" value="email" id="connection_request_user_email_checkbox">
                                    <label class="form-check-label" for="connection_request_user_email_checkbox">
                                        Email
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $connection_request_mobile ? 'checked' : null ?> name="_user_notify_when_have_new_connection_request" class="form-check-input" type="checkbox" value="mobile" id="connection_request_user_mobile_checkbox">
                                    <label class="form-check-label" for="connection_request_user_mobile_checkbox">
                                        Mobile
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox">
                                    <input <?php echo ($connection_request_email == false && $connection_request_mobile == false) ? 'checked' : null; ?> name="_user_notify_when_have_new_connection_request" class="form-check-input" type="checkbox" value="no_thanks" id="connection_request_user_no_thanks_checkbox">
                                    <label class="form-check-label" for="connection_request_user_no_thanks_checkbox">
                                        No thanks
                                    </label>
                                </div>
                            </div>

                            <h5 class="pb-0 pt-5 pb-3">Notification Preferences</h5>
                            <label class="d-block t-transform-none pb-3">Newsletters and offers</label>
                            <div class="pb-30px setting row m-0">
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $newsletters_and_offers_email ? 'checked' : null ?> name="_user_notify_when_have_new_newsletters_and_offers" class="form-check-input" type="checkbox" value="email" id="newsletters_and_offers_user_email_checkbox">
                                    <label class="form-check-label" for="newsletters_and_offers_user_email_checkbox">
                                        Email
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $newsletters_and_offers_mobile ? 'checked' : null ?> name="_user_notify_when_have_new_newsletters_and_offers" class="form-check-input" type="checkbox" value="mobile" id="newsletters_and_offers_user_mobile_checkbox">
                                    <label class="form-check-label" for="newsletters_and_offers_user_mobile_checkbox">
                                        Mobile
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox">
                                    <input <?php echo ($newsletters_and_offers_email == false && $newsletters_and_offers_mobile == false) ? 'checked' : null; ?> name="_user_notify_when_have_new_newsletters_and_offers" class="form-check-input" type="checkbox" value="no_thanks" id="newsletters_and_offers_user_no_thanks_checkbox">
                                    <label class="form-check-label" for="newsletters_and_offers_user_no_thanks_checkbox">
                                        No thanks
                                    </label>
                                </div>
                            </div>

                            <label class="d-block t-transform-none pb-3">Notify me</label>
                            <div class="pb-30px setting row m-0">
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $notify_me_daily ? 'checked' : null ?> name="_user_notify_when_have_new_notify_me" class="form-check-input" type="checkbox" value="daily" id="notify_me_user_daily_checkbox">
                                    <label class="form-check-label" for="notify_me_user_daily_checkbox">
                                        Daily
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $notify_me_weekly ? 'checked' : null ?> name="_user_notify_when_have_new_notify_me" class="form-check-input" type="checkbox" value="weekly" id="notify_me_user_weekly_checkbox">
                                    <label class="form-check-label" for="notify_me_user_weekly_checkbox">
                                        Weekly
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox me-5">
                                    <input <?php echo $notify_me_monthly ? 'checked' : null ?> name="_user_notify_when_have_new_notify_me" class="form-check-input" type="checkbox" value="monthly" id="notify_me_user_monthly_checkbox">
                                    <label class="form-check-label" for="notify_me_user_monthly_checkbox">
                                        Monthly
                                    </label>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 col-12 form-check mb-1 custom-checkbox">
                                    <input <?php echo ($notify_me_daily == false && $notify_me_weekly == false && $notify_me_monthly == false) ? 'checked' : null; ?> name="_user_notify_when_have_new_notify_me" class="form-check-input" type="checkbox" value="no_thanks" id="notify_me_user_no_thanks_checkbox">
                                    <label class="form-check-label" for="notify_me_user_no_thanks_checkbox">
                                        No thanks
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
