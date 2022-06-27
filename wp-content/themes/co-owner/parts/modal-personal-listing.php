<?php 
$property_category = get_user_meta($user_id, '_user_property_category', true);
$property_category = empty($property_category) ? array() : $property_category;
$property_type = get_user_meta($user_id, '_user_property_type', true);
$property_type = empty($property_type) ? array() : $property_type;
$descriptions = get_user_meta($user_id, '_user_descriptions', true);
$preferred_location = get_user_meta($user_id, '_user_preferred_location', true);;
$preferred_location = empty($preferred_location) ? array() : $preferred_location;
$land_area = get_user_meta($user_id, '_user_land_area', true);
$building_area = get_user_meta($user_id, '_user_building_area', true);
$age_year_built = get_user_meta($user_id, '_user_age_year_built', true);
$user_bedroom = get_user_meta($user_id, '_user_bedroom', true);
$bedroom = empty($user_bedroom) ? 0 : $user_bedroom;
$user_bathroom = get_user_meta($user_id, '_user_bathroom', true);
$bathroom = empty($user_bathroom) ? 0 : $user_bathroom;
$user_parking = get_user_meta($user_id, '_user_parking', true);
$parking = empty($user_parking) ? 0 : $user_parking;
$property_features = get_user_meta($user_id, '_user_property_features', true);
$property_features = empty($property_features) ? array() : $property_features;
$manually_features = get_user_meta($user_id, '_user_manually_features', true);
$manually_features = empty($manually_features) ? array() : $manually_features;
$budget = get_user_budget($user_id);
$enable_pool = get_user_meta($user_id, '_user_enable_pool', true);
$listing_status = get_user_meta($user_id, '_user_listing_status', true);
$check_for_create = check_create_upto_listings_by_plan($user_id);
$is_edit = (empty($listing_status) || $listing_status == 0) ? false : true;


