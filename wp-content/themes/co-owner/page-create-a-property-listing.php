<?php
get_header();
$is_edit = isset($_GET['id']) && is_numeric($_GET['id']) ? true : false;
$property = get_property_detail();

$check_for_create = check_create_upto_listings_by_plan(get_current_user_id());

$input_disable = null;
$input_disable_class = null;
if($is_edit && $property->total_members_without_admin > 0){
    $message = "A member has already been added to your property pool so you cannot change it.";
    $input_disable = "title='{$message}' data-toggle='tooltip'";
    $input_disable_class = "property-input-disable";
}

?>
    <div class="center-area">
        <div class="main-section mt-5 pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-11 col-lg-11 col-md-12">
                        <div class="w-100 d-flex title-area">
                            <div class="me-auto">
                                <h3>Property Listing <?php echo $is_edit ? 'Edit' : null; ?></h3>
                                <span class="mnd-title">All fields are mandatory*</span>
                                <?php if(!$check_for_create && !$is_edit) { echo '<span class="mnd-title">Please update your subscription to create more listings.</span>'; }?>
                            </div>

                            <div class="switch-top">
                                <div class="float-start form-check form-switch mt-1" <?php echo $input_disable; ?>>
                                    <label class="form-check-label" for="enable_pool0">Enable Pool</label>
                                    <input class="form-check-input <?php echo $input_disable_class; ?>" type="checkbox" id="enable_pool0" <?php if($is_edit && @$property->enable_pool == 0){ ?> <?php }else{ ?> checked <?php } ?>  >
                                </div>
                            </div>
                        </div>

                        <div class="row" id="error-block" style="display:none;"></div>

                        <div class="accordion property-accordion" id="accordionExample">
                            <div class="accordion-item">
                                <form id="property-one">
                                    <?php if($is_edit): ?>
                                        <input type="hidden" name="property_id" value="<?php echo $property->ID; ?>">
                                    <?php endif; ?>
                                    <h2 class="accordion-header" id="headingOne">
                                        <button id="action-heading-one" class="accordion-button" type="button"  data-bs-target="#collapseOne" >
                                            STEP 1
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                                        <div class="accordion-body">
                                            <h4>Select Category</h4>
                                            <div class="btn-group property-radiobtn pb-5" role="group" aria-label="Basic radio toggle button group">
                                                <input <?php echo ($is_edit && $property->property_category == 'commercial') ? 'checked' : null; ?> type="radio" class="btn-check" name="_pl_property_category" id="commercial-btn" value="commercial" autocomplete="off" >
                                                <label class="btn btn-outline-orange commercial" for="commercial-btn">Commercial</label>

                                                <input <?php echo (($is_edit && $property->property_category == 'residential') || $is_edit == false) ? 'checked' : null; ?> type="radio" class="btn-check" name="_pl_property_category" id="residential-btn" value="residential" autocomplete="off">
                                                <label class="btn btn-outline-orange residential" for="residential-btn">Residential</label>
                                            </div>
                                            <label id="pl_property_category-error" class="text-error" for="pl_property_category"></label>
                                            <h5>Type</h5>
                                            <div class="input-residential form-check custom-check">
                                                <input <?php echo ($is_edit && $property->property_type == 'house' ? 'checked' : null);?> value="house" class="form-check-input" type="radio" name="_pl_property_type" id="flexRadioDefault1">
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    House
                                                </label>
                                            </div>
                                            <div class="input-residential form-check custom-check">
                                                <input <?php echo ($is_edit && $property->property_type == 'apartment' ? 'checked' : null);?> value="apartment" class="form-check-input" type="radio" name="_pl_property_type" id="flexRadioDefault2">
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Apartment
                                                </label>
                                            </div>
                                            <div class="input-residential form-check custom-check">
                                                <input <?php echo ($is_edit && $property->property_type == 'townhouse' ? 'checked' : null);?> value="townhouse" class="form-check-input" type="radio" name="_pl_property_type" id="flexRadioDefault3">
                                                <label class="form-check-label" for="flexRadioDefault3">
                                                    Townhouse
                                                </label>
                                            </div>
                                            <div class="input-residential form-check custom-check">
                                                <input <?php echo ($is_edit && $property->property_type == 'land' ? 'checked' : null);?> value="land" class="form-check-input" type="radio" name="_pl_property_type" id="flexRadioDefault4">
                                                <label class="form-check-label" for="flexRadioDefault4">
                                                    Land
                                                </label>
                                            </div>
                                            <div class="input-residential form-check custom-check">
                                                <input <?php echo ($is_edit && $property->property_type == 'retirement' ? 'checked' : null);?> value="retirement" class="form-check-input" type="radio" name="_pl_property_type" id="flexRadioDefault5">
                                                <label class="form-check-label" for="flexRadioDefault5">
                                                    Retirement
                                                </label>
                                            </div>

                                            <div class="input-commercial d-none form-check custom-check">
                                                <input <?php echo ($is_edit && $property->property_type == 'office' ? 'checked' : null);?> value="office" class="form-check-input" type="radio" name="_pl_property_type" id="pl-type-office">
                                                 <label class="form-check-label" for="pl-type-office">
                                                    Office - Office buildings,serviced offices
                                                </label>
                                            </div>
                                            <div class="input-commercial d-none form-check custom-check">
                                                <input <?php echo ($is_edit && $property->property_type == 'leisure' ? 'checked' : null);?> value="leisure" class="form-check-input" type="radio" name="_pl_property_type" id="pl-type-leisure">
                                                 <label class="form-check-label" for="pl-type-leisure">
                                                    Leisure - hotels,public houses, restaurants, cafes, sports facilities
                                                </label>
                                            </div>
                                            <div class="input-commercial d-none form-check custom-check">
                                                <input <?php echo ($is_edit && $property->property_type == 'retails' ? 'checked' : null);?> value="retails" class="form-check-input" type="radio" name="_pl_property_type" id="pl-type-retails">
                                                 <label class="form-check-label" for="pl-type-retails">
                                                    Retails - retail stores, shopping malls, shops
                                                </label>
                                            </div>
                                            <div class="input-commercial d-none form-check custom-check">
                                                <input <?php echo ($is_edit && $property->property_type == 'healthcare' ? 'checked' : null);?> value="healthcare" class="form-check-input" type="radio" name="_pl_property_type" id="pl-type-healthcare">
                                                 <label class="form-check-label" for="pl-type-healthcare">
                                                    Healthcare - medical centers, hospitals, nursing homes
                                                </label>
                                            </div>
                                            <div class="input-commercial d-none form-check custom-check">
                                                <input <?php echo ($is_edit && $property->property_type == 'multifamily' ? 'checked' : null);?> value="multifamily" class="form-check-input" type="radio" name="_pl_property_type" id="pl-type-multifamily">
                                                 <label class="form-check-label" for="pl-type-multifamily">
                                                    Multifamily - multifamily housing buildings (apartments)
                                                </label>
                                            </div>

                                            <label id="_pl_property_type-error" class="text-error" for="_pl_property_type"></label>



                                            <h5 class="mt-4">Posted By</h5>
                                            <div class="w-100 custom-select">
                                                <select name="_pl_posted_by" class="single-select2 w-100" data-search="false">
                                                    <option value="Owner">Owner</option>
                                                    <option <?php if(@$property->_pl_posted_by == "Agent"){ ?> selected="selected" <?php } ?> value="Agent">Agent/Non Owner</option>
                                                </select>
                                            </div>


                                            <div class="pt-4">
                                                <a href="#" id="next-heading-one" class="btn rounded-pill btn-orange btn-sm w-80px">Next</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="accordion-item">
                                <form id="property-two">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button id="action-heading-two" class="accordion-button collapsed" type="button" data-bs-target="#collapseTwo" >
                                            STEP 2
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                                        <div class="accordion-body">
                                            <h4>Add Property Info</h4>

                                            <div class="row">
                                                <div class="col-sm-12 col-12 mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label d-flex align-items-baseline">
                                                        Listing Heading
                                                        <span class="ms-auto character-counter">
                                                            <span class="character-length"><?php echo ($is_edit && $property->post_title) ? strlen($property->post_title) : 0; ?></span>/200
                                                        </span>
                                                    </label>
                                                    <input value="<?php echo ($is_edit && $property->post_title) ? $property->post_title : null; ?>" type="text" name="_pl_heading" maxlength="200" class="form-control count-character" id="exampleFormControlInput1" placeholder="LISTING HEADING">
                                                </div>
												
												<?php if($is_edit && $property->post_status != 'publish'){ ?>
						<input type="hidden" name="_pl_review_post" value="preview"/>
						<?php }else{ ?>
						<input type="hidden" name="_pl_review_post" value="notpreview"/>
						<?php } ?>

                                                <div class="col-sm-12 col-12 mb-3">
                                                    <label for="exampleFormControlTextarea1" class="form-label d-flex align-items-baseline">
                                                        Description
                                                        <span class="ms-auto character-counter">
                                                            <span class="character-length"><?php echo ($is_edit && $property->post_content) ? strlen($property->post_content) : 0; ?></span>/3000
                                                        </span>
                                                    </label>
                                                    <textarea class="form-control count-character" maxlength="3000" name="_pl_descriptions" id="exampleFormControlTextarea1" placeholder="ADD DESCRIPTION" rows="3"><?php echo ($is_edit && $property->post_content) ? $property->post_content : null; ?></textarea>
                                                </div>
                                            </div>



                                            <div class="row address-management">
                                                <div class="col-md-12 col-12">
                                                    <h5 class="pt-4">
                                                        Address
                                                        <a href="#" class="add-manually-property-address btn btn-orange btn-rounded">
                                                            <?php echo $is_edit && $property->address_manually ? 'Add By Suggestion' : 'Add Manually'; ?>
                                                        </a>
                                                        <input type="hidden" name="_pl_address_manually" value="<?php echo $is_edit && $property->address_manually ? 'true' : 'false'; ?>">
                                                    </h5>
                                                </div>

                                                <div class="address-by-suggest col-md-12 col-12 mb-3" style="display:<?php echo $is_edit && $property->address_manually ? 'none' : 'block'; ?>;">
                                                    <label for="_pl_address" class="form-label">Address</label>
                                                    <div class="w-100 custom-select">
                                                        <select class="select2-property-address-api" name="_pl_address" id="_pl_address" style="width: 100%;">
                                                            <?php if($is_edit && $address = get_property_full_address($property->ID,true)): ?>
                                                            <option selected value="<?php echo $address; ?>"><?php echo $address; ?></option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                    <label id="_pl_address-error" class="text-error" for="_pl_address"></label>
                                                    <div class="d-flex align-items-center control-bottom">Only display suburb in my address.
                                                        <div class="ms-auto switch-custom">
                                                            <div class="form-check form-switch">
                                                                <input <?php echo ($is_edit) ? ($property->only_display_suburb_in_my_ad ? 'checked' : '') : '' ?> class="form-check-input only-display-suburb-1" type="checkbox">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="address-manually col-sm-6 col-12 mb-3" style="display: <?php echo $is_edit && $property->address_manually ? 'block' : 'none'; ?>;">
                                                    <label for="pl-unit-no" class="form-label">Unit No</label>
                                                    <input maxlength="10" value="<?php echo ($is_edit && $property->unit_no) ? $property->unit_no : null ?>" name="_pl_unit_no" type="text" class="form-control ignore-validate" id="pl-unit-no" placeholder="UNIT NO">
                                                </div>

                                                <div class="address-manually col-sm-6 col-12 mb-3" style="display: <?php echo $is_edit && $property->address_manually ? 'block' : 'none'; ?>;">
                                                    <label for="exampleFormControlInput3" class="form-label">Suburb</label>
                                                    <input maxlength="20" value="<?php echo ($is_edit && $property->suburb) ? $property->suburb : null ?>" name="_pl_suburb" type="text" class="form-control ignore-validate" id="exampleFormControlInput3" placeholder="SUBURB">
                                                    <div class="d-flex align-items-center control-bottom">Only display suburb in my address.
                                                        <div class="ms-auto switch-custom">
                                                            <div class="form-check form-switch">
                                                                <input <?php echo ($is_edit) ? ($property->only_display_suburb_in_my_ad ? 'checked' : '') : '' ?> name="_pl_only_display_suburb_in_my_ad" class="form-check-input only-display-suburb-2" type="checkbox" id="flexSwitchCheckChecked">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="address-manually col-sm-6 col-12 mb-3" style="display: <?php echo $is_edit && $property->address_manually ? 'block' : 'none'; ?>;">
                                                    <label for="pl-street-no" class="form-label">Street No</label>
                                                    <input maxlength="10" value="<?php echo ($is_edit && $property->street_no) ? $property->street_no : null ?>" name="_pl_street_no" type="text" class="form-control ignore-validate" id="pl-street-no" placeholder="STREET NO">
                                                </div>

                                                <div class="address-manually col-sm-6 col-12 mb-3" style="display: <?php echo $is_edit && $property->address_manually ? 'block' : 'none'; ?>;">
                                                    <label for="pl-postcode" class="form-label">Postcode</label>
                                                    <input maxlength="20" value="<?php echo ($is_edit && $property->postcode) ? $property->postcode : null ?>" name="_pl_postcode" type="text" class="form-control ignore-validate" id="pl-postcode" placeholder="POSTCODE">
                                                </div>

                                                <div class="address-manually col-sm-6 col-12 mb-3" style="display: <?php echo $is_edit && $property->address_manually ? 'block' : 'none'; ?>;">
                                                    <label for="exampleFormControlInput4" class="form-label">Street Name</label>
                                                    <input maxlength="30" value="<?php echo ($is_edit && $property->street_name) ? $property->street_name : null ?>" name="_pl_street_name" type="text" class="form-control ignore-validate" id="exampleFormControlInput4" placeholder="STREET NAME">
                                                </div>

                                                <div class="address-manually col-sm-6 col-12 mb-3" style="display: <?php echo $is_edit && $property->address_manually ? 'block' : 'none'; ?>;">
                                                    <label for="exampleFormControlInput5" class="form-label">State</label>
                                                    <div class="w-100 custom-select">
                                                        <select name="_pl_state" class="form-control ignore-validate single-select2" aria-label="Default select example" style="width: 100%;" id="exampleFormControlInput5" >
                                                            <option value="">SELECT STATE</option>
                                                            <?php foreach(get_all_states() as $value => $name): ?>
                                                            <option <?php echo ($is_edit && $property->state == $value) ? 'selected' : null ?> value="<?php echo $value; ?>"><?php echo $name; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <label id="exampleFormControlInput5-error" class="text-error" for="exampleFormControlInput5"></label>
                                                </div>
                                            </div>


                                            <h5 class="pt-4">Add Photos</h5>
                                            <div class="row pl-preview-images-box">
                                                <?php if($is_edit): ?>
                                                <?php foreach (is_array($property->images) ? $property->images : [] as $key => $image): ?>
												<?php $filename_from_url = parse_url($image['url']);
                           $ext = pathinfo($filename_from_url['path'], PATHINFO_EXTENSION); 
						   if($ext){
						   ?>
                                                <div class="col-md-4 col-sm-12 col-12 pb-3">
                                                    <div class="property-up-main w-100 d-block">
                                                        <img src="<?php echo $image['url']; ?>" class="img-fluid">
                                                    </div>
                                                    <a href="#" class="text-danger delete-property-image" data-index="<?php echo $key; ?>" data-property-id="<?php echo $property->ID; ?>">Remove</a>
                                                </div>
												 <?php } ?>
                                                <?php endforeach; ?>
                                                <?php endif; ?>


                                                <div class="col-md-4 col-sm-12 col-12 pb-3">
                                                    <div class="file-field" id="resumable-drop-container">
                                                        <div class="btn-floating text-center" id="uploader">
                                                            <div class="file-icon">
                                                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve">
                                                                    <style type="text/css">.st0{fill:#0A7E80;}</style><path class="st0" d="M28,18.1v6.4h0v2.2c0,0.8-0.7,1.5-1.5,1.5h-23c-0.8,0-1.5-0.7-1.5-1.5v-7.1l0-1.5l-1.9,0V27c0,1.7,1.3,3,3,3H27c1.7,0,3-1.3,3-3v-8.9H28z"/><path class="st0" d="M22.6,7.9L15.8,1c-0.4-0.4-1-0.4-1.4,0l-7,6.7l1.4,1.4l5.5-5.3v18.1h1.8V4.1l5.1,5.2L22.6,7.9z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="file-cnt text-center" >
                                                                Drag & Drop your image or <span id="browse-button">Browse</span>
                                                                <br>
                                                                <small>You can upload maximum size of <?php echo CO_OWNER_PROPERTY_IMAGE_LIMIT_MB / 1000 / 1000 ; ?>mb.</small>
                                                            </div>
                                                            <label id="pl-images-input-error" class="text-error" for="pl-images-input"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="pt-4">
                                                <a href="#" id="next-heading-two" class="btn rounded-pill btn-orange btn-sm w-80px">Next</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="accordion-item">
                                <form id="property-three">
                                <h2 class="accordion-header" id="headingThree">
                                    <button id="action-heading-three" class="accordion-button collapsed" type="button" data-bs-target="#collapseThree" >
                                        STEP 3
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
                                    <div class="accordion-body">
                                        <h4>Add Specifications & Features</h4>

                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 col-12 mb-3">
                                                <label for="exampleFormControlInput6" class="form-label">Land Area</label>
                                                <input maxlength="25" value="<?php echo ($is_edit && $property->land_area) ? $property->land_area : null; ?>" name="_pl_land_area" type="text" class="form-control" id="exampleFormControlInput6" placeholder="Land Area">
                                            </div>

                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <label for="exampleFormControlInput7" class="form-label">Building Area</label>
                                                <input maxlength="25" value="<?php echo ($is_edit && $property->building_area) ? $property->building_area : null; ?>" name="_pl_building_area" type="text" class="form-control" id="exampleFormControlInput7" placeholder="Building Area">
                                            </div>

                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <label for="exampleFormControlInput8" class="form-label">Age/year built</label>
                                                <div class="w-100 custom-select">
												
                                                    <select name="_pl_age_year_built" class="form-select single-select2" aria-label="Default select example" id="exampleFormControlInput8">
                                                        <option value="">Select Age/year built</option>
                                                        <?php for($i=1;$i<=100;$i++): ?>
														<?php if($i==1){ ?>
                                                        <option <?php if(@$property->_pl_age_year_built == "<1"){ ?> selected <?php } ?> value="<1"> <1 </option>  <?php } ?>
														<option <?php echo ($is_edit && $property->age_year_built == $i) ? 'selected' : null; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                    <label id="exampleFormControlInput8-error" class="text-error" for="exampleFormControlInput8"></label>
                                                </div>
                                            </div>


                                            <div class="input-residential col-md-4 col-sm-12 mb-3">
                                                <label for="pl-bedroom" class="form-label">Bedroom(s)</label>
                                                <div class="d-flex align-items-center room-counter">
                                                    <a href="#" class="text-center counter-minus">-</a>
                                                    <input data-max="99" max="99" value="<?php echo ($is_edit && $property->bedroom) ? $property->bedroom : 0; ?>" name="_pl_bedroom" type="text" class="form-control" id="pl-bedroom" placeholder="" value="0">
                                                    <a href="#" class="text-center counter-plus">+</a>
                                                </div>
                                                <label id="pl-bedroom-error" class="text-error" for="pl-bedroom"></label>
                                            </div>

                                            <div class="input-residential col-md-4 col-sm-12 mb-3">
                                                <label for="pl-bathroom" class="form-label">Bathroom(s)</label>
                                                <div class="d-flex align-items-center room-counter">
                                                    <a href="#" class="text-center counter-minus">-</a>
                                                    <input data-max="99" max="99" value="<?php echo ($is_edit && $property->bathroom) ? $property->bathroom : 0; ?>" name="_pl_bathroom" type="text" class="form-control" id="pl-bathroom" placeholder="" value="0">
                                                    <a href="#" class="text-center counter-plus">+</a>
                                                </div>
                                                <label id="pl-bathroom-error" class="text-error" for="pl-bathroom"></label>
                                            </div>

                                            <div class="input-residential col-md-4 col-sm-12 mb-3">
                                                <label for="pl-parking" class="form-label">Parking</label>
                                                <div class="d-flex align-items-center room-counter">
                                                    <a href="#" class="text-center counter-minus">-</a>
                                                    <input data-max="99" max="99" value="<?php echo ($is_edit && $property->parking) ? $property->parking : 0; ?>" name="_pl_parking" type="text" class="form-control" id="pl-parking" placeholder="" value="0">
                                                    <a href="#" class="text-center counter-plus">+</a>
                                                </div>
                                                <label id="pl-parking-error" class="text-error" for="pl-parking"></label>
                                            </div>
                                        </div>

                                        <h5 class="pt-4 input-residential">Property Features</h5>

                                        <div class="row input-residential">
                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php echo ($is_edit && is_array($property->property_features) && in_array('Air Conditioning',$property->property_features)) ? 'checked' : null ?> name="_pl_property_features[]" class="form-check-input" type="checkbox" value="Air Conditioning" id="_pl_property_features[]">
                                                    <label class="form-check-label" for="_pl_property_features[]">
                                                        Air Conditioning
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php echo ($is_edit && is_array($property->property_features) && in_array('Build in wardrobes',$property->property_features)) ? 'checked' : null ?> name="_pl_property_features[]" class="form-check-input" type="checkbox" value="Build in wardrobes" id="flexCheckChecked1">
                                                    <label class="form-check-label" for="flexCheckChecked1">
                                                        Build in wardrobes
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php echo ($is_edit && is_array($property->property_features) && in_array('Floorboards',$property->property_features)) ? 'checked' : null ?> name="_pl_property_features[]" class="form-check-input" type="checkbox" value="Floorboards" id="flexCheckChecked2">
                                                    <label class="form-check-label" for="flexCheckChecked2">
                                                        Floorboards
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php echo ($is_edit && is_array($property->property_features) && in_array('Gas',$property->property_features)) ? 'checked' : null ?> name="_pl_property_features[]" class="form-check-input" type="checkbox" value="Gas" id="flexCheckChecked3">
                                                    <label class="form-check-label" for="flexCheckChecked3">
                                                        Gas
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php echo ($is_edit && is_array($property->property_features) && in_array('Swimming Pool',$property->property_features)) ? 'checked' : null ?> name="_pl_property_features[]" class="form-check-input" type="checkbox" value="Swimming Pool" id="flexCheckChecked4">
                                                    <label class="form-check-label" for="flexCheckChecked4">
                                                        Swimming Pool
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php echo ($is_edit && is_array($property->property_features) && in_array('Furnished',$property->property_features)) ? 'checked' : null ?> name="_pl_property_features[]" class="form-check-input" type="checkbox" value="Furnished" id="flexCheckChecked5">
                                                    <label class="form-check-label" for="flexCheckChecked5">
                                                        Furnished
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php echo ($is_edit && is_array($property->property_features) && in_array('Indoor Gym',$property->property_features)) ? 'checked' : null ?> name="_pl_property_features[]" class="form-check-input" type="checkbox" value="Indoor Gym" id="flexCheckChecked6">
                                                    <label class="form-check-label" for="flexCheckChecked6">
                                                        Indoor Gym
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php echo ($is_edit && is_array($property->property_features) && in_array('Alarm System',$property->property_features)) ? 'checked' : null ?> name="_pl_property_features[]" class="form-check-input" type="checkbox" value="Alarm System" id="flexCheckChecked7">
                                                    <label class="form-check-label" for="flexCheckChecked7">
                                                        Alarm System
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php echo ($is_edit && is_array($property->property_features) && in_array('Dishwasher',$property->property_features)) ? 'checked' : null ?> name="_pl_property_features[]" class="form-check-input" type="checkbox" value="Dishwasher" id="flexCheckChecked8">
                                                    <label class="form-check-label" for="flexCheckChecked8">
                                                        Dishwasher
                                                    </label>
                                                </div>
                                            </div>
                                            <label id="_pl_property_features[]-error" class="text-error" for="_pl_property_features[]"></label>

                                            <div class="col-sm-12 col-12 mb-3">
                                                <label for="exampleFormControlInput9" class="form-label">Add features manually</label>
                                                <div class="w-100 custom-select">
                                                    <select name="_pl_manually_features[]" id="exampleFormControlInput9" class="form-control select2-multiple-taggable w-100" multiple="multiple">
                                                        <?php if($is_edit && is_array($property->manually_features)): ?>
                                                            <?php foreach ($property->manually_features as $value): ?>
                                                            <option selected value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <label id="exampleFormControlInput9-error" class="text-error" for="exampleFormControlInput9"></label>
                                                <div class="d-flex align-items-center control-bottom">Instructions: To add multiple features in the list, type feature name and then add comma [ , ] and press enter.</div>
                                            </div>

                                        </div>

                                        <div class="pt-4">
                                            <a href="#" id="next-heading-three" class="btn rounded-pill btn-orange btn-sm w-80px">Next</a>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>

                            <div class="accordion-item">
                                <form id="property-four">
                                <h2 class="accordion-header" id="headingFour">
                                    <button id="action-heading-four" class="accordion-button collapsed" type="button" data-bs-target="#collapseFour" >
                                        STEP 4
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour">
                                    <div class="accordion-body">
                                        <h4>Set Pricing</h4>

                                        <div class="row">
                                            <div class="col-sm-12 col-12 mb-3">
                                                <label class="form-label d-block mb-3">Interested in Selling</label>
                                                <div class="form-check form-check-inline custom-check" <?php echo $input_disable; ?>>
                                                    <input <?php echo ($is_edit && $property->interested_in_selling == 'full_property' ? 'checked' : (!$is_edit ? 'checked': null)); ?> value="full_property" class="<?php echo $input_disable_class; ?> form-check-input" type="radio" name="_pl_interested_in_selling" id="interested_in_selling0">
                                                    <label class="form-check-label" for="interested_in_selling0">
                                                        Full Property
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline custom-check" <?php echo $input_disable; ?>>
                                                    <input <?php echo ($is_edit && $property->interested_in_selling == 'portion_of_it' ? 'checked' : null); ?> value="portion_of_it" class="<?php echo $input_disable_class; ?> form-check-input" type="radio" name="_pl_interested_in_selling" id="interested_in_selling1">
                                                    <label class="form-check-label" for="interested_in_selling1">
                                                        Portion of it [% Portion]
                                                    </label>
                                                </div>
                                                <label id="_pl_interested_in_selling-error" class="text-error" for="_pl_interested_in_selling"></label>
                                            </div>

                                            <div class="input-residential col-sm-12 col-12 mb-3">
                                                <label class="form-label d-block">This Property Is</label>
                                                <div class="form-check form-check-inline custom-check" <?php echo $input_disable; ?>>
                                                    <input <?php echo ($is_edit && $property->this_property_is == 'Investment' ? 'checked' : (!$is_edit ? 'checked': null)); ?> name="_pl_this_property_is" value="Investment" class="<?php echo $input_disable_class; ?> form-check-input" type="radio" id="flexRadioDefault8">
                                                    <label class="form-check-label" for="flexRadioDefault8">
                                                        Investment
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-inline custom-check" <?php echo $input_disable; ?>>
                                                    <input <?php echo ($is_edit && $property->this_property_is == 'Currently occupied by owner' ? 'checked' : null); ?> name="_pl_this_property_is" value="Currently occupied by owner" class="<?php echo $input_disable_class; ?> form-check-input" type="radio" id="flexRadioDefault9">
                                                    <label class="form-check-label" for="flexRadioDefault9">
                                                        Currently occupied by owner
                                                    </label>
                                                </div>
                                                <label id="_pl_this_property_is-error" class="mb-3 text-error" for="_pl_this_property_is"></label>
                                            </div>

                                            <div class="col-sm-12 col-12 mb-3">
                                                <label class="form-label d-block mb-3">Currently Leased</label>
                                                <div class="form-check form-check-inline custom-check">
                                                    <input <?php echo ($is_edit && $property->currently_on_leased == 'Yes' ? 'checked' : null); ?> name="_pl_currently_on_leased" value="Yes" class="form-check-input" type="radio" id="flexRadioDefault10">
                                                    <label class="form-check-label" for="flexRadioDefault10">
                                                        Yes
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-inline custom-check">
                                                    <input <?php echo ($is_edit && $property->currently_on_leased == 'No' ? 'checked' : (!$is_edit ? 'checked': null)); ?> name="_pl_currently_on_leased" value="No" class="form-check-input" type="radio" id="flexRadioDefault11">
                                                    <label class="form-check-label" for="flexRadioDefault11">
                                                        No
                                                    </label>
                                                </div>

                                                <label id="_pl_currently_on_leased-error" class="text-error" for="_pl_currently_on_leased"></label>
                                            </div>
                                        </div>

                                        <div class="row pl-rent-per-month" style="display: <?php echo ($is_edit && $property->currently_on_leased == 'Yes' ? '' : 'none;') ?>;">
                                            <div class="col-md-4 col-sm-12 col-12 mb-3">
                                                <label for="rent_per_month" class="form-label">RENT PER MONTH</label>
                                                <input value="<?php echo ($is_edit && $property->rent_per_month) ? $property->rent_per_month : (!$is_edit ? 0 : null); ?>" name="_pl_rent_per_month" type="text" class="form-control input-only-price" id="rent_per_month" placeholder="$">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12 col-12 mb-3">
                                                <div class="card text-dark bg-light">
                                                    <div class="card-body" <?php echo $input_disable; ?>>
                                                        <h6 class="card-title">Enable Pool</h6>
                                                        <p class="card-text">Enable it if you want to create a pool of this property so multiple investors can buy your property portion. To understand more about the pool, Please read our <a href="<?php echo home_url(CO_OWNER_FAQS_PAGE."?search=what is a property pool"); ?>" class="text-orange" target="_blank">FAQ</a> section which explains how Pool works.</p>
                                                        <div class="switch-top pt-3">
                                                            <div class="form-check form-switch">
                                                                <input name="_pl_enable_pool" class="<?php echo $input_disable_class; ?> form-check-input float-start"  type="checkbox" id="enable_pool" 
																<?php if($is_edit && @$property->enable_pool == 0){ ?> <?php }else{ ?> checked <?php } ?>
																>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>Price Information</h5>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-12 col-12 view-market-value-of-this-property"></div>
                                                </div>
                                            </div>


                                            <div <?php echo $input_disable; ?> class="col-md-4 col-sm-12 col-12 mb-3">
                                                <label for="property_market_price" class="form-label">Property Market price ($)</label>
                                                <input maxlength="20" value="<?php echo ($is_edit &&  $property->property_market_price) ? ( $property->interested_in_selling == 'full_property' ? $property->property_market_price : $property->property_original_price ) : null; ?>" name="_pl_property_market_price" type="text" class="<?php echo $input_disable_class; ?> form-control input-only-price property-value-input" data-property-share-input=".property-share-input" data-calculated-value-input=".calculated-value-input" id="property_market_price" placeholder="Property Market price">
                                                <div class="align-items-center control-bottom">
                                                    <h4 class="m-0 p-0 preview-market-value-of-this-property py-3" style="display: none;"></h4>
                                                    <a href="<?php echo WWW_DOMAIN_COM_AU_PROPERTY_PROFILE; ?>" target="_blank" class="fs-6">Get real market value</a>
                                                    <h2 class="text-black-50 d-none">
                                                        <div class="domain-logo">
                                                            <span>Powered by</span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 97 22"><path class="domain-logo__svg-icon-tow" fill="#0ea800" d="M72.51 18.27v-7c0-4.28-2.63-5.51-6-5.51-3.62 0-6 1.89-6 5h2.79c0-1.55 1-2.42 3.29-2.42 2.49 0 3.21 1.08 3.21 2.09 0 .84-.18 1.29-1.65 1.56l-3.32.63c-2.91.54-5 1.86-5 4.91 0 2.82 2.34 4.47 5 4.47A6.15 6.15 0 0070 19.62V20a1.54 1.54 0 001.41 1.66 1.37 1.37 0 00.35 0H74v-2.34h-.36c-.86 0-1.13-.32-1.13-1.05zm-2.66-2.69c0 2.09-1.77 3.89-4.53 3.89-1.64 0-2.54-.84-2.54-2.22s1-2 3.11-2.48l2.67-.57a4.21 4.21 0 001.29-.45zM7 .12H0V21.6h6.71c6.62 0 11-4.53 11-10.76C17.7 3.83 12.67.12 7 .12zm-.29 18.81H2.94V2.82h3.47c4.91 0 8.33 2.66 8.33 8s-3.69 8.11-8.03 8.11zM75.66 6.15h2.68V21.6h-2.68zM87.87 5.75a5.53 5.53 0 00-4.42 2v-1.6h-2.68V21.6h2.68v-9.75a3.73 3.73 0 016-2.95 4 4 0 01.48.44l.07.09a3.68 3.68 0 01.89 2.41v9.76h2.68v-9.53c.02-3.98-2.1-6.32-5.7-6.32zM77 0a1.81 1.81 0 101.82 1.8A1.82 1.82 0 0077 0zM52.18 5.48L47.4 8.73l-4.8-3.25-3.45 2.33V6.15h-2.68V21.6h2.68V11.06l3.45-2.34 3.43 2.34V21.6h2.68V11.06l3.47-2.34 3.46 2.34V21.6h2.68V9.62l-6.14-4.14zM27 5.74a7.93 7.93 0 00-8.08 7.78v.36a7.91 7.91 0 007.7 8.12H27a7.91 7.91 0 008-7.82v-.3a7.93 7.93 0 00-7.72-8.14zm0 13.54a5.41 5.41 0 115.4-5.42 5.42 5.42 0 01-5.4 5.42z" fill-rule="evenodd"></path></svg>
                                                        </div>
                                                    </h2>
                                                </div>
                                            </div>


                                            <div <?php echo $input_disable; ?> class="input-portion-of-it col-md-4 col-sm-12 mb-3">
                                                <label for="i_want_to_sell" class="form-label">I want to sell %</label>
                                                <div class="custom-select">
                                                    <select name="_pl_i_want_to_sell" class="<?php echo $input_disable_class ?> single-pr-select2 form-select property-share-input" data-property-value-input=".property-value-input" data-calculated-value-input=".calculated-value-input"  aria-label="Default select example" id="i_want_to_sell">
                                                        <option value="">Select I Want To Sell</option>
                                                        <?php
                                                            $array_ = array();
                                                            for ($i = 1; $i <= 99; $i++):
                                                                $array_[] = $i;
                                                        ?>
                                                            <option <?php echo ($is_edit && $property->i_want_to_sell == $i ) ? 'selected' : null; ?> value="<?php echo $i; ?>"><?php echo $i; ?>%</option>
                                                        <?php endfor; ?>
                                                        <?php if($is_edit && !in_array($property->i_want_to_sell,$array_) && $property->interested_in_selling == 'portion_of_it'): ?>
                                                            <option selected value="<?php echo $property->i_want_to_sell; ?>"><?php echo $property->i_want_to_sell; ?>%</option>
                                                        <?php endif; ?>

                                                    </select>

                                                    <label id="i_want_to_sell-error" class="text-error" for="i_want_to_sell"></label>
                                                </div>
                                            </div>

                                            <div <?php echo $input_disable; ?> class="input-portion-of-it col-md-4 col-sm-12 col-12 mb-3">
                                                <label for="Calculated" class="<?php echo $input_disable_class; ?> form-label">Calculated</label>
                                                <input value="<?php echo ($is_edit && $property->calculated ) ? $property->calculated : null; ?>" name="_pl_calculated" readonly type="text" class="form-control input-only-price calculated-value-input" id="Calculated" placeholder="Calculated">
                                            </div>

                                            <div class="col-md-12 col-sm-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php echo ($is_edit) ? ( $property->negotiable ? 'checked' : null ) : 'checked'?> name="_pl_negotiable" class="form-check-input" type="checkbox" value="Negotiable" id="negotiable">
                                                    <label class="form-check-label" for="negotiable">
                                                        Negotiable
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="controls-section">
        <div class="container">
            <div class="align-items-center d-flex row">
                <div class="col-4 col-sm-4 pb-3">
                    <?php if(($is_edit && $property->post_status == 'publish') || ($check_for_create || $is_edit)): ?>
                        <?php if(!$is_edit): ?>
                            <a href="#" class="btn btn-orange rounded-pill px-4" id="preview-button" style="display: <?php echo $is_edit ? 'unset;' : 'none;'; ?>">Preview</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <a id="property-form-submit" href="#" data-is-preview="false" class="d-none"></a>
                <div class="col-7 col-sm-7 pb-3 px-0">
                    <div class="d-flex float-end align-items-center">
                        <a href="<?php echo empty(wp_get_referer()) ? home_url(CO_OWNER_MY_LISTINGS_PAGE) : wp_get_referer(); ?>" class="btn btn-link rounded-pill cancel_alert">Cancel</a>
                        <?php if(($is_edit && $property->post_status == 'publish') || ($check_for_create || $is_edit)): ?>
                            <?php if($check_for_create || $is_edit): ?>
                                <a href="#" class="btn btn-orange rounded-pill submit-co-owner-property-form <?php if($is_edit && $property->post_status == 'publish'){ ?>btn_list_disabled <?php }  ?>"><?php echo $is_edit && $property->post_status == 'publish' ? 'Update Listing' : 'Post Listing'; ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php  include('popup/cancel-alert-myaccount.php'); ?>
<?php get_no_footer();  ?>
