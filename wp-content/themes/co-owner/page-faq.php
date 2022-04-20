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
$args = array(
    'post_type'     => 'faqs',
    "post_status"   => "publish",
    "nopaging"      => true
);
$faqs = get_posts($args);
?>
<div class="modal fade default-modal-custom default-small-modal" id="thank-you-feedback" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">

                <div class="row pt-30px pb-30px">
                    <div class="col-sm-12 col-12 text-center icon-36px">
                        <!--?xml version="1.0" encoding="utf-8"?-->
                        <!-- Generator: Adobe Illustrator 25.3.1, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 36 36" style="enable-background:new 0 0 36 36;" xml:space="preserve">
                            <style type="text/css">
                                .st20{fill:#F47421;}
                            </style>
                            <g>
                                <path class="st20" d="M26.93,12.53c-0.58-0.58-1.51-0.58-2.08,0l-9.46,9.46l-4.77-4.77c-0.58-0.58-1.51-0.58-2.08,0
                                    c-0.58,0.58-0.58,1.51,0,2.08l5.81,5.81c0.58,0.58,1.51,0.58,2.08,0c0,0,0,0,0,0c0.01-0.01,0.02-0.01,0.02-0.02l10.48-10.48
                                    C27.5,14.04,27.5,13.11,26.93,12.53z"></path>
                                <g>
                                    <path class="st20" d="M18,1.69c8.99,0,16.3,7.31,16.3,16.3s-7.31,16.3-16.3,16.3S1.7,26.98,1.7,17.99S9.01,1.69,18,1.69 M18,0.19
                                        c-9.83,0-17.8,7.97-17.8,17.8s7.97,17.8,17.8,17.8s17.8-7.97,17.8-17.8S27.83,0.19,18,0.19L18,0.19z"></path>
                                </g>
                            </g>
                            </svg>
                    </div>
                    <div class="col-sm-12 col-12 text-center pt-20px">
                        <h3>Thank you!<br>
                            for submitting your feedback.</h3>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade default-modal-custom default-small-modal" id="faq-feedback" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-12 d-flex">
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="col-sm-12 col-12 pb-4">
                        <h6>Feedback Form
                            <span class="d-block pt-2">Thank you for taking the time to provide feedback. We appreciate hearing from you and will review your comments carefully.</span>
                        </h6>
                    </div>

                    <form id="faq-feedback-form">
                        <input name="action" value="save_user_feedback" type="hidden">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
                            <div class="str-list w-100 d-block pb-20px">
                                <span class="d-block">1. <?php echo CO_OWNER_FEEDBACK_Q_1; ?></span>
                                <div class="star-main pt-10px">
                                    <div class="star-rating">
                                        <input type="radio" id="rate_1_5_stars" required name="rating_1" value="5" />
                                        <label for="rate_1_5_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_1_4_stars" name="rating_1" value="4" />
                                        <label for="rate_1_4_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_1_3_stars" name="rating_1" value="3" />
                                        <label for="rate_1_3_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_1_2_stars" name="rating_1" value="2" />
                                        <label for="rate_1_2_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_1_1_stars" name="rating_1" value="1" />
                                        <label for="rate_1_1_stars" class="star">&#9733;</label>
                                    </div>
                                </div>
                                <label id="rating_1-error" class="text-error" for="rating_1" style="display: none;"></label>
                            </div>

                            <div class="str-list w-100 d-block pb-20px">
                                <span class="d-block">2. <?php echo CO_OWNER_FEEDBACK_Q_2; ?></span>
                                <div class="star-main pt-10px">
                                    <div class="star-rating">
                                        <input type="radio" id="rate_2_5_stars" required name="rating_2" value="5" />
                                        <label for="rate_2_5_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_2_4_stars" name="rating_2" value="4" />
                                        <label for="rate_2_4_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_2_3_stars" name="rating_2" value="3" />
                                        <label for="rate_2_3_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_2_2_stars" name="rating_2" value="2" />
                                        <label for="rate_2_2_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_2_1_stars" name="rating_2" value="1" />
                                        <label for="rate_2_1_stars" class="star">&#9733;</label>
                                    </div>
                                </div>
                                <label id="rating_2-error" class="text-error" for="rating_2" style="display: none;"></label>
                            </div>

                            <div class="str-list w-100 d-block pb-20px">
                                <span class="d-block">3. <?php echo CO_OWNER_FEEDBACK_Q_3; ?></span>
                                <div class="star-main pt-10px">
                                    <div class="star-rating">
                                        <input type="radio" id="rate_3_5_stars" required name="rating_3" value="5" />
                                        <label for="rate_3_5_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_3_4_stars" name="rating_3" value="4" />
                                        <label for="rate_3_4_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_3_3_stars" name="rating_3" value="3" />
                                        <label for="rate_3_3_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_3_2_stars" name="rating_3" value="2" />
                                        <label for="rate_3_2_stars" class="star">&#9733;</label>
                                        <input type="radio" id="rate_3_1_stars" name="rating_3" value="1" />
                                        <label for="rate_3_1_stars" class="star">&#9733;</label>
                                    </div>
                                </div>
                                <label id="rating_3-error" class="text-error" for="rating_3" style="display: none;"></label>
                            </div>
                        </div>

                        <div class="col-sm-12 col-12 mb-4">
                            <span for="add-comment">Is there anything else you'd like to share about our product and customer experience?</span>
                            <textarea class="form-control" name="rating_comment" id="add-comment" rows="4" placeholder="Type here "></textarea>
                        </div>

                        <div class="col-sm-12 col-12 text-end bottom-btns">
                            <a href="#" data-bs-dismiss="modal" class="btn btn-orange-text rounded-pill">Cancel</a>
                            <button type="submit" class="btn btn-orange rounded-pill">Send</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="center-area">
    <div class="main-section bg-white public-title for-my-list py-20px">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 inner-section-grey list-view-title">
                    <div class="title-area pb-0">
                        <h3 class="d-flex align-items-center">
                            <span class="pe-2 f-size-30px">
                                <?php the_title(); ?>
                            </span>
                            <div class="align-items-center d-flex float-end ms-auto">
                                <a href="#" class="btn btn-orange btn-rounded pe-3 ps-3" type="button" data-bs-toggle="modal" data-bs-target="#faq-feedback">
                                    Help us improve
                                </a>
                            </div>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-section">
        <div class="container">
            <div class="row my-4">
                <div class="col-md-6">
                    <div class="input-group custom-contact-search">
                        <span class="input-group-text search"><?php echo co_owner_get_svg('search'); ?></span>
                        <input type="text" class="form-control search-input" aria-label="Amount (to the nearest dollar)" placeholder="Ask a question or search by keywords">
                        <a href="#" class="input-group-text contact-search-close close"><?php echo co_owner_get_svg('close-round'); ?></a>
                    </div>
                </div>
            </div>
            <div class="row my-4 mb-5">
                <?php if(count($faqs) > 0): ?>
                <div class="col-md-12 mb-5">
                    <div class="accordion faqs-accordions" id="accordion-faqs">
                        <?php foreach ($faqs as $key => $faq): ?>
                        <div class="accordion-item search-filter-faqs">
                            <h2 class="accordion-header" id="faq-accrdion-<?php echo $key; ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $key; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $key; ?>">
                                    <?php echo $faq->post_title; ?>
                                </button>
                            </h2>
                            <div id="collapse-<?php echo $key; ?>" class="accordion-collapse collapse collapsed" aria-labelledby="faq-accrdion-<?php echo $key; ?>" data-bs-parent="#accordion-faqs">
                                <div class="accordion-body">
                                    <?php echo apply_filters('the_content', $faq->post_content); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-md-12 mb-5">
                    <div class="card card-body">
                        <p class="mb-0">If you have any further questions, or you would like to share your feedback, we’d love to hear from you. Visit our <a href="<?php echo home_url(CO_OWNER_CONTACT_US_PAGE); ?>">contact us</a> page for more information on how to get in touch.</p>
                    </div>
                </div>
                <?php else: ?>
                <div class="col-md-12 text-center">
                    <h3 class="my-4">No Any Faqs</h3>
                </div>
                <?php endif;?>
            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>
