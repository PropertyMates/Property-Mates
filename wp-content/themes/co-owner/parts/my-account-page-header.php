<div class="main-section bg-grey public-title for-my-list py-20px">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 inner-section-grey list-view-title">
                <div class="title-area pb-0">
                    <h3 class="d-flex align-items-center">
                        <span class="pe-2">
                            <?php
                            $user_id = get_current_user_id();
                            echo get_user_full_name($user_id);
                            if(get_user_shield_status($user_id)){
                                echo "&nbsp;<span class='user-shield-tooltip'>".co_owner_get_svg('big_shield')."</span>";
                            }
                            ?>
                        </span>

                        <div class="ms-auto">
                            <div class="custom-btn-area">
                                <a href="#"  data-bs-toggle="modal" data-bs-target="#why-upgrade" class="btn btn-black-text me-2 ">Why Upgrade?</a>
                                <a href="#" class="btn btn-orange btn-big rounded-pill" data-bs-target="#plan-modal" data-bs-toggle="modal">Upgrade</a>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal why-upgr fade default-modal-custom" id="why-upgrade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-body">


                         <div class="row">
                              <div class="col-sm-12 col-12 d-flex">
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                             </div>
                         </div>


                         <div class="row">
                        <div class="col-md-6 cret-listing-contnt">
                            <div class="create-more">
                                <h2>Create more listings. Unlock more benefits when you upgrade.</h2>
                                <h2><strong>Here's what you get with a standard plan.</strong></h2>

                            </div>
                            <ul class="creat-listin">
                                <li>Create up to 3 listings</li>
                                <li>Access contact details of users and make unlimited connections</li>
                                <li>Read, comment, and reply to property comments. Full use of forum.</li>
                                <li>Browse unlimited properties</li>
                            </ul>
                            <div class="upgrad-btn">
                                <a class="btn btn-orange btn-big action-pricing is-pricing-menu" data-bs-dismiss="modal" aria-label="Close" href="#">Upgrade now</a>
                            </div>
                        </div>

                        <div class="col-md-6 img-creat-listing">
                            <img class="desktop-img-list" src="<?php echo get_template_directory_uri(); ?>/images/crea-list.png">
                            <img class="mobile-img-list" src="<?php echo get_template_directory_uri(); ?>/images/crea-list-mobile.png">
                        </div>
                            
                       
                    </div>








                </div>
            </div>
        </div>
    </div>