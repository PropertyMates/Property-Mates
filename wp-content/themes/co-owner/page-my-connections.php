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
$view = (isset($_GET['view']) ? ($_GET['view'] == 'connections' ? 'connections' :  'pools' ) : 'connections');
$connections = get_all_my_connections($view);
?>
<div class="center-area">

    <?php include(CO_OWNER_THEME_DIR.'/parts/my-account-page-header.php') ?>

    <div class="main-section bg-white my-listings-main my-conn">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12">
                    <?php include(CO_OWNER_THEME_DIR.'/parts/my-account-page-aside.php') ?>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-12 py-40px pt-30px">
                    <div class="row">
                        <div class="col-md-12 my-listings-title">
                            <h4 class="d-flex align-items-center">
                                <span class="pe-2">My Connections</span>
                            </h4>

                            <div class="my-list-links w-100">
                                <ul>
                                    <li class="<?php echo $view == 'connections' ? 'active' : null; ?>"><a href="<?php echo home_url('my-connections?view=connections')?>">All Connections</a></li>
                                    <li class="<?php echo $view == 'pools' ? 'active' : null; ?>"><a title="All existing pools" href="<?php echo home_url('my-connections?view=pools')?>">Pools-Chat Rooms</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row list-section pt-40px">
                        <?php foreach ($connections as $connection): ?>
                            <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 pb-30px">
                                <?php if($view == 'connections'): ?>
                                    <div class="card member-card">
                                        <div class="card-body pb-20px">
                                            <div class="mbr-title d-flex w-100">
                                                <div class="form-check small custom-checkbox ms-auto">
                                                    <div class="dropdown member-drop ms-auto">
                                                        <button title="Open more actions" class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                                 viewBox="0 0 32 18" style="enable-background:new 0 0 32 18;" xml:space="preserve">
                                                                    <style type="text/css">
                                                                        .st0{opacity:0.65;fill:#262626;}
                                                                    </style>
                                                                <g>
                                                                    <circle class="st0" cx="3.4" cy="9" r="3.2"/>
                                                                    <circle class="st0" cx="16.1" cy="9" r="3.2"/>
                                                                    <circle class="st0" cx="28.6" cy="9" r="3.2"/>
                                                                </g>
                                                                </svg>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                            <li><a data-connection-id="<?php echo $connection->connection_id; ?>" data-id="<?php echo $connection->id; ?>" class="dropdown-item user-remove-action" href="#">Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mbr-detail-area">
                                                <div class="<?php echo get_user_shield_status($connection->id) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''?> mt--46px">
                                                    <div class="mbr-thumb mx-auto">
                                                        <img src="<?php echo $connection->profile; ?>" alt="">
                                                    </div>
                                                </div>
                                                <h5 class="text-center">
                                                    <a href="<?php echo home_url('person-details/?id='.$connection->id); ?>" class="text-orange">
                                                        <?php echo $connection->name; ?>
                                                    </a>
                                                </h5>

                                                <?php if($connection->user_status == 1): ?>
                                                    <span class="cnt text-center">
                                                        <a href="mailto:<?php echo $connection->email; ?>"><?php echo $connection->email; ?></a>
                                                    </span>
                                                    <span class="cnt text-center"><?php echo $connection->mobile; ?></span>
                                                <?php endif; ?>

                                                <div class="btn-area d-flex justify-content-center">
                                                    <a href="<?php echo home_url("messages/?is_pool=false&with={$connection->id}"); ?>" class="btn btn-orange btn-sm rounded-pill me-1">Message</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="card member-card for-connections">
                                        <div class="card-body pb-20px">
                                            <div class="mbr-title d-flex w-100">
                                                <div class="form-check small custom-checkbox ms-auto">
                                                    <div class="dropdown member-drop ms-auto">
                                                        <button  title="Open more actions" class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 18" style="enable-background:new 0 0 32 18;" xml:space="preserve">
                                                                <style type="text/css">
                                                                    .st0{opacity:0.65;fill:#262626;}
                                                                </style>
                                                                <g>
                                                                    <circle class="st0" cx="3.4" cy="9" r="3.2"/>
                                                                    <circle class="st0" cx="16.1" cy="9" r="3.2"/>
                                                                    <circle class="st0" cx="28.6" cy="9" r="3.2"/>
                                                                </g>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                            <?php if($connection->user_id == $user_id): ?>
                                                            <li><a data-id="<?php echo $connection->id; ?>" class="dropdown-item delete-group-action" href="#">Delete</a></li>
                                                            <?php else: ?>
                                                            <li><a data-id="<?php echo $connection->id; ?>" data-user-id="<?php echo $user_id; ?>" class="dropdown-item leave-group-action" href="#">Leave</a></li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mbr-detail-area">
                                                <?php
                                                    $images = get_post_meta($connection->property_id,'_pl_images',true);
                                                ?>
                                                <div class="mbr-icon-onthumb mt--46px">
                                                    <div class="mbr-thumb mx-auto">
                                                        <img src="<?php echo count($images) > 0 ? $images[0]['url'] : ''; ?>" alt="">
                                                    </div>
                                                </div>
                                                <h5 class="text-center">
                                                    <a href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE.'/?id='.$connection->property_id); ?>" class="text-orange">
                                                        <?php echo $connection->name; ?>
                                                    </a>
                                                </h5>
                                                <span class="cnt text-center">
                                                    Total Members: <?php echo $connection->members_count; ?>
                                                </span>
                                                <span class="cnt text-center">
                                                    Admin <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE.'/?id='.$connection->user_id); ?>" class="text-orange"><?php echo get_user_full_name($connection->user_id); ?></a>
                                                </span>
                                                <div class="btn-area d-flex justify-content-center">
                                                    <a href="<?php echo home_url("messages/?is_pool=true&with={$connection->id}"); ?>" class="btn btn-orange btn-sm rounded-pill me-1">Message</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
