<?php
get_header();

$user_id = get_current_user_id();
$person = get_person_detail();

if ($person->user_status != 1 && $user_id != $person->ID) {
    $person = null;
}


$user_status = get_user_status($user_id);
$is_auth_user = ($person && ($person->ID == $user_id));
$connection = null;
if ($person) {
    $connection = CoOwner_Connections::get_connection_between_sender_receiver($user_id, $person->ID);
}
$properties = array();
$account_status_link = $user_status != 1 ? home_url(CO_OWNER_MY_ACCOUNT_PAGE) . '?alert=your_account_is_inactive' : '#';
?>

<div class="center-area">
    <?php if ($person) : ?>
        <?php if ($user_status == 1 && $user_id && $person->user_status == 1 && (empty($connection)) || ($connection && $connection->status != 0 || ($connection && $connection->receiver_user != $user_id))) : ?>
            <div class="modal fade default-modal-custom" id="person-connection-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <form action="" id="person-connection-form" data-id="<?php echo $person->ID ?>">
                                    <div class="col-sm-12 col-12 d-flex">
                                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="col-sm-12 col-12 pb-4">
                                        <h6>Connect with member</h6>
                                    </div>

                                    <?php $properties = get_my_properties_options($person->ID); ?>
                                    <?php if (count($properties) > 0) : ?>
 
                                        <div class="col-sm-12 col-12">
                                            <h5 class="double-bb-title">
                                                <span>Seller</span>
                                            </h5>
                                            <p>HEY, CHECK MY PROPERTY LISTING.</p>
                                        </div>

                                        <div class="col-sm-12 col-12 mb-3">
                                            <div class="w-100 custom-select mb-3 <?php echo (count($properties) == 1 ? 'd-none' : null) ?>">
                                                <select class="single-select2 property-select2" name="property_id">
                                                    <option value="">Select a property</option>
                                                    <?php foreach ($properties as $property) : ?>
                                                        <option <?php echo count($properties) == 1 ? 'selected' : ''; ?> value="<?php echo $property->ID; ?>" data-enable-pool="<?php echo $property->enable_pool; ?>" data-available-share="<?php echo $property->available_share; ?>" data-available-price="<?php echo $property->available_price; ?>" data-members="<?php echo count($property->members); ?>">
                                                            <?php echo $property->address; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <label id="property_id-error" class="text-error" for="property_id"></label>
                                            </div>
                                            <div class="connect-grey-main property-information" style="display:<?php echo (count($properties) == 1 ? 'blodk;' : 'none;') ?>;">
                                                <?php
                                                if (count($properties) > 0) : ?>
                                                    <h5>
                                                        <span class="is-pool"><?php echo ($properties[0]->enable_pool ? 'Pool: ' : null); ?></span>
                                                        <span class="address"><?php echo $properties[0]->address; ?></span>
                                                    </h5>
                                                    <p>
                                                        <span class="member-label <?php echo $properties[0]->enable_pool ? '' : 'd-none' ?>">Total member(s): </span>
                                                        <span class="total-members"><?php echo $properties[0]->enable_pool ? count($properties[0]->members) : ''; ?></span>
                                                        Available Portion: <span class="available-share"><?php echo $properties[0]->available_share ?></span>% at
                                                        <?php echo CO_OWNER_CURRENCY_SYMBOL; ?><span class="available-price"><?php echo number_format($properties[0]->available_price); ?></span>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            <h5 class="text-teal pt-3">Add other details</h5>
                                        </div>

                                        <div class="col-12">
                                            <div class="row property-share-inputs <?php echo ((count($properties) > 1 || count($properties) == 1 && $properties[0]->enable_pool == false)) ? 'd-none' : ''; ?>">
                                                <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-3">
                                                    <label for="property-share-options" class="form-label">I am interested in %</label>
                                                    <div class="w-100 custom-select">
                                                        <select id="property-share-options" data-available-share="<?php echo $property->available_share; ?>" data-available-price="<?php echo $property->available_price; ?>" class="form-select single-select2" name="interested_in">
                                                            <?php
                                                            if (count($properties) > 0) {
                                                                echo get_property_share_options_by_id($properties[0]->ID);
                                                            } else {
                                                                echo '<option value="">Select Interest</option>';
                                                            }
                                                            ?>
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
                                    <?php else : ?>
                                        <div class="col-sm-12 col-12 mb-4 py-3">
                                            <p class="text-danger">You don't have any listing.</p>
                                            <p class="text-danger">Why don't you create a listing of your search and contact this user so they know the reason for you to contact them.</p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-sm-12 col-12 mb-3 text-end bottom-btns">
                                        <a href="#" class="btn btn-orange-text rounded-pill" data-bs-dismiss="modal">Cancel</a>
                                        <?php if (count($properties) > 0) : ?>
                                            <button type="submit" class="btn btn-orange rounded-pill">Send Request</button>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="main-section list-section <?php echo $person->listing_status == 1 ? 'pt-30px' : 'py-40px'; ?>">
            <div class="container">
                <div class="card custom-card public-view-card mt-0">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-sm-12 col-12 d-lg-flex align-items-lg-end">
                                <div class="<?php echo get_user_shield_status($person->ID) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''; ?> mxw-180px d-md-inline-block">
                                    <div class="mbr-thumb large mb-0">
									<?php 
									/* #changed 11*/
									   $profile_url = avatar_default();
										  if(avatar_exist($person->ID)){
											 $profile_url =  get_avatar_url($person->ID); 
										  }
									
									?>
                                        <img src="<?php echo  $profile_url; ?>" alt="">
                                    </div>
                                </div>

                                <div class="member-details-cnt d-md-inline-block">
                                    <h4><?php echo $person->full_name; ?></h4>
                                    <?php if ($user_id && $user_status == 1) : ?>
                                        <dl class="row custom-data-list mb-0">
                                            <dt class="col-sm-12">Email id</dt>
                                            <dd class="col-sm-12">
                                                <a href="mailto:<?php echo $person->user_email; ?>"><?php echo $person->user_email; ?></a>
                                            </dd>
                                            <?php if ($person->mobile) : ?>
                                                <dt class="col-sm-12">Phone No</dt>
                                                <dd class="col-sm-12"><?php echo $person->mobile; ?></dd>
                                            <?php endif; ?>
                                            <?php if (!empty($person->facebook_linked)) : ?>
                                                <dt class="col-sm-12">
                                                    Facebook <?php echo co_owner_get_svg('verified'); ?>
                                                </dt>
                                            <?php endif; ?>
                                            <?php if (!empty($person->linkedin_linked)) : ?>
                                                <dt class="col-sm-12">
                                                    Linkedin <?php echo co_owner_get_svg('verified'); ?>
                                                </dt>
                                            <?php endif; ?>
                                            <?php if (!empty($person->instagram_linked)) : ?>
                                                <dt class="col-sm-12">
                                                    Instagram <?php echo co_owner_get_svg('verified'); ?>
                                                </dt>
                                            <?php endif; ?>
                                        </dl>
                                    <?php endif; ?>
                                </div>

                                <div class="ms-auto">
                                    <?php if ($user_id) : ?>
                                        <?php if ($is_auth_user) : ?>
                                            <a href="<?php echo home_url(CO_OWNER_MY_ACCOUNT_PAGE) ?>" class="btn btn-orange-bordered rounded-pill me-2 px-4">
                                                Edit Profile
                                            </a>
                                            <a href="<?php echo home_url(CO_OWNER_CREATE_A_PERSON_PAGE) ?>" class="btn btn-orange-bordered rounded-pill me-2 px-4">
                                                <?php echo $person->listing_status == 1 ? 'Edit Person Listing' : 'Continue Edit'; ?>
                                            </a>
                                            <?php if ($person->listing_status == 0) : ?>
                                                <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE) ?>/?id=<?php echo $person->ID; ?>&update_person_status=1" class="btn btn-orange-bordered rounded-pill">
                                                    Post Listing
                                                </a>
                                            <?php endif; ?>
                                        <?php elseif (!current_user_can('administrator')) : ?>
                                            <a href="#" data-id="<?php echo $person->ID; ?>" class="btn btn-orange-bordered <?php echo !$person->is_liked ? 'add-to-shortlist' : 'remove-to-shortlist'; ?> rounded-pill px-4 person"><?php echo $person->is_liked ? 'Remove From ' : null; ?>Shortlist</a>

                                            <?php if ($person->user_status == 1 && (empty($connection)) || ($connection && $connection->status != 0 || ($connection && $connection->receiver_user != $user_id))) : ?>

                                                <a href="<?php echo $account_status_link; ?>" data-id="<?php echo $person->ID; ?>" class="btn btn-orange connect-to-person rounded-pill px-4">Connect</a>

                                            <?php elseif ($person->user_status == 1 && $connection && $connection->receiver_user == $user_id && $connection->status == 0) : ?>

                                                <a href="#" data-id="<?php echo $connection->id; ?>" class="notify-reject-action btn btn-orange-bordered rounded-pill px-4">Deny</a>
                                                <a href="#" data-id="<?php echo $connection->id; ?>" class="notify-accept-action btn btn-orange rounded-pill px-4">Accept</a>

                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($person->listing_status == 1 || $user_id == $person->ID) : ?>
            <div class="main-section py-40px pt-4">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-12">
                            <div class="card custom-card public-view-card">
                                <div class="card-body">
                                    <h3>
                                        Property Preference
                                        <?php if ($person->enable_pool) : ?>
                                            <span class="badge bg-dark-teal">Open to joining pool</span>
                                        <?php endif; ?>
                                    </h3>
                                    <h4 class="pt-2">Preferred Location(s)</h4>

                                    <div class="bdg-main pb-4">
                                        <?php if ($person->preferred_location && is_array($person->preferred_location)) : ?>
                                            <?php foreach ($person->preferred_location as $location) : ?>
                                                <span class="badge bg-grey text-dark"><?php echo get_state_full_name($location); ?></span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>

                                    <?php
                                    if (
                                        ($person->bedroom && $person->bedroom > 0) ||
                                        ($person->bathroom && $person->bathroom > 0) ||
                                        ($person->parking && $person->parking > 0)
                                    ) :
                                    ?>
                                        <div class="property-facility-area">
                                            <?php if ($person->bedroom > 0) : ?>
                                                <a href="#">
                                                    <?php echo co_owner_get_svg('bedroom'); ?>
                                                    <span><?php echo $person->bedroom; ?></span>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($person->bathroom > 0) : ?>
                                                <a href="#">
                                                    <?php echo co_owner_get_svg('bathroom'); ?>
                                                    <span><?php echo $person->bathroom; ?></span>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($person->parking > 0) : ?>
                                                <a href="#">
                                                    <?php echo co_owner_get_svg('parking'); ?>
                                                    <span><?php echo $person->parking; ?></span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif ?>


                                    <div class="row">
                                        <div class="col-sm-12 col-12 pt-40px">
                                            <h4>Description </h4>
                                            <?php $description = apply_filters('the_content', $person->descriptions); ?>
                                            <?php if (!empty($description)) : ?>
                                                <div class="description-box">
                                                    <div class="description-small" style="height: 3em;"><?php echo $description; ?>
                                                        <div class="blur-line" id="blur-line"></div>
                                                    </div>
                                                    <div class="description-full" style="display: none;"><?php echo $description; ?></div>
                                                </div>
                                                <a href="#" class="btn view-full-description btn-orange-bordered rounded-pill btn-sm <?php echo strlen($description) > 80 ?: 'd-none'; ?>">Read more</a>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row pt-40px">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                            <h4>Property Type</h4>
                                            <?php if ($person->property_type && is_array($person->property_type)) : ?>
                                                <?php foreach ($person->property_type as $type) : ?>
                                                    <p><?php echo ucfirst($type); ?></p>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <p>-</p>
                                            <?php endif; ?>
                                        </div>


                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                            <h4>Building Area</h4>
                                            <p><?php echo !empty($person->building_area) ? $person->building_area : '-'; ?></p>
                                        </div>
                                    </div>

                                    <div class="row pt-40px">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                            <h4>Land Area</h4>
                                            <p><?php echo (!empty($person->land_area) ? $person->land_area : '-'); ?></p>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                            <h4>Age/year built</h4>
                                            <p><?php echo (!empty($person->age_year_built) ? $person->age_year_built : '-')/* . ' Years'*/; ?></p>
                                        </div>
                                    </div>


                                    <div class="row pt-40px">
                                        <div class="col-sm-12 col-12">
                                            <h4>Property Features</h4>
                                        </div>
                                        <?php if (count($person->property_features) > 0 || count($person->manually_features) > 0) : ?>
                                            <?php foreach (array_merge($person->property_features, $person->manually_features) as $features) : ?>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                                    <p><?php echo $features; ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                                                <p>-</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12">
                            <div class="card custom-card public-view-card">
                                <div class="card-body">
                                    <h3>Budget Information</h3>
                                    <h4>Budget</h4>
                                    <h2>
                                        <?php echo (price_range_show(($person->price_range))) ?>
                                    </h2>
                                    <?php if (!$is_auth_user && (count($properties) > 0 || $user_status != 1)) : ?>
                                        <div class="investor-cnt bb-1 mt-4 pb-4">
                                            <h3 class="mb-2">Do you have a suitable property?</h3>
                                            <p class="pb-3">Send me a connection request and let's chat.</p>
                                            <a href="<?php echo $account_status_link; ?>" class="btn btn-orange connect-to-person rounded-pill">Connect with member</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="main-section py-40px pt-4"></div>
            <div class="main-section py-40px pt-4"></div>
            <div class="main-section py-40px pt-4"></div>
            <div class="main-section py-40px pt-4"></div>
        <?php endif; ?>
    <?php else : ?>
        <?php include CO_OWNER_THEME_DIR . 'parts/404.php'; ?>
    <?php endif; ?>
</div>


<?php get_footer();  ?>