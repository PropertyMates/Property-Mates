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

$filters = array(
    'view' => isset($_GET['view']) ? $_GET['view'] : null,
    'p_state' => isset($_GET['p_state']) ? $_GET['p_state'] : null,
    'p_price' => isset($_GET['p_price']) ? $_GET['p_price'] : null,
    'p_order' => isset($_GET['p_order']) ? $_GET['p_order'] : null,
);
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
                                    <a href="<?php echo get_view_url('list',CO_OWNER_PROPERTY_SEARCH_PAGE); ?>" class="btn btn-orange list <?php echo ($view_mode == 'list'?'active':''); ?>">List View</a>
                                    <a href="<?php echo get_view_url('map',CO_OWNER_PROPERTY_SEARCH_PAGE); ?>" class="btn btn-orange map <?php echo ($view_mode == 'map'?'active':''); ?>">Map View</a>
                                </div>
                            </div>
                        </h3>
                        <?php the_content(); ?>
                        <div class="list-btns ms-auto d-block d-md-none pt-3">
                            <div class="btn-group">
                                <a href="<?php echo get_view_url('list',CO_OWNER_PROPERTY_SEARCH_PAGE); ?>" class="btn btn-orange list <?php echo ($view_mode == 'list'?'active':''); ?>">List View</a>
                                <a href="<?php echo get_view_url('map',CO_OWNER_PROPERTY_SEARCH_PAGE); ?>" class="btn btn-orange map <?php echo ($view_mode == 'map'?'active':''); ?>">Map View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-section srt-section py-40px">
        <div class="container">
            <div class="row">
                <form action="<?php echo get_view_url($view_mode,CO_OWNER_PROPERTY_SEARCH_PAGE); ?>" method="get" id="property-view">
                    <input type="hidden" name="view" value="<?php echo $view_mode; ?>">
                    <?php if(isset($_GET['p_budget'])): ?>
                        <input type='hidden' name='p_budget' value='<?php echo $_GET['p_budget']; ?>'>
                    <?php endif; ?>
                    <?php if(isset($_GET['p_location'])): ?>
                        <input type='hidden' name='p_location' value='<?php echo $_GET['p_location']; ?>'>
                    <?php endif; ?>

                    <div class="col-sm-12 d-md-flex">
                        <div class="title-select med-select">
                            <select name="p_state" class="single-select2">
                                <option value="">State</option>
                                <?php foreach (get_all_states() as $state_code => $name ) : ?>
                                    <option <?php echo $filters['p_state'] == $state_code ? 'selected' : null; ?> value="<?php echo $state_code;?>"><?php echo $name;?></option>
                                <?php endforeach; ?>
                            </select>

                            <select name="p_price" class="single-select2">
                                <option value="">Price</option>
                                <?php foreach (get_price_dropdown_options() as $p_value => $p_key ): ?>
                                    <option <?php echo $filters['p_price'] == $p_value ? 'selected' : null; ?>  value="<?php echo $p_value; ?>"><?php echo $p_key; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="title-select ts-label med-select ms-auto">
                            <label>Sort by:</label>
                            <select name="p_order" class="single-select2">
                                <option <?php echo $filters['p_order'] == 'newest' ? 'selected' : null; ?> value="newest" >Newest</option>
                                <option <?php echo $filters['p_order'] == 'oldest' ? 'selected' : null; ?> value="oldest" >Oldest</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="main-section list-section py-20px">

        <div class="container">
            <div class="row mt-4">
                <?php if($view_mode == 'map') : ?>
                <div class="col-xxl-3 col-xl-4 col-md-5 col-sm-12 col-12">
                    <div class="row" style="max-height: 450px;overflow-y: scroll;">
                        <?php endif; ?>

                        <?php foreach (get_co_owner_property_list(get_query_filters(),false,'all') as $key => $property) : ?>
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
                    <?php echo co_owner_paginate_links_property('all'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
