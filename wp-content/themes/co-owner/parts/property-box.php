<?php
$title = ucfirst($property->post_title);
$property_category = get_post_meta($property->ID,'_pl_property_category',true);
$enable_pool = get_post_meta($property->ID,'_pl_enable_pool',true);
$posted_by = get_post_meta($property->ID,'_pl_posted_by',true);

$image_url = get_property_first_image($property->ID);
//$owner = get_the_author_meta('display_name', $property->post_author);
$market_price = (int) get_post_meta($property->ID,'_pl_property_original_price',true);
$interested_in_selling = get_post_meta($property->ID,'_pl_interested_in_selling',true);
$i_want_to_sell = $interested_in_selling !== 'full_property' ? get_post_meta($property->ID,'_pl_i_want_to_sell',true) : null ;

$bathroom = (int) get_post_meta($property->ID,'_pl_bathroom',true);
$bedroom = (int) get_post_meta($property->ID,'_pl_bedroom',true);
$parking = (int) get_post_meta($property->ID,'_pl_parking',true);
$is_liked = get_property_is_liked($property->ID);
$col = $view_mode == 'list' && ($enable_pool || is_page('property-search')) ? '4' : '3';

$members = $enable_pool ? get_property_total_members($property->ID) : array();
?>
<div class="property-info-box <?php echo $view_mode == 'list' ? "col-xxl-{$col} col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12" : "col-sm-12 col-12" ?> pb-30px" data-id="<?php echo $property->ID;?>">
    <div class="card property-card-one">
        <div class="card-body">
            <div class="property-one-thumb">
                <div class="property-thumb-top d-flex align-items-center">
                    <?php if($enable_pool): ?>
                        <?php echo co_owner_get_svg('enable_pool'); ?>
                    <?php endif; ?>
                    <a href="#" class="btn btn-<?php echo $property_category == 'residential' ? 'orange' : 'blue'; ?> <?php echo $enable_pool ? 'ms-1 ' : ''; ?>rounded-pill me-1"><?php echo $property_category; ?></a>
                    <a href="#" class="btn btn-orange-outline rounded-pill">Posted by: <?php echo $posted_by; ?></a>
                    <a href="#" data-id="<?php echo $property->ID; ?>" class="btn btn-favourite ms-auto <?php echo $is_liked ? 'active make-property-dislike' : 'make-property-like' ?>"></a>
                </div>
                <a href="<?php echo get_the_permalink($property->ID); /*echo CO_OWNER_PROPERTY_DETAILS_PAGE.'/?id='.$property->ID; */ ?>" >
                    <div class="property-thumb-bottom">
                        <?php if(count($members) > 0):
                            $available_share = get_property_available_share($property->ID);
                        ?>
                            <div class="property-mbr">
                                <span class="mbr-tite" title="<?php echo count($members); ?> Members"><?php echo count($members); ?> Members</span>
                                <?php if($available_share > 0): ?><span class="mbr-tite" title="Available  <?php echo $available_share; ?>%"> & Available  <?php echo $available_share; ?>%</span> <?php endif; ?>
                                <div class="pt-2 <?php echo $view_mode == 'list' ? 'd-flex' : ''; ?>">
                                    <?php foreach ($members as $key => $member): ?>
                                        <?php if($key <= 1 ): ?>
                                            <div class="mbr-list d-flex align-items-center pe-1 <?php echo $view_mode == 'map' ? 'mb-1' : ''; ?>">
                                                <div class="mbr-photo">
                                                    <img src="<?php echo get_avatar_url($member->id) ?>" alt="" class="h-100 w-100 user-avatar">
                                                </div>
                                                <div class="mbr-hold d-sm-inline-block d-none">
                                                    <?php
                                                        if(get_user_shield_status($member->id)){
                                                            echo "<span class='user-shield-tooltip'>".co_owner_get_svg('shield')."</span>";
                                                        }
                                                    ?>
                                                    <?php if($member->is_admin && $interested_in_selling == 'full_property'): ?>
                                                        Admin
                                                    <?php elseif($member->is_admin && $interested_in_selling == 'portion_of_it'): ?>
                                                        Admin holds <?php echo get_admin_hold_pr($property->ID); ?>%
                                                    <?php else: ?>
                                                        holds <?php echo $member->interested_in; ?>%
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php echo count($members) > 2 ? "<span class='align-content-center d-grid small-title'>+ ".(count($members)-2)." More</span>" : ''; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex align-items-end">
                            <?php if($enable_pool): ?>
                                <?php if($available_share > 0): ?>
                                    <span class="small-title">Selling Price</span>
                                    <h4 class="ms-auto"><?php echo get_updated_property_price($property->ID); ?></h4>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="small-title">Selling Price</span>
                                <h4 class="ms-auto"><?php echo get_updated_property_price($property->ID)?></h4>
                            <?php endif; ?>
                        </div>
                    </div>
                    <img src="<?php echo $image_url; ?>" class="img-fluid" alt="">
                </a>
            </div>

            <div class="property-detail-area">
                <a href="<?php echo get_the_permalink($property->ID); /* CO_OWNER_PROPERTY_DETAILS_PAGE.'/?id='.$property->ID; */ ?>" >
                    <h6>
                        <span class="d-block">
                            Owner wants to sell: <?php echo $i_want_to_sell ? $i_want_to_sell.'%' : 'Full Property'; ?>
                        </span>
                        <?php echo $title; ?>
                    </h6>
                </a>

                <div class="property-facility-area d-flex align-items-end">
                    <?php if($property_category == 'residential'): ?>
                        <a href="#">
                            <?php echo co_owner_get_svg('bedroom'); ?>
                            <span><?php echo $bedroom; ?></span>
                        </a>
                        <a href="#">
                            <?php echo co_owner_get_svg('bathroom'); ?>
                            <span><?php echo $bathroom; ?></span>
                        </a>
                        <a href="#">
                            <?php echo co_owner_get_svg('parking'); ?>
                            <span><?php echo $parking; ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if($enable_pool && $available_share == 0): ?>
                        <a href="#" class="btn fw ms-auto text-danger pb-0">POOL IS FULL</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
