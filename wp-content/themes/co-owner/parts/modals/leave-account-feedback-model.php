<div class="modal fade default-modal-custom" id="leave-account-feedback-model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-12 d-flex">
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="leave-account-feedback-form" method="post" action="#">
                        <div class="col-sm-12 col-12 pb-2">
                            <h6>Account has been deleted</h6>
                            <h5 class="text-teal pt-3 t-transform-none">Help us to serve you better</h5>
                            <h5 class="text-grey pt-3 t-transform-none">I am leaving because:</h5>
                        </div>

                        <div class="col-sm-12 col-12 mb-3">
                            <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">
                            <input type="hidden" name="action" value="send_leave_feedback">
                            <div class="input-residential form-check custom-check normal mb-1">
                                <input value="I Found Property/Partner" class="form-check-input" type="radio" checked name="leave_reason"id="radio1">
                                <label class="form-check-label" for="radio1">I Found Property/Partner</label>
                            </div>
                            <div class="input-residential form-check custom-check normal mb-1">
                                <input value="I am not interested anymore" class="form-check-input" type="radio" name="leave_reason" id="radio2">
                                <label class="form-check-label" for="radio2">I am not interested anymore</label>
                            </div>
                            <div class="input-residential form-check custom-check normal mb-1">
                                <input value="Other" class="form-check-input" type="radio" name="leave_reason" id="radio3">
                                <label class="form-check-label" for="radio3">Other</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-12 mb-4">
                            <textarea name="comment" class="form-control" placeholder="Add Comment" rows="3"></textarea>
                        </div>
                        <div class="col-sm-12 col-12 text-end bottom-btns">
                            <a href="#" class="btn btn-orange-text rounded-pill" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-orange rounded-pill">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