?>     

	 <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                                    <div class="accordion-body">

                                        <div class="row">
                                            <div class="col-sm-12 col-12 pt-30px pb-30px">
                                                <div class="card text-dark bg-light">
                                                    <div class="card-body d-flex">
                                                        <h6 class="card-title me-auto pb-0 pt-1">Enable Pool if you are fine with buying property with others.</h6>
                                                        <div class="switch-top">
                                                            <div class="form-check form-switch">
                                                                <input <?php
                                                                        if ($enable_pool)
                                                                            echo $enable_pool ? 'checked' : '';
                                                                        else
                                                                            echo 'checked';
                                                                        ?> name="_user_enable_pool" class="form-check-input float-start" type="checkbox" id="enable_pool">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-100 title-area pb-2">
                                            <!--<span class="mnd-title">*Mandatory Fields</span>-->
                                        </div>
                                        <h4>Select Property Category <span class="Mandatory-f">*</span></h4>
                                        <div class="row">
                                            <div class="col-sm-12 col-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php
                                                            if ($property_category)
                                                                echo in_array('commercial', $property_category) ? 'checked' : '';
                                                            else
                                                                echo 'checked';
                                                            ?> name="_user_property_category[]" class="form-check-input" type="checkbox" value="commercial" id="user-property-category-commercial">
                                                    <label class="form-check-label" for="user-property-category-commercial">
                                                        Commercial
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-12 mb-3">
                                                <div class="form-check custom-checkbox">
                                                    <input <?php
                                                            if ($property_category)
                                                                echo in_array('residential', $property_category) ? 'checked' : '';
                                                            else
                                                                echo 'checked';
                                                            ?> name="_user_property_category[]" class="form-check-input" type="checkbox" value="residential" id="user-property-category-residential">
                                                    <label class="form-check-label" for="user-property-category-residential">
                                                        Residential
                                                    </label>
                                                </div>
                                            </div>
                                            <label id="_user_property_category[]-error" class="text-error" for="_user_property_category[]"></label>
                                        </div>
                                        <h4 class="pt-4 budget">Budget</h4>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 col-12 mb-3">
                                                <label for="user_budget" class="form-label">My Budget Range <span class="Mandatory-f">*</span></label>
                                                <div class="w-100 custom-select">
                                                    <select name="user_budget_price" class="form-control single-select2" data-search="false">
                                                        <option value="">Price</option>
                                                        <?php foreach (get_price_range_dropdown_options() as $p_value => $p_key) : ?>
                                                            <option value="<?php echo $p_value; ?>" <?php if ($budget) echo stripos($p_value, (string)$budget) ? 'selected' : ''; ?>><?php echo $p_key; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="pt-4">PREFERRED LOCATION(S)</h5>
                                        <div class="row">
                                            <div class="col-sm-12 col-12 mb-3">
                                                <label for="user-preferred-location" class="form-label">ADD PREFERRED LOCATION(S) <span class="Mandatory-f">*</span></label>
                                                <div class="w-100 custom-select">
                                                    <select name="_user_preferred_location[]" id="user-preferred-location" class="form-control single-select2" multiple>
                                                        <?php
                                                        $selected = (is_array($preferred_location) ? $preferred_location : []);
                                                        ?>
                                                        <option value="all" <?php echo (!$selected) ? 'selected' : ''; ?>>ALL</option>
                                                        <?php
                                                        foreach (get_all_states() as $key => $state) : ?>
                                                            <option value="<?php echo $key; ?>" <?php echo in_array($key, $selected) ? 'selected' : ''; ?>><?php echo $state; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <label id="user-preferred-location-error" class="text-error" for="user-preferred-location"></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-4 show-more-div">
                                            <a href="#" id="view-more-options" class="btn rounded-pill text-primary btn-sm w-200px">SHOW MORE <i class="bi bi-chevron-down"></i></a>
                                        </div>

                                        <div class="show-more-option" id="show-more-option">
                                            <h5 class="pt-4 residential-commercial">Select Property Type (Optional)</h5>
                                            <div class="row">
                                                <div class="col-md-5 residential">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-12 mb-3">
                                                            <div class="form-check custom-checkbox">
                                                                <input <?php
                                                                        if ($property_type)
                                                                            echo in_array('house', $property_type) ? 'checked' : '';
                                                                        else
                                                                            echo 'checked';
                                                                        ?> value="house" class="form-check-input" type="checkbox" name="_user_property_type[]" id="user-property-type-house">
                                                                <label class="form-check-label" for="user-property-type-house">
                                                                    House
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-12 mb-3">
                                                            <div class="form-check custom-checkbox">
                                                                <input <?php
                                                                        if ($property_type)
                                                                            echo in_array('apartment', $property_type) ? 'checked' : '';
                                                                        else
                                                                            echo 'checked'; ?> value="apartment" class="form-check-input" type="checkbox" name="_user_property_type[]" id="user-property-type-apartment">
                                                                <label class="form-check-label" for="user-property-type-apartment">
                                                                    Apartment
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-12 mb-3">
                                                            <div class="form-check custom-checkbox">
                                                                <input <?php
                                                                        if ($property_type)
                                                                            echo in_array('townhouse', $property_type) ? 'checked' : '';
                                                                        else
                                                                            echo 'checked';
                                                                        ?> value="townhouse" class="form-check-input" type="checkbox" name="_user_property_type[]" id="user-property-type-townhouse">
                                                                <label class="form-check-label" for="user-property-type-townhouse">
                                                                    Townhouse
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-12 mb-3">
                                                            <div class="form-check custom-checkbox">
                                                                <input <?php
                                                                        if ($property_type)
                                                                            echo in_array('land', $property_type) ? 'checked' : '';
                                                                        else
                                                                            echo 'checked';
                                                                        ?> value="land" class="form-check-input" type="checkbox" name="_user_property_type[]" id="user-property-type-land">
                                                                <label class="form-check-label" for="user-property-type-land">
                                                                    Land
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-12 mb-3">
                                                            <div class="form-check custom-checkbox">
                                                                <input <?php
                                                                        if ($property_type)
                                                                            echo in_array('retirement', $property_type) ? 'checked' : '';
                                                                        else
                                                                            echo 'checked';
                                                                        ?> value="retirement" class="form-check-input" type="checkbox" name="_user_property_type[]" id="user-property-type-retirement">
                                                                <label class="form-check-label" for="user-property-type-retirement">
                                                                    Retirement
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7 commercial">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-12 mb-3">
                                                            <div class="form-check custom-checkbox">
                                                                <input <?php
                                                                        if ($property_type)
                                                                            echo in_array('office', $property_type) ? 'checked' : '';
                                                                        else
                                                                            echo 'checked';
                                                                        ?> value="office" class="form-check-input" type="checkbox" name="_user_property_type[]" id="user-property-type-office">
                                                                <label class="form-check-label" for="user-property-type-office">
                                                                    Office - Office buildings,serviced offices
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-12 mb-3">
                                                            <div class="form-check custom-checkbox">
                                                                <input <?php
                                                                        if ($property_type)
                                                                            echo in_array('leisure', $property_type) ? 'checked' : '';
                                                                        else
                                                                            echo 'checked';
                                                                        ?> value="leisure" class="form-check-input" type="checkbox" name="_user_property_type[]" id="user-property-type-leisure">
                                                                <label class="form-check-label" for="user-property-type-leisure">
                                                                    Leisure - hotels,public houses, restaurants, cafes, sports facilities
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-12 mb-3">
                                                            <div class="form-check custom-checkbox">
                                                                <input <?php
                                                                        if ($property_type)
                                                                            echo in_array('retails', $property_type) ? 'checked' : '';
                                                                        else
                                                                            echo 'checked';
                                                                        ?> value="retails" class="form-check-input" type="checkbox" name="_user_property_type[]" id="user-property-type-retails">
                                                                <label class="form-check-label" for="user-property-type-retails">
                                                                    Retails - retail stores, shopping malls, shops
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-12 mb-3">
                                                            <div class="form-check custom-checkbox">
                                                                <input <?php
                                                                        if ($property_type)
                                                                            echo in_array('healthcare', $property_type) ? 'checked' : '';
                                                                        else
                                                                            echo 'checked';
                                                                        ?> value="healthcare" class="form-check-input" type="checkbox" name="_user_property_type[]" id="user-property-type-healthcare">
                                                                <label class="form-check-label" for="user-property-type-healthcare">
                                                                    Healthcare - medical centers, hospitals, nursing homes
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-12 mb-3">
                                                            <div class="form-check custom-checkbox">
                                                                <input <?php
                                                                        if ($property_type)
                                                                            echo in_array('multifamily', $property_type) ? 'checked' : '';
                                                                        else
                                                                            echo 'checked';
                                                                        ?> value="multifamily" class="form-check-input" type="checkbox" name="_user_property_type[]" id="user-property-type-multifamily">
                                                                <label class="form-check-label" for="user-property-type-multifamily">
                                                                    Multifamily - multifamily housing buildings (apartments)
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label id="_user_property_type[]-error" class="text-error" for="_user_property_type[]"></label>
                                            </div>

                                            <h4 class="pt-4">Add Property Preference (Optional)</h4>
                                            <div class="row">
                                                <div class="col-sm-12 col-12 mb-3">
                                                    <label for="user-descriptions" class="form-label d-flex align-items-baseline">
                                                        Add Description
                                                        <span class="ms-auto character-counter"><span class="character-length">0</span>/3000</span>
                                                    </label>
                                                    <textarea class="form-control count-character" maxlength="3000" name="_user_descriptions" id="user-descriptions" placeholder="Hint" rows="5"><?php echo $descriptions; ?></textarea>
                                                </div>
                                            </div>

                                            <h4 class="pt-4">Add Specifications & Features Preferences (Optional)</h4>
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <label for="exampleFormControlInput6" class="form-label">Land Area</label>
                                                    <input value="<?php echo $land_area; ?>" name="_user_land_area" type="text" class="form-control" id="exampleFormControlInput6" placeholder="Land Area">
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <label for="exampleFormControlInput7" class="form-label">Building Area</label>
                                                    <input value="<?php echo $building_area; ?>" name="_user_building_area" type="text" class="form-control" id="exampleFormControlInput7" placeholder="Building Area">
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <label for="exampleFormControlInput8" class="form-label">Age/year built</label>
                                                    <div class="w-100 custom-select">
                                                        <select name="_user_age_year_built" class="form-select single-select2" aria-label="Default select example" id="exampleFormControlInput8">
                                                            <option value="">Select Age/year built</option>
                                                            <?php for ($i = 1; $i <= 100; $i++) : ?>
                                                                <option <?php echo $age_year_built == $i ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                            <?php endfor; ?>
                                                        </select>
                                                        <label id="exampleFormControlInput8-error" class="text-error" for="exampleFormControlInput8"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-sm-12 mb-3 residential">
                                                    <label class="form-label">Bedroom(s)</label>
                                                    <div class="d-flex align-items-center room-counter">
                                                        <a href="#" class="text-center counter-minus">-</a>
                                                        <input value="<?php echo $bedroom; ?>" name="_user_bedroom" type="text" class="form-control" id="" placeholder="" value="0">
                                                        <a href="#" class="text-center counter-plus">+</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3 residential">
                                                    <label class="form-label">Bathroom(s)</label>
                                                    <div class="d-flex align-items-center room-counter">
                                                        <a href="#" class="text-center counter-minus">-</a>
                                                        <input value="<?php echo $bathroom; ?>" name="_user_bathroom" type="text" class="form-control" id="" placeholder="" value="0">
                                                        <a href="#" class="text-center counter-plus">+</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3 residential">
                                                    <label class="form-label">Parking</label>
                                                    <div class="d-flex align-items-center room-counter">
                                                        <a href="#" class="text-center counter-minus">-</a>
                                                        <input value="<?php echo $parking; ?>" name="_user_parking" type="text" class="form-control" id="" placeholder="" value="0">
                                                        <a href="#" class="text-center counter-plus">+</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="pt-4 residential">Property Features (Optional)</h5>
                                            <div class="row residential">
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <div class="form-check custom-checkbox">
                                                        <input <?php echo in_array('Air Conditioning', $property_features) ? 'checked' : ''; ?> name="_user_property_features[]" class="form-check-input" type="checkbox" value="Air Conditioning" id="flexCheckChecked">
                                                        <label class="form-check-label" for="flexCheckChecked">
                                                            Air Conditioning
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <div class="form-check custom-checkbox">
                                                        <input <?php echo in_array('Build in wardrobes', $property_features) ? 'checked' : ''; ?> name="_user_property_features[]" class="form-check-input" type="checkbox" value="Build in wardrobes" id="flexCheckChecked1">
                                                        <label class="form-check-label" for="flexCheckChecked1">
                                                            Build in wardrobes
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <div class="form-check custom-checkbox">
                                                        <input <?php echo in_array('Floorboards', $property_features) ? 'checked' : ''; ?> name="_user_property_features[]" class="form-check-input" type="checkbox" value="Floorboards" id="flexCheckChecked2">
                                                        <label class="form-check-label" for="flexCheckChecked2">
                                                            Floorboards
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <div class="form-check custom-checkbox">
                                                        <input <?php echo in_array('Gas', $property_features) ? 'checked' : ''; ?> name="_user_property_features[]" class="form-check-input" type="checkbox" value="Gas" id="flexCheckChecked3">
                                                        <label class="form-check-label" for="flexCheckChecked3">
                                                            Gas
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <div class="form-check custom-checkbox">
                                                        <input <?php echo in_array('Swimming Pool', $property_features) ? 'checked' : ''; ?> name="_user_property_features[]" class="form-check-input" type="checkbox" value="Swimming Pool" id="flexCheckChecked4">
                                                        <label class="form-check-label" for="flexCheckChecked4">
                                                            Swimming Pool
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <div class="form-check custom-checkbox">
                                                        <input <?php echo in_array('Furnished', $property_features) ? 'checked' : ''; ?> name="_user_property_features[]" class="form-check-input" type="checkbox" value="Furnished" id="flexCheckChecked5">
                                                        <label class="form-check-label" for="flexCheckChecked5">
                                                            Furnished
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <div class="form-check custom-checkbox">
                                                        <input <?php echo in_array('Indoor Gym', $property_features) ? 'checked' : ''; ?> name="_user_property_features[]" class="form-check-input" type="checkbox" value="Indoor Gym" id="flexCheckChecked6">
                                                        <label class="form-check-label" for="flexCheckChecked6">
                                                            Indoor Gym
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <div class="form-check custom-checkbox">
                                                        <input <?php echo in_array('Alarm System', $property_features) ? 'checked' : ''; ?> name="_user_property_features[]" class="form-check-input" type="checkbox" value="Alarm System" id="flexCheckChecked7">
                                                        <label class="form-check-label" for="flexCheckChecked7">
                                                            Alarm System
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 mb-3">
                                                    <div class="form-check custom-checkbox">
                                                        <input <?php echo in_array('Dishwasher', $property_features) ? 'checked' : ''; ?> name="_user_property_features[]" class="form-check-input" type="checkbox" value="Dishwasher" id="flexCheckChecked8">
                                                        <label class="form-check-label" for="flexCheckChecked8">
                                                            Dishwasher
                                                        </label>
                                                    </div>
                                                </div>

                                                <label id="_user_property_features[]-error" class="text-error" for="_user_property_features[]"></label>

                                                <div class="col-sm-12 col-12 mb-3">
                                                    <label for="exampleFormControlInput9" class="form-label">Add features manually</label>
                                                    <div class="w-100 custom-select">
                                                        <select name="_user_manually_features[]" id="exampleFormControlInput9" class="form-control select2-multiple-taggable w-100" multiple="multiple">
                                                            <?php
                                                            $options = (is_array($manually_features) ? $manually_features : []);
                                                            foreach ($options as $option) : ?>
                                                                <option selected value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <label id="exampleFormControlInput9-error" class="text-error" for="exampleFormControlInput9"></label>
                                                    <div class="d-flex align-items-center control-bottom">Instructions: To add multiple features in the list, type feature name and then add comma [ , ] and press enter.</div>
                                                </div>

                                            </div>
                                            <div class="pt-0 show-less-div">
                                                <a href="#" id="view-less-options" class="btn rounded-pill text-primary btn-sm w-200px">SHOW LESS</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            