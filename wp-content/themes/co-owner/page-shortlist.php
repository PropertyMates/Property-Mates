<?php
/**
 * Default shortlist file
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

/* --- FILTERS --- */
$view_mode = isset($_GET['view']) ? $_GET['view'] : 'list';
/* --- FILTERS --- */
?>
<div class="center-area">
    <div class="main-section bg-white py-20px border-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 inner-section-grey list-view-title">
                    <div class="title-area">
                        <h3 class="d-flex">
                            <?php the_title(); ?>
                            <div class="list-btns ms-auto d-none d-md-block">
                                <div class="btn-group">
                                    <a href="<?php echo get_view_url('list',CO_OWNER_SHORTLIST_PAGE); ?>" class="btn btn-orange list <?php echo ($view_mode == 'list'?'active':''); ?>">List View</a>
                                    <a href="<?php echo get_view_url('map',CO_OWNER_SHORTLIST_PAGE); ?>" class="btn btn-orange map <?php echo ($view_mode == 'map'?'active':''); ?>">Map View</a>
                                </div>
                            </div>
                        </h3>
                        <div class="list-btns ms-auto d-block d-md-none pt-3">
                            <div class="btn-group">
                                <a href="<?php echo get_view_url('list',CO_OWNER_SHORTLIST_PAGE); ?>" class="btn btn-orange list <?php echo ($view_mode == 'list'?'active':''); ?>">List View</a>
                                <a href="<?php echo get_view_url('map',CO_OWNER_SHORTLIST_PAGE); ?>" class="btn btn-orange map <?php echo ($view_mode == 'map'?'active':''); ?>">Map View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-section list-section py-20px">
        <div class="container">
            <?php the_content(); ?>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="border-5 border-bottom py-2">Property Listings</h4>
                </div>
            </div>
            <?php
            $properties = get_co_owner_shortlist_property_list();
            if(count($properties) > 0 ) : ?>
            <div class="row mt-4">
                <?php if($view_mode == 'map') : ?>
                    <div class="col-xxl-3 col-xl-4 col-md-5 col-sm-12 col-12">
                        <div class="row" style="max-height: 450px;overflow-y: scroll;">
                        <?php endif; ?>
                        <?php foreach ($properties as $key => $property) : ?>
                            <?php include('parts/property-box.php'); ?>
                        <?php endforeach; ?>
                        <?php if($view_mode == 'map') : ?>
                        </div>
                    </div>
                    <div class="col-xxl-9 col-xl-8 col-md-7 col-sm-12 col-12 map-section">
                        <div id="google-map-view" style="height: 450px;width: 100%;"></div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-12 mt-5">
                    <?php echo co_owner_paginate_links('property-shortlist'); ?>
                </div>
            </div>
            <?php else: ?>
                <div class="row my-5">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            You do not have any shortlisted property
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="main-section list-section py-20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="border-5 border-bottom py-2">Profiles</h4>
                </div>
            </div>
            <?php
            $persons = get_co_owner_shortlist_person_list();
            if(count($persons) > 0 ) : ?>
            <div class="row mt-4">
                <?php foreach ($persons as $key => $user) : ?>
                    <?php include('parts/person-box.php'); ?>
                <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-md-12 mt-5">
                    <?php echo get_co_owner_shortlist_person_list(true); ?>
                </div>
            </div>
            <?php else: ?>
            <div class="row my-5">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        You do not have any shortlisted profile 
                    </div>
                </div>
            </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
