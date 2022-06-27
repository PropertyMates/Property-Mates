<div class="modal fade default-modal-custom default-small-modal" id="edit-mobile-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <form action="" id="edit-mobile-form" data-message-sended="false" method="post">
                        <div class="col-sm-12 col-12 d-flex">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?php
                        $mobile = get_user_meta($user->ID,'_mobile',true);
                        ?>
                        <div class="col-sm-12 col-12 pb-4">
                            <h6><?php echo $mobile ? 'Edit Mobile Number' : 'Add Mobile Number' ?></h6>
                            <h5 class="text-teal pt-1 t-transform-none">Verification required</h5>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
                            <input name="mobile" id="mobile" class="form-control input-only-number" placeholder="Enter mobile number" value="<?php echo !empty($mobile) ? $mobile : null; ?>" data-oldmobile="<?php echo !empty($mobile) ? $mobile : null; ?>">
                            <label id="mobile-error" class="text-error" for="mobile" style="display: none;"></label>
                        </div>

                        <div class="col col-sm-12 col-12 mb-4 verify-code mobile-verify-code-input" id="mobile-verify-code-input" style="display: none;">
                            <div class="row">
                                <div class="col col-sm-12 col-12">
                                    <span class="otp-cnt">Verify with OTP sent to <span id="temp-email"></span></span>
                                </div>
                                <div class="col col-12 col-sm-12 d-flex otp-fld pt-2 align-items-center">
                                    <input type="text" id="mobile_code_1" class="form-control input-only-number ignore-bg next-focus" data-next-focus="#mobile_code_2" maxlength="1" name="mobile_code_1">
                                    <input type="text" id="mobile_code_2" class="form-control input-only-number ignore-bg next-focus" data-next-focus="#mobile_code_3" maxlength="1" name="mobile_code_2">
                                    <input type="text" id="mobile_code_3" class="form-control input-only-number ignore-bg next-focus" data-next-focus="#mobile_code_4" maxlength="1" name="mobile_code_3">
                                    <input type="text" id="mobile_code_4" class="form-control input-only-number ignore-bg" maxlength="1" name="mobile_code_4">
                                    <div class="ms-auto for-right-tx"><a href="#" class="resend-mobile-verification-code">Resend Code</a></div>
                                </div>
                                <div class="col col-12 col-sm-12">
                                    <label id="mobile_verify_code_error" class="validate-error"></label>
                                    <label id="mobile_code_1-error" class="text-error" for="mobile_code_1">.</label>
                                    <label id="mobile_code_2-error" class="text-error d-none" for="mobile_code_2">.</label>
                                    <label id="mobile_code_3-error" class="text-error d-none" for="mobile_code_3">.</label>
                                    <label id="mobile_code_4-error" class="text-error d-none" for="mobile_code_4">.</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-12 my-3 text-end bottom-btns">
                            <a href="#" class="btn btn-orange-text rounded-pill" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-orange rounded-pill">Continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
