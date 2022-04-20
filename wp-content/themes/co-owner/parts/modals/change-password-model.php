<div class="modal fade default-modal-custom" id="change-password-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form id="change-password-form" method="post">
                    <div class="row">
                        <input type="hidden" name="action" value="user_update_password">
                        <div class="col-sm-12 col-12 d-flex">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="col-sm-12 col-12 mb-4">
                            <h6 class="h6">Change Password</h6>
                        </div>

                        <div class="col-sm-12 col-12 mb-3">
                            <input name="old_password" class="form-control p" type="password" placeholder="Old password">
                        </div>
                        <div class="col-sm-12 col-12 mb-3">
                            <input name="new_password" id="new_password" class="form-control p" type="password" placeholder="New password">
                        </div>
                        <div class="col-sm-12 col-12 mb-3">
                            <input name="new_password_confirm" class="form-control p" type="password" placeholder="Confirm new password">
                        </div>

                        <div class="col-sm-12 col-12 my-3 text-end bottom-btns">
                            <a href="#" class="btn btn-orange-text rounded-pill" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-orange rounded-pill">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

