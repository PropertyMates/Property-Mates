<div class="modal fade default-modal-custom" id="deactivate-or-delete-my-account-model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 d-flex">
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="confirm-to-action">
                        <div class="col-sm-12 col-12 pb-4">
                            <h6>Deactivate or Delete my account</h6>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
                            <div class="form-check form-check-inline custom-check normal">
                                <input value="deactivate" class="form-check-input" type="radio" checked name="delete_action" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <strong>Deactivate account (recommended)</strong>
                                    Deactivating your account means your listings will be taken down and your listings alerts will be turned off. You can still access your account but it will remain dormant untill your reactivate or create a new listing.
                                </label>
                            </div>
                            <div class="input-residential form-check custom-check normal">
                                <input value="delete" class="form-check-input" type="radio" name="delete_action" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    <strong>Delete account</strong>
                                    Deleting your account means that your account, connections and listings are permantly removed from the website.
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="submit-to-action deactivate" style="display: none;">
                        <div class="col-sm-12 col-12 pb-2"><h6>Are you sure you want to deactivate account?</h6></div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
                            <div class="form-check form-check-inline custom-check normal ps-0">
                                <label class="form-check-label">
                                    Deactivating your account means that your account, connections and listings are temporary removed from the website.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="submit-to-action delete" style="display: none;">
                        <div class="col-sm-12 col-12 pb-4"><h6>Are you sure you want to delete account?</h6></div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
                            <div class="form-check form-check-inline custom-check normal ps-0">
                                <label class="form-check-label">
                                    Deleting your account means that your account, connections and listings are permanently removed from the website.
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-12 text-end bottom-btns">
                        <a href="#" class="btn btn-orange-text rounded-pill confirm-to-action" data-bs-dismiss="modal">Cancel</a>
                        <button type="button" class="btn btn-orange rounded-pill confirm-to-action">Continue</button>
                        <button type="button" class="btn btn-orange rounded-pill submit-to-action action back">Back</button>
                        <button type="button" class="btn btn-orange rounded-pill submit-to-action action submit" style="display: none;">Yes, <span id="action">Delete</span> It</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
