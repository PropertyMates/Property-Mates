<?php
$title = get_user_full_name($user->ID);
$user_property_category = get_user_meta($user->ID, '_user_property_category', true);
$property_category = empty($user_property_category) ? array() : $user_property_category;
$user_preferred_location = get_user_meta($user->ID, '_user_preferred_location', true);
$preferred_location = empty($user_preferred_location) ? array() : $user_preferred_location;
$budget = get_user_budget($user->ID);
$budget = $budget == null || empty($budget) ? 0 : $budget;
$budget_range = get_user_meta($user->ID, '_user_budget_range', true);
$is_liked = get_people_is_liked($user->ID);
?>
<div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 pb-30px">
    <div class="card property-card-one">
        <div class="card-body">
            <div class="property-one-thumb">
                <div class="property-thumb-top d-flex align-items-center">
                    <?php foreach ($property_category as $category) : ?>
                        <a href="#" class="btn btn-<?php echo $category != 'commercial' ? 'orange' : 'primary'; ?> rounded-pill me-1">
                            <?php echo $category; ?>
                        </a>
                    <?php endforeach; ?>
                    <a href="#" data-id="<?php echo $user->ID; ?>" class="btn btn-favourite ms-auto people <?php echo $is_liked ? 'active make-property-dislike' : 'make-property-like' ?>"></a>

                </div>

                <a href="<?php echo CO_OWNER_PERSON_DETAILS_PAGE . '?id=' . $user->ID; ?>">
                    <div class="property-thumb-bottom">
                        <div class="d-flex align-items-end">
                            <span class="small-title">Budget</span>
                            <h4 class="ms-auto">
                                <?php echo (price_range_show(($budget_range))) ?>
                            </h4>
                        </div>
                    </div>
                </a>
                <img src="<?php echo esc_url(get_avatar_url($user->ID));  ?>" class="img-fluid" alt="">
            </div>

            <div class="property-detail-area">
                <a href="<?php echo CO_OWNER_PERSON_DETAILS_PAGE . '?id=' . $user->ID; ?>">
                    <h6>
                        <?php echo $title; ?>
                        <?php if (get_user_shield_status($user->ID)) {
                            echo "&nbsp;<span class='user-shield-tooltip'>" . co_owner_get_svg('big_shield') . "</span>";
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