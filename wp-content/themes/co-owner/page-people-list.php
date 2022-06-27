<?php
/**
 * Default Person List file
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
$front_page = get_option( 'page_on_front' );
/* --- FILTERS --- */

$filters = array(
    'p_state' => isset($_GET['p_state']) ? $_GET['p_state'] : null,
    'p_budget' => isset($_GET['p_budget']) ? $_GET['p_budget'] : null,
    'p_order' => isset($_GET['p_order']) ? $_GET['p_order'] : null,
);


/* --- FILTERS --- */
?>
<div class="center-area">
    <div class="main-section bg-white py-20px border-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 inner-section-grey list-view-title">
                    <div class="title-area">
                        <h3 class="d-flex">
                            <?php echo get_post_meta($front_page,'_front_page_people_looking_for_properties_title',true); ?>
                        </h3>
                        <?php echo get_post_meta($front_page,'_front_page_people_looking_for_properties_description',true); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-section srt-section py-40px">
        <div class="container">
            <div class="row">
                <form action="<?php echo get_people_view_url(); ?>" method="get" id="property-view">
                    <div class="col-sm-12 d-md-flex">
                        <div class="title-select med-select">
                            <select name="p_budget" class="single-select2">
                                <option value="">Budget</option>
                                <?php foreach (get_price_dropdown_options() as $p_value => $p_key ): ?>
                                    <option <?php echo $filters['p_budget'] == $p_value ? 'selected' : null; ?>  value="<?php echo $p_value; ?>"><?php echo $p_key; ?></option>
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
                <?php the_content(); ?>
                <?php foreach (get_people_looking_for_properties_list(get_query_filters()) as $user) : ?>
                    <?php include ( 'parts/person-box.php' );?>
                <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-md-12 mt-5">
                    <?php echo co_owner_paginate_links('people-list'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
