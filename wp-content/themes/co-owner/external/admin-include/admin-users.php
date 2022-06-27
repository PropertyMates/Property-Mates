<?php
if (is_admin()) {
    add_action('restrict_manage_users', 'co_owner_filter_by_budget');
    function co_owner_filter_by_budget($which)
    {
        if ($which == 'top') {
            $st = '<select name="user_budget_%s" style="float:none;margin-left:10px;"><option value="">%s</option>%s</select>';
            $default = isset($_GET['user_budget_top']) ? $_GET['user_budget_top'] : "";

            $sym = CO_OWNER_CURRENCY_SYMBOL;
            $options = "<option " . ($default == '50000,150000' ? 'selected' : '') . " value='50000,150000'>{$sym}50,000 to {$sym}1,50,000</option>
                    <option " . ($default == '150000,350000' ? 'selected' : '') . " value='150000,350000'>{$sym}1,50,000 to {$sym}3,50,000</option>
                    <option " . ($default == '350000,650000' ? 'selected'  : '') . " value='350000,650000'>{$sym}3,50,000 to {$sym}6,50,000</option>
                    <option " . ($default == '650000,950000' ? 'selected' : '') . " value='650000,950000'>{$sym}6,50,000 to {$sym}9,50,000</option>
                    <option " . ($default == '650000,950000' ? 'selected'  : '') . " value='950000,150000'>{$sym}9,50,000 to {$sym}1,50,000</option>
                    <option " . ($default == '1500000,up' ? 'selected' : '') . " value='1500000,up'>{$sym}15,00,000 Up</option>";

            $select = sprintf($st, $which, __('User Budget'), $options);
            echo $select;


            $args = array(
                'fields' => array(
                    'ID',
                    'display_name',
                )
            );
            $users = get_users($args);
            $shorted_by = isset($_GET['user_shorted_by_top']) ? $_GET['user_shorted_by_top'] : null;
            $users_options = '';
            foreach ($users as $user) {
                $users_options .= "<option " . ($shorted_by == $user->ID ? 'selected' : '') . " value='{$user->ID}'>{$user->display_name}</option>";
            }
            $user_dropdown = '<select name="user_shorted_by_%s" style="float:none;margin-left:10px;"><option value="">%s</option>%s</select>';
            echo sprintf($user_dropdown, $which, __('Shorted By'), $users_options);

            submit_button(__('Filter'), null, $which, false);
        }
    }



    add_filter('pre_get_users', 'filter_users_by_budget_section');
    function filter_users_by_budget_section($query)
    {
        global $pagenow;
        if (is_admin() && 'users.php' == $pagenow) {
            if (isset($_GET['s']) && is_numeric($_GET['s'])) {
                $query->set('search', null);
            }
        }
    }



    function co_owner_users_list_table_query_args($args)
    {
        $meta_query = array();

        if (isset($_GET['s']) && is_numeric($_GET['s'])) {
            $s = $_GET['s'];
            $meta_query[] = array(
                array(
                    'key' => '_user_budget',
                    'value' => $s,
                    'type' => 'NUMERIC',
                    'compare' => '=',
                )
            );
        }

        if (isset($_GET['user_budget_top']) && !empty($_GET['user_budget_top'])) {
            $price = explode(',', $_GET['user_budget_top']);
            if (is_array($price)) {
                if (count($price)) {
                    $meta_filter2 = array('relation' => 'AND');
                    $meta_filter2[] = array(
                        'key' => '_min_budget',
                        'value' => $price[0],
                        'type' => 'NUMERIC',
                        'compare' => '>=',
                    );
                    if (isset($price[1]) && is_numeric($price[1])) {
                        $meta_filter2[] = array(
                            'key' => '_max_budget',
                            'value' => $price[1],
                            'type' => 'NUMERIC',
                            'compare' => '<=',
                        );
                    }
                    $meta_query[] = $meta_filter2;
                }
            }
        }

        if (count($meta_query) > 0) {
            $args['meta_query'] = $meta_query;
        }

        if (isset($_GET['user_shorted_by_top']) && !empty($_GET['user_shorted_by_top'])) {
            $exclude = (new CoOwner_ArrayResponse(get_users()))->pluck('ID');

            $include = CoOwner_Favourite::get(CO_OWNER_FAVOURITE_TABLE, array(
                'user_id' => $_GET['user_shorted_by_top'],
                'favourite_type' =>  'user'
            ));
            $include = (new CoOwner_ArrayResponse($include))->pluck('favourite_id');

            $args['exclude'] = array_diff($exclude, $include);
            $args['include'] = $include;
        }
        return $args;
    }
    add_filter('users_list_table_query_args', 'co_owner_users_list_table_query_args', 10, 1);

    add_action('show_user_profile', 'extra_user_profile_fields', 9, 1);
    add_action('edit_user_profile', 'extra_user_profile_fields', 9, 1);
    add_action('user_new_form', 'extra_user_profile_fields', 9, 1);
    function extra_user_profile_fields($user)
    {
        if (is_object($user) && in_array('administrator', $user->roles)) {
            return;
        }

        $mobile_verified = (is_object($user) && get_user_meta($user->ID, '_user_is_mobile_verified', true)) == 1 ? 1 : 0;
        $email_verified = (is_object($user) && get_user_meta($user->ID, '_user_is_email_verified', true)) == 1 ? 1 : 0;

        $mobile = is_object($user) ? get_user_meta($user->ID, '_mobile', true) : (isset($_POST['_mobile']) ? $_POST['_mobile'] : null);
        $property_category = is_object($user) ? get_user_meta($user->ID, '_user_property_category', true) : (isset($_POST['_user_property_category']) ? $_POST['_user_property_category'] : array());
        $user_property_type = is_object($user) ? get_user_meta($user->ID, '_user_property_type', true) : (isset($_POST['_user_property_type']) ? $_POST['_user_property_type'] : array());
        $user_descriptions = is_object($user) ? get_user_meta($user->ID, '_user_descriptions', true) : (isset($_POST['_user_property_preference']) ? $_POST['_user_property_preference'] : null);
        $user_preferred_location = is_object($user) ? get_user_meta($user->ID, '_user_preferred_location', true) : (isset($_POST['_user_preferred_location']) ? $_POST['_user_preferred_location'] : array());
        $user_preferred_location = is_array($user_preferred_location) ? $user_preferred_location : array();
        $user_land_area = is_object($user) ? get_user_meta($user->ID, '_user_land_area', true) : (isset($_POST['_user_land_area']) ? $_POST['_user_land_area'] : null);
        $user_building_area = is_object($user) ? get_user_meta($user->ID, '_user_building_area', true) : (isset($_POST['_user_building_area']) ? $_POST['_user_building_area'] : null);
        $user_age_year_built = is_object($user) ? get_user_meta($user->ID, '_user_age_year_built', true) : (isset($_POST['_user_age_year_built']) ? $_POST['_user_age_year_built'] : null);
        $user_bedroom = is_object($user) ? get_user_meta($user->ID, '_user_bedroom', true) : (isset($_POST['_user_bedroom']) ? $_POST['_user_bedroom'] : 0);
        $user_bathroom = is_object($user) ? get_user_meta($user->ID, '_user_bathroom', true) : (isset($_POST['_user_bathroom']) ? $_POST['_user_bathroom'] : 0);
        $user_parking = is_object($user) ? get_user_meta($user->ID, '_user_parking', true) : (isset($_POST['_user_parking']) ? $_POST['_user_parking'] : 0);
        $property_features = is_object($user) ? get_user_meta($user->ID, '_user_property_features', true) : (isset($_POST['_user_property_features']) ? $_POST['_user_property_features'] : array());
        $property_features = is_array($property_features) ? $property_features : array();
        $manually_features = is_object($user) ? get_user_meta($user->ID, '_user_manually_features', true) : (isset($_POST['_user_manually_features']) ? $_POST['_user_manually_features'] : array());
        $manually_features = is_array($manually_features) ? $manually_features : array();
        $user_enable_pool = is_object($user) ? get_user_meta($user->ID, '_user_enable_pool', true) : (isset($_POST['_user_enable_pool']) ? $_POST['_user_enable_pool'] : null);
        $user_budget = is_object($user) ? get_user_meta($user->ID, '_user_budget', true) : (isset($_POST['_user_budget']) ? $_POST['_user_budget'] : null);
        $user_budget_range = is_object($user) ? get_user_meta($user->ID, '_user_budget_range', true) : '';

        $user_status = is_object($user) ? get_user_meta($user->ID, '_user_status', true) : (isset($_POST['_user_status']) ? $_POST['_user_status'] : null);
        $user_listing_status = is_object($user) ? get_user_meta($user->ID, '_user_listing_status', true) : (isset($_POST['_user_listing_status']) ? $_POST['_user_listing_status'] : null);
?>
        <h3><?php _e("Extra profile information", "blank"); ?></h3>
        <table class="form-table mobile-box">
            <tr class="">
                <th><label for="user_mobile"><?php _e("Mobile"); ?></label></th>
                <td>
                    <input id="email-verified" type="hidden" value="<?php echo $email_verified ? 'true' : 'false'; ?>">
                    <input type="text" name="_mobile" id="user_mobile" value="<?php echo $mobile; ?>" class="regular-text code" />
                    <?php if ($mobile_verified) : echo co_owner_get_svg('verified');
                    endif; ?>
                </td>
            </tr>
        </table>
        <table class="form-table property-category-box">
            <tr class="">
                <th><label for="property_category"><?php _e("Property Category"); ?></label></th>
                <td>
                    <label style="padding-right: 10px;">
                        <input type="checkbox" name="_user_property_category[]" id="property_category" value="residential" <?php echo in_array('residential', $property_category) ? "checked" : ''; ?> class="regular-text code" required />Residential
                    </label>
                    <label>
                        <input type="checkbox" name="_user_property_category[]" id="property_category" value="commercial" <?php echo in_array('commercial', $property_category) ? "checked" : ''; ?> class="regular-text code" required />Commercial
                    </label>
                </td>
            </tr>
        </table>
        <table class="form-table property-type-box">
            <tr class="">
                <th><label for="property_type"><?php _e("Property Type"); ?></label></th>
                <td class="check-list residential">
                    <table>
                        <tr>
                            <td><label><input type="checkbox" name="_user_property_type[]" <?php echo in_array('house', $user_property_type) ? 'checked' : null; ?> value="house">House</label></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><label><input type="checkbox" name="_user_property_type[]" <?php echo in_array('apartment', $user_property_type) ? 'checked' : null; ?> value="apartment">Apartment</label></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><label><input type="checkbox" name="_user_property_type[]" <?php echo in_array('townhouse', $user_property_type) ? 'checked' : null; ?> value="townhouse">Townhouse</label></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><label><input type="checkbox" name="_user_property_type[]" <?php echo in_array('land', $user_property_type) ? 'checked' : null; ?> value="land">Land</label></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><label><input type="checkbox" name="_user_property_type[]" <?php echo in_array('retirement', $user_property_type) ? 'checked' : null; ?> value="retirement">Retirement</label></td>
                        </tr>
                    </table>
                </td>
                <td class="check-list commercial">
                    <table>
                        <tr>
                            <td><label><input type="checkbox" name="_user_property_type[]" <?php echo in_array('office', $user_property_type) ? 'checked' : null; ?> value="office">Office - Office buildings,serviced offices</label></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><label><input type="checkbox" name="_user_property_type[]" <?php echo in_array('leisure', $user_property_type) ? 'checked' : null; ?> value="leisure">Leisure - hotels,public houses, restaurants, cafes, sports facilities</label></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><label><input type="checkbox" name="_user_property_type[]" <?php echo in_array('retails', $user_property_type) ? 'checked' : null; ?> value="retails">Retails - retail stores, shopping malls, shops</label></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><label><input type="checkbox" name="_user_property_type[]" <?php echo in_array('healthcare', $user_property_type) ? 'checked' : null; ?> value="healthcare">Healthcare - medical centers, hospitals, nursing homes</label></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><label><input type="checkbox" name="_user_property_type[]" <?php echo in_array('multifamily', $user_property_type) ? 'checked' : null; ?> value="multifamily">Multifamily - multifamily housing buildings (apartments)</label></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="form-table property-preference-box">
            <tr class="">
                <th><label for="_user_property_preference"><?php _e("Property Preference"); ?></label></th>
                <td>
                    <textarea rows="5" cols="30" name="_user_property_preference" id="_user_property_preference"><?php echo $user_descriptions; ?></textarea>
                </td>
            </tr>
        </table>
        <table class="form-table property-preference-box">
            <tr class="">
                <th><label for="_user_preferred_location"><?php _e("Preferred Locations"); ?></label></th>
                <td>
                    <select name="_user_preferred_location[]" id="_user_preferred_location" class="single-select2" multiple>
                        <?php foreach (get_all_states() as $state_key => $state_value) : ?>
                            <option value="<?php echo $state_key; ?>" <?php echo in_array($state_key, $user_preferred_location) ? 'selected' : ''; ?>><?php echo $state_value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>

        <table class="form-table">
            <tr class="">
                <th><label for="_user_land_area"><?php _e("Land Area"); ?></label></th>
                <td>
                    <input type="text" id="_user_land_area" name="_user_land_area" class="regular-text code" value="<?php echo $user_land_area; ?>">
                </td>
            </tr>
        </table>
        <table class="form-table">
            <tr class="">
                <th><label for="_user_building_area"><?php _e("Building Area"); ?></label></th>
                <td>
                    <input type="text" id="_user_building_area" name="_user_building_area" class="regular-text code" value="<?php echo $user_building_area; ?>">
                </td>
            </tr>
        </table>
        <table class="form-table">
            <tr class="">
                <th><label for="_user_age_year_built"><?php _e("Age/year Built"); ?></label></th>
                <td>
                    <select id="_user_age_year_built" name="_user_age_year_built" class="single-select2">
                        <?php for ($i = 1; $i <= 100; $i++) : ?>
                            <option <?php echo $i == $user_age_year_built ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </td>
            </tr>
        </table>
        <table class="form-table residential">
            <tr class="">
                <th><label for="_user_bedroom"><?php _e("Bedroom(s)"); ?></label></th>
                <td>
                    <div class="d-flex align-items-center room-counter">
                        <a href="#" class="text-center counter-minus">-</a>
                        <input value="<?php echo $user_bedroom; ?>" name="_user_bedroom" type="text" class="form-control input-only-number" id="_user_bedroom">
                        <a href="#" class="text-center counter-plus">+</a>
                    </div>
                </td>
            </tr>
            <tr class="">
                <th><label for="_user_bathroom"><?php _e("Bathroom(s)"); ?></label></th>
                <td>
                    <div class="d-flex align-items-center room-counter">
                        <a href="#" class="text-center counter-minus">-</a>
                        <input value="<?php echo $user_bathroom; ?>" name="_user_bathroom" type="text" class="form-control input-only-number" id="_user_bathroom">
                        <a href="#" class="text-center counter-plus">+</a>
                    </div>
                </td>
            </tr>
            <tr class="">
                <th><label for="_user_parking"><?php _e("Parking"); ?></label></th>
                <td>
                    <div class="d-flex align-items-center room-counter">
                        <a href="#" class="text-center counter-minus">-</a>
                        <input value="<?php echo $user_parking; ?>" name="_user_parking" type="text" class="form-control input-only-number" id="_user_parking">
                        <a href="#" class="text-center counter-plus">+</a>
                    </div>
                </td>
            </tr>
        </table>

        <table class="form-table residential property-features">
            <tr class="">
                <th><label for="_user_property_features"><?php _e("Property Features"); ?></label></th>
                <td class="check-list">
                    <table style="width: 300px;">
                        <tr>
                            <td><label><input id="_user_property_features" name="_user_property_features[]" <?php echo in_array('Air Conditioning', $property_features) ? 'checked' : null; ?> value="Air Conditioning" type="checkbox">Air Conditioning</label></td>
                            <td><label><input name="_user_property_features[]" <?php echo in_array('Build in wardrobes', $property_features) ? 'checked' : null; ?> value="Build in wardrobes" type="checkbox">Build in wardrobes</label></td>
                            <td><label><input name="_user_property_features[]" <?php echo in_array('Floorboards', $property_features) ? 'checked' : null; ?> value="Floorboards" type="checkbox">Floorboards</label></td>
                        </tr>
                        <tr>
                            <td><label><input name="_user_property_features[]" <?php echo in_array('Gas', $property_features) ? 'checked' : null; ?> value="Gas" type="checkbox">Gas</label></td>
                            <td><label><input name="_user_property_features[]" <?php echo in_array('Swimming Pool', $property_features) ? 'checked' : null; ?> value="Swimming Pool" type="checkbox">Swimming Pool</label></td>
                            <td><label><input name="_user_property_features[]" <?php echo in_array('Furnished', $property_features) ? 'checked' : null; ?> value="Furnished" type="checkbox">Furnished</label></td>
                        </tr>
                        <tr>
                            <td><label><input name="_user_property_features[]" <?php echo in_array('Indoor Gym', $property_features) ? 'checked' : null; ?> value="Indoor Gym" type="checkbox">Indoor Gym</label></td>
                            <td><label><input name="_user_property_features[]" <?php echo in_array('Alarm System', $property_features) ? 'checked' : null; ?> value="Alarm System" type="checkbox">Alarm System</label></td>
                            <td><label><input name="_user_property_features[]" <?php echo in_array('Dishwasher', $property_features) ? 'checked' : null; ?> value="Dishwasher" type="checkbox">Dishwasher</label></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="">
                <th><label for="_user_manually_features"><?php _e("Features Manually"); ?></label></th>
                <td>
                    <select id="_user_manually_features" name="_user_manually_features[]" class="select2-tags" multiple>
                        <?php foreach ($manually_features as $value) : ?>
                            <option selected value="<?php echo $value; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

        </table>

        <table class="form-table">
            <tr class=" check-link">
                <th><label for="_user_enable_pool"><?php _e("Enable Pool"); ?></label></th>
                <td>
                    <label>
                        <input type="checkbox" id="_user_enable_pool" name="_user_enable_pool" <?php echo $user_enable_pool ? 'checked' : ''; ?>>Enable Pool
                    </label>
                </td>
            </tr>
            <tr class="">
                <th><label for="_user_budget"><?php _e("Budget"); ?></label></th>
                <td>
                    <input type="text" id="_user_budget" name="_user_budget" value="<?php echo $user_budget; ?>" class="input-only-number">
                </td>
            </tr>
            <?php if ($user_budget_range) { ?>
                <tr class="">
                    <th><label><?php _e("Budget Range"); ?></label></th>
                    <td>
                        <select name="user_budget_price" class="form-control single-select2" data-search="false">
                            <option value="">Price</option>
                            <?php foreach (get_price_range_dropdown_options() as $p_value => $p_key) : ?>
                                <option value="<?php echo $p_value; ?>" <?php if ($user_budget) echo stripos($p_value, (string)$user_budget) ? 'selected' : ''; ?>><?php echo $p_key; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <table class="form-table">
            <tr>
                <th><label for="is-mobile-number-verified"><?php _e("Is Mobile Number Verified"); ?></label></th>
                <td>
                    <label class="switch-box">
                        <input name="_user_is_mobile_verified" type="checkbox" id="is-mobile-number-verified" <?php echo $mobile_verified ? 'checked' : null; ?>>
                        <span class="slider round"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <th><label for="is-email-id-verified"><?php _e("Is Email Id Verified"); ?></label></th>
                <td>
                    <label class="switch-box">
                        <input name="_user_is_email_verified" type="checkbox" id="is-email-id-verified" <?php echo $email_verified ? 'checked' : null; ?>>
                        <span class="slider round"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <th><label for="_user_listing_status"><?php _e("User Listing Status"); ?></label></th>
                <td>
                    <select id="_user_listing_status" name="_user_listing_status" class="single-select2">
                        <option <?php echo $user_listing_status == 1 ? 'selected' : ''; ?> value="1">Active</option>
                        <option <?php echo $user_listing_status == 2 ? 'selected' : ''; ?> value="2">Hide</option>
                        <option <?php echo $user_listing_status == 0 ? 'selected' : ''; ?> value="0">Delete</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="_user_status"><?php _e("User Status"); ?></label></th>
                <td>
                    <select id="_user_status" name="_user_status" class="single-select2">
                        <option <?php echo $user_status == 1 ? 'selected' : ''; ?> value="1">Active</option>
                        <option <?php echo $user_status == 2 ? 'selected' : ''; ?> value="2">Deactivate</option>
                        <option <?php echo $user_status == 3 ? 'selected' : ''; ?> value="2">Delete</option>
                    </select>
                </td>
            </tr>
        </table>
<?php }

    function co_owner_user_profile_errors(&$errors, $update = null, &$user = false)
    {
        global $pagenow;
        if ($pagenow == 'profile.php') {
            return;
        }
        if (empty($_POST['first_name'])) {
            $errors->add('first_name', '<strong>Error: </strong>: Please enter your first name.');
        }
        if (empty($_POST['last_name'])) {
            $errors->add('last_name', '<strong>Error: </strong>: Please enter your last name.');
        }
        if (empty($_POST['_mobile'])) {
            $errors->add('_mobile', '<strong>Error: </strong>: Please enter your mobile.');
        }
        if (empty($_POST['_user_property_category'])) {
            $errors->add('_user_property_category', '<strong>Error: </strong>: Please select property category.');
        }
        if (empty($_POST['_user_property_type'])) {
            $errors->add('_user_property_type', '<strong>Error: </strong>: Please select property type.');
        }
        if (empty($_POST['_user_preferred_location'])) {
            $errors->add('_user_preferred_location', '<strong>Error: </strong>: Please add preferred locations.');
        }
        // if (empty($_POST['_user_land_area'])) {
        //     $errors->add('_user_land_area', '<strong>Error: </strong>: Please add land area.');
        // }
        // if (empty($_POST['_user_building_area'])) {
        //     $errors->add('_user_building_area', '<strong>Error: </strong>: Please add building area.');
        // }
        if (empty($_POST['_user_age_year_built'])) {
            $errors->add('_user_age_year_built', '<strong>Error: </strong>: Please add age/year built.');
        }

        if (isset($_POST['_user_property_category']) && in_array('residential', $_POST['_user_property_category'])) {
            if (isset($_POST['_user_bedroom']) && ($_POST['_user_bedroom'] > 99 || $_POST['_user_bedroom'] < 0)) {
                $errors->add('_user_bedroom', '<strong>Error: </strong>: Please add bedroom.');
            }
            if (!is_numeric($_POST['_user_bedroom'])) {
                $errors->add('_user_bedroom', '<strong>Error: </strong>: Bedroom must be numeric.');
            }
            if (isset($_POST['_user_bathroom']) && ($_POST['_user_bathroom'] > 99 || $_POST['_user_bathroom'] < 0)) {
                $errors->add('_user_bathroom', '<strong>Error: </strong>: Please add bathroom.');
            }
            if (!is_numeric($_POST['_user_bathroom'])) {
                $errors->add('_user_bathroom', '<strong>Error: </strong>: Bathroom must be numeric.');
            }
            if (isset($_POST['_user_parking']) && ($_POST['_user_parking'] > 99 || $_POST['_user_parking'] < 0)) {
                $errors->add('_user_parking', '<strong>Error: </strong>: Please add parking.');
            }
            if (!is_numeric($_POST['_user_parking'])) {
                $errors->add('_user_parking', '<strong>Error: </strong>: Parking must be numeric.');
            }
        }

        // if (isset($_POST['_user_property_category']) && in_array('residential', $_POST['_user_property_category']) && empty($_POST['_user_property_features'])) {
        //     $errors->add('_user_property_features', '<strong>Error: </strong>: Please add property features.');
        // }

        if (isset($_POST['_user_budget']) && !is_numeric($_POST['_user_budget'])) {
            $errors->add('_user_budget', '<strong>Error: </strong>: Budget must be numeric.');
        }

        if (isset($_POST['user_budget_price']) && !empty($_POST['user_budget_price'])) {
            $errors->add('user_budget_price', '<strong>Error: </strong>: Budget range must be selected');
        }

        if (empty($_POST['_user_budget'])) {
            $errors->add('_user_budget', '<strong>Error: </strong>: Please add budget.');
        }
    }
    add_filter('registration_errors', 'co_owner_user_profile_errors');
    add_action('user_profile_update_errors', 'co_owner_user_profile_errors', 10, 3);

    /* USER SAVE / UPDATE PROFILE INFO  */
    function admin_co_owner_user_register($user_id)
    {
        save_custom_user_profile_fields($user_id, true);
    }
    add_action('user_register', 'admin_co_owner_user_register');
    function admin_co_owner_profile_update($user_id)
    {
        save_custom_user_profile_fields($user_id);
    }
    add_action('profile_update', 'admin_co_owner_profile_update');

    function save_custom_user_profile_fields($user_id, $is_register = false)
    {
        global $pagenow;
        if ($pagenow == 'profile.php') {
            return;
        }

        if (isset($_POST['_mobile']) && !empty($_POST['_mobile'])) {
            update_user_meta($user_id, '_mobile', $_POST['_mobile']);
        }
        if (isset($_POST['_user_property_category']) && is_array($_POST['_user_property_category']) && count($_POST['_user_property_category'])) {
            update_user_meta($user_id, '_user_property_category', $_POST['_user_property_category']);
        }
        if (isset($_POST['_user_property_type']) && is_array($_POST['_user_property_type']) && count($_POST['_user_property_type'])) {
            update_user_meta($user_id, '_user_property_type', $_POST['_user_property_type']);
        }
        if (isset($_POST['_user_preferred_location']) && is_array($_POST['_user_preferred_location']) && count($_POST['_user_preferred_location'])) {
            update_user_meta($user_id, '_user_preferred_location', $_POST['_user_preferred_location']);
        }
        if (!empty($_POST['_user_land_area'])) {
            update_user_meta($user_id, '_user_land_area', $_POST['_user_land_area']);
        }
        if (!empty($_POST['_user_building_area'])) {
            update_user_meta($user_id, '_user_building_area', $_POST['_user_building_area']);
        }
        if (!empty($_POST['_user_age_year_built'])) {
            update_user_meta($user_id, '_user_age_year_built', $_POST['_user_age_year_built']);
        }
        if (!empty($_POST['_user_bedroom'])) {
            update_user_meta($user_id, '_user_bedroom', $_POST['_user_bedroom']);
        }
        if (!empty($_POST['_user_bathroom'])) {
            update_user_meta($user_id, '_user_bathroom', $_POST['_user_bathroom']);
        }
        if (!empty($_POST['_user_parking'])) {
            update_user_meta($user_id, '_user_parking', $_POST['_user_parking']);
        }
        if (isset($_POST['_user_property_features']) && is_array($_POST['_user_property_features']) && count($_POST['_user_property_features'])) {
            update_user_meta($user_id, '_user_property_features', $_POST['_user_property_features']);
        }

        update_user_meta($user_id, '_user_manually_features', isset($_POST['_user_manually_features']) ? $_POST['_user_manually_features'] : array());
        update_user_meta($user_id, '_user_enable_pool', isset($_POST['_user_enable_pool']) ? 1 : 0);
        update_user_meta($user_id, '_user_status', isset($_POST['_user_status']) ? 1 : 0);

        if (!empty($_POST['_user_budget'])) {
            update_user_meta($user_id, '_user_budget', $_POST['_user_budget']);
        }

        if (!empty($_POST['user_budget_price'])) {
            update_user_meta($user_id, 'user_budget_price', $_POST['user_budget_price']);
        }

        $status = isset($_POST['_user_listing_status']) ? $_POST['_user_listing_status'] : 2;
        update_user_meta($user_id, '_user_listing_status', $status);
        if ($status == 0) {
            admin_reset_listing_status_and_meta($user_id);
        }


        if ($is_register) {
            $userMetaData = array(
                '_user_status' => 1,
                '_user_listing_status' => 1,
                '_user_notify_when_have_new_message_email' => true,
                '_user_notify_when_have_new_message_mobile' => false,
                '_user_notify_when_have_new_matching_listing_email' => true,
                '_user_notify_when_have_new_matching_listing_mobile' => false,
                '_user_notify_when_have_new_connection_request_email' => true,
                '_user_notify_when_have_new_connection_request_mobile' => true,
                '_user_notify_when_have_new_newsletters_and_offers_email' => false,
                '_user_notify_when_have_new_newsletters_and_offers_mobile' => false,
                '_user_notify_when_have_new_notify_me_daily' => false,
                '_user_notify_when_have_new_notify_me_weekly' => false,
                '_user_notify_when_have_new_notify_me_monthly' => false,
            );
            co_owner_update_user_meta($user_id, $userMetaData);
        } else {
            update_user_meta($user_id, '_user_is_mobile_verified', (isset($_POST['_user_is_mobile_verified']) ? 1 : 0));
            update_user_meta($user_id, '_user_is_email_verified', (isset($_POST['_user_is_email_verified']) ? 1 : 0));
        }
    }
    /* USER SAVE / UPDATE PROFILE INFO  */

    function users_remove_row_actions($actions, $user = false)
    {
        $link = $user ? home_url(CO_OWNER_PERSON_DETAILS_PAGE . "?id=$user->ID") : "#";
        $actions['view'] = $user ? "<a href='{$link}'>View</a>" : "";
        return $actions;
    }
    add_filter('user_row_actions', 'users_remove_row_actions', 11, 2);

    add_action('manage_users_columns', function ($columns) {
        unset($columns['role']);
        return $columns;
    }, 11, 1);

    add_action('manage_users_columns', 'co_owner_modify_user_columns', 1, 1);
    function co_owner_modify_user_columns($column_headers)
    {
        unset($column_headers['posts']);
        $column_headers['user_info'] = 'Info';
        $column_headers['user_subscription'] = 'Subscription';
        return $column_headers;
    }
    add_action('manage_users_columns', 'co_owner_modify_user_columns2', 10, 1);
    function co_owner_modify_user_columns2($column_headers)
    {
        if (isset($column_headers['s2member_subscr_id'])) {
            unset($column_headers['s2member_subscr_id']);
        }
        if (isset($column_headers['s2member_auto_eot_time'])) {
            unset($column_headers['s2member_auto_eot_time']);
        }
        if (isset($column_headers['s2member_ccaps'])) {
            unset($column_headers['s2member_ccaps']);
        }
        return $column_headers;
    }

    add_action('manage_users_custom_column', 'co_owner_user_posts_count_column_content', 1, 3);
    function co_owner_user_posts_count_column_content($value, $column_name, $user_id)
    {

        $user = new WP_User($user_id);
        if (is_object($user) && in_array('administrator', $user->roles)) {
            return;
        }

        switch ($column_name) {
            case 'user_info':
                $html = "<table class='info-table'>";

                $email_is_verified = get_user_meta($user_id, '_user_is_email_verified', true);
                $email_verified_icon = $email_is_verified == 1 ? co_owner_get_svg('verified') : null;
                $email_status = $email_is_verified == 1 ? 0 : 1;
                $email_status_text = $email_is_verified == 1 ? 'Make Unverified' : 'Make Verified';
                $verified_link = wp_nonce_url(admin_url("users.php?action=admin_make_user_email_verified&id={$user_id}&status={$email_status}"));
                $email_action_html = $user->user_email ? "{$email_verified_icon} <a href='{$verified_link}'>{$email_status_text}</a>" : "Email id not registered";
                $html .= "<tr>
                            <th>Email Verified</th>
                            <td>{$email_action_html}</td>
                        </tr>";

                $mobile_is_verified = get_user_meta($user_id, '_user_is_mobile_verified', true);
                $mobile = get_user_meta($user_id, '_mobile', true);
                $mobile_verified_icon = $mobile_is_verified == 1 ? co_owner_get_svg('verified') : null;
                $mobile_status = $mobile_is_verified == 1 ? 0 : 1;
                $mobile_status_text = $mobile_is_verified == 1 ? 'Make Unverified' : 'Make Verified';
                $verified_link = wp_nonce_url(admin_url("users.php?action=admin_make_user_mobile_verified&id={$user_id}&status={$mobile_status}"));
                $email_action_html = !empty(get_user_meta($user_id, '_mobile', true)) ? "{$mobile_verified_icon} <a href='{$verified_link}'>{$mobile_status_text}</a>" : "Mobile no. not registered";

                if ($email_action_html != 'Mobile no. not registered') {
                    $html .= "<tr>
                            <th>Mobile No</th>
                            <td>{$mobile}</td>
                        </tr>";
                }

                $html .= "<tr>
                            <th>Mobile Verified</th>
                            <td>{$email_action_html}</td>
                        </tr>";

                $budget_range = get_user_meta($user_id, '_user_budget_range', true);
                $html .= "<tr>
                            <th>Budget</th>
                            <td>" . price_range_show(($budget_range)) . "</td>
                        </tr>";

                $posts = get_user_post_count($user_id);
                $posts = $posts == 0 ? '-' : $posts;
                $html .= "<tr>
                            <th>Total Property</th>
                            <td>{$posts}</td>
                        </tr>";


                $status = get_user_meta($user_id, '_user_listing_status', true);
                if ($status == 0) {
                    $action_link = wp_nonce_url(admin_url("user-edit.php?user_id={$user_id}"));
                    $change_listing_status_html = "<a href='{$action_link}'>Create?</a>";
                } else {
                    $new_status = $status == 1 ? 2 : 1;
                    $status_text = $new_status == 2 ? 'Hide Listing' : 'Show Listing';
                    $action_link = wp_nonce_url(admin_url("users.php?action=admin_update_user_listing_status&id={$user_id}&status={$new_status}"));
                    $change_listing_status_html = "<a href='{$action_link}'>{$status_text}?</a>";
                    $delete_link = wp_nonce_url(admin_url("users.php?action=admin_update_user_listing_status&id={$user_id}&status=0"));
                    $change_listing_status_html .= "<br><a href='{$delete_link}'>Delete ?</a>";
                }

                $html .= "<tr>
                            <th>Listing Status</th>
                            <td>{$change_listing_status_html}</td>
                        </tr>";

                $user_status = get_user_meta($user_id, '_user_status', true);
                $options = '';
                if ($user_status != 1) {
                    $link1 = wp_nonce_url(admin_url("users.php?action=admin_update_user_status&id={$user_id}&status=1"));
                    $options .= "<a href='{$link1}'>Active Account?</a><br>";
                }

                if ($user_status != 2 && $user_status != 3) {
                    $link2 = wp_nonce_url(admin_url("users.php?action=admin_update_user_status&id={$user_id}&status=2"));
                    $options .= "<a href='{$link2}'>Deactive Account?</a><br>";
                }

                if ($user_status != 3 && $user_status != 2) {
                    $link3 = wp_nonce_url(admin_url("users.php?action=admin_update_user_status&id={$user_id}&status=3"));
                    $options .= "<a href='{$link3}'>Delete Account?</a><br>";
                }


                $html .= "<tr>
                            <th>User Status</th>
                            <td>{$options}</td>
                        </tr>";

                if ($user_status == 3) {
                    $reason = get_user_meta($user_id, '_user_leave_feedback', true);
                    if ($reason) {
                        $h1 = $reason['leave_reason'];
                        $html .= "<tr class='delete-reason'><td colspan='2'>Delete Reason:- {$h1}</td></tr>";
                        if ($h1 == 'Other') {
                            $h2 = "Comment:- " . $reason['comment'];
                            $html .= "<tr class='delete-reason'><td colspan='2'>{$h2}</td></tr>";
                        }
                    }
                }


                $html .= "</table>";
                return $html;
            case 'user_subscription':
                $role = co_owner_get_user_field('s2member_access_role', $user->ID);

                if ($role == 's2member_level0') {
                    $expire_at = get_user_meta($user->ID, '_user_subscription_valid_at', true);
                    $value = 'Trial Plan<br>';
                    $value .= "Expires on :- " . date('d M Y', strtotime($expire_at));
                } elseif ($role == 's2member_level1') {
                    $value = 'Standard Plan';
                } elseif ($role == 's2member_level2') {
                    $value = 'Professional Plan';
                }

                return $value;
        }
        return $value;
    }



    // USER RESET PASSWORD OVERRIDE
    function co_owner_retrieve_password_title($title, $user_login, $user_data)
    {
        return PROPERTY_MATES_PASSWORD_RESET;
    }
    add_filter('retrieve_password_title', 'co_owner_retrieve_password_title', 10, 3);

    function co_owner_retrieve_password_message($message, $key, $user_login, $user_data)
    {
        $user = get_user_by('login', $user_login);
        if ($user) {
            $token = wp_generate_uuid4();
            $time = wp_date('Y-m-d H:i:s', strtotime('+1 hour'));

            update_user_meta($user->ID, 'reset_password_token', $token);
            update_user_meta($user->ID, 'reset_password_token_expire', $time);

            $email = base64_encode($user->user_email);

            $link = home_url('reset-password') . "?token={$token}&email={$email}";
            ob_start();
            include(CO_OWNER_THEME_DIR . '/parts/mails/forgot-password.php');
            $message = ob_get_clean();
        }

        return $message;
    }
    add_filter('retrieve_password_message', 'co_owner_retrieve_password_message', 10, 4);
}
