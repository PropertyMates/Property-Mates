<div class="modal fade member-modal-custom" id="my-members-modal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select from your connections</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group custom-contact-search mb-3">
                    <span class="input-group-text search"><?php echo co_owner_get_svg('search'); ?></span>
                    <input type="text" class="form-control search-input" aria-label="Amount (to the nearest dollar)" placeholder="Search by name">
                    <span class="input-group-text close"><?php echo co_owner_get_svg('close-round'); ?></span>
                </div>
                <div class="row <?php echo count($people_requested)>0 ? '' : 'd-none'; ?>">
                    <div class="col-sm-12 col-12 list-title">
                        <h6>People requested for the same pool</h6>
                    </div>
                    <?php foreach ($people_requested as $user_): ?>
                        <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12 pb-3 search-filter-member">
                            <div class="card member-card">
                                <div class="card-body">
                                    <div class="mbr-title d-flex w-100">
                                        <div class="form-check custom-check">
                                            <label class="form-check-label" for="flex-radio-default-<?php echo $user_->id;?>">
                                                <input value="<?php echo $user_->id;?>" class="form-check-input" type="radio" name="selected_member" id="flex-radio-default-<?php echo $user_->id;?>">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mbr-detail-area">
                                        <div class="<?php echo get_user_shield_status($user_->id) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''; ?> mt--46px">
                                            <div class="mbr-thumb mx-auto">
                                                <img src="<?php echo $user_->profile ?>" alt="">
                                            </div>
                                        </div>
                                        <h5 class="text-center"><?php echo $user_->name ?></h5>
                                        <span class="cnt text-center">
                                                <a href="mailto:<?php echo $user_->email ?>"><?php echo $user_->email ?></a>
                                            </span>
                                        <span class="cnt text-center"><?php echo $user_->mobile ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-12 list-title">
                        <h6>Other Connections</h6>
                    </div>
                    <?php foreach ($connected_users as $connected_user): ?>
                        <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12 pb-3 search-filter-member">
                            <div class="card member-card">
                                <div class="card-body">
                                    <div class="mbr-title d-flex w-100">
                                        <div class="form-check custom-check">
                                            <label class="form-check-label" for="flex-radio-default-<?php echo $connected_user->id;?>">
                                                <input value="<?php echo $connected_user->id;?>" class="form-check-input" type="radio" name="selected_member" id="flex-radio-default-<?php echo $connected_user->id;?>">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mbr-detail-area">
                                        <div class="<?php echo get_user_shield_status($connected_user->id) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''; ?> mt--46px">
                                            <div class="mbr-thumb mx-auto">
                                                <img src="<?php echo $connected_user->profile ?>" alt="">
                                            </div>
                                        </div>
                                        <h5 class="text-center"><?php echo $connected_user->name ?></h5>
                                        <span class="cnt text-center">
                                                <a href="mailto:<?php echo $connected_user->email ?>"><?php echo $connected_user->email ?></a>
                                            </span>
                                        <span class="cnt text-center"><?php echo $connected_user->mobile ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-orange-text" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-orange add-member-to-pool rounded-pill">Add to Pool</button>
            </div>
        </div>
    </div>
</div>
