<?php

function co_owner_get_meta_row($key, $value = null, $single = false)
{
    global $wpdb;
    $limit = $single ? "ORDER BY umeta_id DESC LIMIT 1 " : "";
    $key = is_numeric($key) ? $key : "'{$key}'";
    $andWhere = $value ? " AND meta_value = " . (is_numeric($value) ? $value : "'{$value}'") : "";
    $query = "SELECT * FROM {$wpdb->usermeta} WHERE meta_key = {$key} {$andWhere} {$limit}";
    return $single || $value != null ? $wpdb->get_row($query) : $wpdb->get_results($query);
}

function create_default_usermeta($id)
{
    $userMetaData = array(
        '_user_status' => 1,
        '_user_property_category' => array(),
        '_user_property_type' => array(),
        '_user_descriptions' => null,
        '_user_preferred_location' => array(),
        '_user_land_area' => null,
        '_user_building_area' => null,
        '_user_age_year_built' => null,
        '_user_bedroom' => (int) 0,
        '_user_bathroom' => (int) 0,
        '_user_parking' => (int) 0,
        '_user_property_features' => array(),
        '_user_manually_features' => array(),
        '_user_budget' => null,
        '_user_enable_pool' => false,
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
        '_user_listing_status' => 0,
    );
    co_owner_update_user_meta($id, $userMetaData);
}

function old_input($input)
{
    if (isset($_POST[$input]) && !empty($_POST[$input])) {
        return $_POST[$input];
    }
    return null;
}

function get_verification_code($length = 4)
{
    $min = pow(10, $length - 1);
    $max = pow(10, $length) - 1;
    return mt_rand($min, $max);
}

function is_verified_user()
{
    return (isset($_SESSION['user_verified']) && $_SESSION['user_verified'] == true);
}

function co_owner_get_svg($name)
{
    return Svg::get_svg(str_replace(["-", " "], "_", $name));
}

function check_value_in_usermeta($meta_key, $meta_value)
{
    global $wpdb;
    $query = "SELECT * FROM {$wpdb->usermeta} WHERE meta_key = '{$meta_key}' AND meta_value = '{$meta_value}'";
    return $wpdb->get_row($query);
}

function check_column_in_table($table, $columns, $values, $get_row = false)
{
    global $wpdb;
    $table = $wpdb->prefix . $table;
    $where = "";
    if (is_array($columns) && is_array($values)) {
        foreach ($columns as $key => $column) {
            $where .= "{$column} = '{$values[$key]}' ";
            if (($key === array_key_last($columns)) == false) {
                $where .= "AND ";
            }
        }
    } else {
        $where = "{$columns} = '{$values}'";
    }
    $query = "SELECT * FROM {$table} WHERE {$where}";
    $row = $wpdb->get_row($query);
    if ($get_row == true) {
        return $row;
    } elseif ($row) {
        return true;
    }
    return false;
}
/*For Signup and login*/
function is_user_login_redirect()
{
$current_user = wp_get_current_user();

    if (is_user_logged_in()) {
       wp_redirect(home_url('/'));
        exit();
    }
}

function get_message($type)
{
    if (
        isset($_GET['alert']) &&
        isset($_GET['message']) &&
        !empty($_GET['alert']) &&
        !empty($_GET['message']) &&
        $_GET['alert'] == $type
    ) {
        return $_GET['message'];
    }
}

function get_no_footer()
{
    echo '       </div>
            </div>
            ' . wp_footer() . '
        </body>
    </html>';
}

function get_all_states()
{
    return array(
        "NSW" => "New South Wales",
        "VIC" => "Victoria",
        "QLD" => "Queensland",
        "TAS" => "Tasmania",
        "SA" => "South Australia",
        "WA" => "Western Australia",
        "NT" => "Northern Territory",
        "ACT" => "Australian Capital Territory"
    );
}

function get_price_dropdown_options()
{
    $symbol = CO_OWNER_CURRENCY_SYMBOL;
    return array(
        '1000,10000' => "{$symbol}100 - {$symbol}10K",
        '10000,100000' => "{$symbol}10K - {$symbol}100K",
        '100000,500000' => "{$symbol}100K - {$symbol}500K",
        '500000,1000000' => "{$symbol}500K - {$symbol}1M",
        '1000000,+' => "{$symbol}1M+"
    );
}

function get_price_range_dropdown_options()
{
    $symbol = CO_OWNER_CURRENCY_SYMBOL;
    return array(
        '10,10000' => "Less than {$symbol}10K",
        '10000,100000' => "{$symbol}10K - {$symbol}100K",
        '100000,250000' => "{$symbol}100K - {$symbol}250K",
        '250000,500000' => "{$symbol}250K - {$symbol}500K",
        '500000,750000' => "{$symbol}500K - {$symbol}750K",
        '750000,1000000' => "{$symbol}750K - {$symbol}1M",
        '1000000,+' => "{$symbol}1M+"
    );
}

function get_state_full_name($state)
{
    $states = get_all_states();
    if (isset($states[$state])) {
        return $states[$state];
    }
    return "";
}

function co_owner_update_user_meta($user_id, $data)
{
    foreach ($data as $key => $value) {
        update_user_meta($user_id, $key, $value);
    }
}

function check_and_create_property_group($property_id)
{
    $property = get_post($property_id);
    $is_pool = get_post_meta($property_id, '_pl_enable_pool', true);
    if ($is_pool) {
        $user_id = $property->post_author;
        $group = CoOwner_Groups::find(array('property_id' => $property_id));
        $address = get_property_full_address($property_id);
        if (empty($group) || $group == null) {
            $data = array(
                'user_id' => $user_id,
                'property_id' => $property_id,
                'name' => $address
            );
            CoOwner_Groups::create($data);
        } else {
            CoOwner_Groups::update($group->id, 'name', $address);
        }
    } else {
        $members = get_property_total_members_without_admin($property_id);
        if (count($members) == 0) {
            CoOwner_Groups::delete_row(CO_OWNER_GROUP_TABLE, array('property_id' => $property_id));
        }
    }
}




/* LIST VIEW */

function get_view_url($type = 'list', $page = null)
{
    $args = $_GET;
    $args['view'] = $type;
    if (!$page) {
        $page = CO_OWNER_PROPERTY_LIST_PAGE;
    }
    $current_url = home_url(add_query_arg($args, $page . '/'));
    return $current_url;
}

function get_co_owner_property_list($filters, $for_pagination = false, $is_pool = false)
{
    $meta_filter = array();
    $price = (isset($filters['p_price']) && $filters['p_price']) ? explode(',', $filters['p_price'], 2) : array();
    $state = (isset($filters['p_state']) && $filters['p_state']) ? $filters['p_state'] : null;

    $order = (isset($filters['p_order']) && $filters['p_order'] == 'oldest') ? "ASC" : "DESC";
    $page = (isset($filters['p_page']) && $filters['p_page']) ? $filters['p_page'] : 1;

    $budget = (isset($filters['p_budget']) && $filters['p_budget']) ? $filters['p_budget'] : null;
    $location = (isset($filters['p_location']) && $filters['p_location']) ? $filters['p_location'] : null;


    if (count($price)) {
        $price_meta_filter = array('relation' => 'AND');
        $price_meta_filter[] = array(
            'key' => '_pl_price',
            'value' => $price[0],
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
        if (isset($price[1]) && is_numeric($price[1])) {
            $price_meta_filter[] = array(
                'key' => '_pl_price',
                'value' => $price[1],
                'type' => 'NUMERIC',
                'compare' => '<=',
            );
        }
        $meta_filter[] = $price_meta_filter;
    }

    if ($budget) {
        $meta_filter[] = array(
            'key' => '_pl_property_original_price',
            'value' => $budget,
            'type' => 'NUMERIC',
            'compare' => '<=',
        );
    }

    if ($location) {
        $meta_filter[] = array(
            'relation' => 'OR',
            array(
                'key' => '_pl_address',
                'value' => $location,
                'type' => 'STRING',
                'compare' => 'LIKE',
            ),
            array(
                'key' => '_pl_postcode',
                'value' => $location,
                'type' => 'STRING',
                'compare' => 'LIKE',
            ),
            array(
                'key' => '_pl_state',
                'value' => $location,
                'type' => 'STRING',
                'compare' => 'LIKE',
            )
        );
    }

    if ($state) {
        $meta_filter[] = array(
            'relation' => 'OR',
            array(
                'key' => '_pl_state',
                'value' => $state,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_pl_address',
                'value' => $state,
                'type' => 'STRING',
                'compare' => 'LIKE',
            ),
        );
    }

    if ($is_pool != 'all' || ($is_pool === true || $is_pool === false)) {
        $meta_filter[] = array(
            'relation' => 'AND',
            array(
                'key' => '_pl_enable_pool',
                'value' => 1,
                'type' => 'BOOLEAN',
                'compare' => $is_pool ? '=' : '!='
            )
        );
    }

    $args = array(
        "orderby"          => "post_date",
        "order"            => $order,
        "post_type"        => "property",
        "post_status"      => "publish"
    );

    if (count($meta_filter) > 0) {
        $args['meta_query'] = $meta_filter;
    }

    if ($for_pagination == false) {
        $args["posts_per_page"]   = CO_OWNER_PERPAGE;
        $args["paged"]            = $page;
    } else {
        $args["numberposts"]      = -1;
    }

    return get_posts($args);
}

function co_owner_paginate_links_property($all = false)
{
    $page = max(1, isset($_GET['p_page']) ? $_GET['p_page'] : 1);
    $filters = get_query_filters();
    $total_post = get_co_owner_property_list($filters, true, $all);
    $max = ceil(count($total_post) / CO_OWNER_PERPAGE);
    $slug = $all == 'all' ? CO_OWNER_PROPERTY_SEARCH_PAGE : CO_OWNER_PROPERTY_LIST_PAGE;
    return get_pagination_html($max, $slug, $page, 'p_page');
}

function co_owner_paginate_links_pool_property()
{
    $page = max(1, isset($_GET['p_page']) ? $_GET['p_page'] : 1);
    $filters = get_query_filters();
    $total_post = get_co_owner_property_list($filters, true, true);
    $max = ceil(count($total_post) / CO_OWNER_PERPAGE);
    return get_pagination_html($max, CO_OWNER_POOL_PROPERTY_LIST_PAGE, $page, 'p_page');
}

function co_owner_paginate_links_people()
{
    $page = max(1, isset($_GET['p_page']) ? $_GET['p_page'] : 1);
    $filters = get_query_filters('p_page', $page - 1);

    $total_post = get_people_looking_for_properties_list($filters, true);

    $max = ceil(count($total_post) / CO_OWNER_PERPAGE);

    return get_pagination_html($max, CO_OWNER_PEOPLE_LIST_PAGE, $page, 'p_page');
}

function co_owner_paginate_links_shortlist()
{
    $page = max(1, isset($_GET['p_page']) ? $_GET['p_page'] : 1);
    $total_post = get_co_owner_shortlist_property_list(true);

    $max = ceil(count($total_post) / CO_OWNER_PERPAGE);
    return get_pagination_html($max, CO_OWNER_SHORTLIST_PAGE, $page, 'p_page');
}

function get_pagination_html($total_page, $url_slug, $page, $page_slug = 'p_page')
{
    $output = '';
    if ($total_page > 0) {
        $query_strings = get_query_filters($page_slug, $page - 1);
        $url1 = home_url('/' . $url_slug . '/?' . http_build_query($query_strings));

        $output = '<nav aria-label="Page navigation example">';
        $output .=      '<ul class="pagination">';
        $output .=      '<li class="page-item ' . ($page == 1 ? "disabled" : "") . '">
                                <a class="page-link" href="' . $url1 . '" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>';

        for ($i = 1; $i <= $total_page; $i++) {
            if ($page == $i) {
                $output .= '<li class="page-item ' . ($page == $i ? "active" : "") . '"><a class="page-link" href="#">' . $i . '</a></li>';
            } elseif ($i == 4) {
                $output .= '<li class="page-item"><a class="page-link" href="#">...</a></li>';
            } elseif ($i < 4 || $i > ($total_page - 2)) {
                $query_strings = get_query_filters($page_slug, $i);
                $url = home_url('/' . $url_slug . '/?' . http_build_query($query_strings));
                $output .= '<li class="page-item"><a class="page-link" href="' . $url . '">' . $i . '</a></li>';
            }
        }

        $query_strings = get_query_filters($page_slug, $page + 1);
        $lastPage = home_url('/' . $url_slug . '/?' . http_build_query($query_strings));

        $output .= '<li class="page-item ' . ($page == $total_page ? "disabled" : "") . '">
                                <a class="page-link" href="' . $lastPage . '" tabindex="-1" aria-disabled="true">Next</a>
                            </li>';
        $output .= '</ul>';
        $output .= '</nav>';
    }
    return $output;
}

function co_owner_paginate_links($type = 'property')
{
    if ($type == 'property') {
        return co_owner_paginate_links_property();
    } elseif ($type == 'people-list') {
        return co_owner_paginate_links_people();
    } elseif ($type == 'property-shortlist') {
        return co_owner_paginate_links_shortlist();
    } elseif ($type == 'pool-property-list') {
        return co_owner_paginate_links_pool_property();
    }
}

function get_property_first_image($property_id)
{
    $images = get_post_meta($property_id, '_pl_images', true);
    return ($images && count($images) > 0 && isset($images[0]['url'])) ? $images[0]['url'] :  "#";
}

function get_property_full_address($property_id, $is_full = false)
{
    $unit_no = get_post_meta($property_id, '_pl_unit_no', true);
    $street_no = get_post_meta($property_id, '_pl_street_no', true);
    $street_name = get_post_meta($property_id, '_pl_street_name', true);
    $suburb = get_post_meta($property_id, '_pl_suburb', true);
    $only_display_suburb_in_my_ad = get_post_meta($property_id, '_pl_only_display_suburb_in_my_ad', true);
    $postcode = get_post_meta($property_id, '_pl_postcode', true);
    $state = get_post_meta($property_id, '_pl_state', true);
    $address = null;
    if (!empty($street_no) && !empty($street_name) && !empty($suburb) && !empty($postcode) && !empty($state)) {
        $states = get_all_states();
        if ($is_full == false && in_array($state, array_keys($states))) {
            $state = $states[$state];
        }
        $address = ($only_display_suburb_in_my_ad && $is_full == false ? $suburb : (($unit_no . (!empty($unit_no) ? ", " : "") . $street_no . " " . $street_name . ", " . $suburb . " " . $state . " " . $postcode)));
    }
    return $address;
}

function get_admin_hold_pr($property_id)
{
    $i_want_to_sell = get_post_meta($property_id, '_pl_i_want_to_sell', true);
    return ($i_want_to_sell ? (100 - $i_want_to_sell) : 100);
}

function get_query_filters($key = null, $value = null, $remove = array())
{
    $filters = $_GET;
    if (!empty($key) && !empty($value)) {
        $filters[$key] = $value;
    }
    if (count($remove) > 0) {
        foreach ($remove as $key) {
            if (isset($filters[$key])) {
                unset($filters[$key]);
            }
        }
    }
    return $filters;
}



/* FOR SORTED LIST */
function get_co_owner_shortlist_property_list($for_pagination = false)
{
    $order = (isset($_GET['p_order']) && $_GET['p_order'] == 'oldest') ? "ASC" : "DESC";
    $page = (isset($_GET['p_page']) && $_GET['p_page']) ? $_GET['p_page'] : 1;

    global $wpdb;
    $user_id = get_current_user_id();
    $post_table = $wpdb->prefix . 'posts';
    $favourite_table = $wpdb->prefix . CO_OWNER_FAVOURITE_TABLE;
    $columns = array(
        $post_table . '.ID',
        $post_table . '.post_author',
        $post_table . '.post_title',
    );
    $select_columns = implode(',', $columns);
    $query = "
        SELECT 
            {$select_columns}
        FROM
             {$post_table}
        LEFT JOIN
             {$favourite_table} ON {$favourite_table}.favourite_id = {$post_table}.ID
        WHERE 
              {$post_table}.post_type = 'property'
              AND {$post_table}.post_status = 'publish'
              AND {$favourite_table}.favourite_type = 'post'
              AND {$favourite_table}.user_id = {$user_id}
        ORDER BY {$post_table}.post_date {$order}";

    if ($for_pagination == false) {
        $limit = CO_OWNER_PERPAGE;
        $offset = ($page - 1) * $limit;
        $query .= " LIMIT {$limit} OFFSET {$offset}";
    }

    $posts = $wpdb->get_results($query);
    return $posts;
}

function get_co_owner_shortlist_person_list($for_pagination = false)
{
    $order = (isset($_GET['u_order']) && $_GET['u_order'] == 'oldest') ? "ASC" : "DESC";
    $page = (isset($_GET['u_page']) && $_GET['u_page']) ? $_GET['u_page'] : 1;

    global $wpdb;
    $user_id = get_current_user_id();
    $users_table = $wpdb->prefix . 'users';
    $favourite_table = $wpdb->prefix . CO_OWNER_FAVOURITE_TABLE;
    $usermeta_table = $wpdb->prefix . 'usermeta';

    $columns = array(
        $users_table . '.ID',
        $users_table . '.user_login',
        $users_table . '.user_nicename',
        $users_table . '.user_email',
        $users_table . '.user_status',
        $users_table . '.display_name',
    );
    $select_columns = implode(',', $columns);

    $query = "
        SELECT {$select_columns}
        
        FROM  {$users_table}
            
        LEFT JOIN {$favourite_table} ON {$favourite_table}.favourite_id = {$users_table}.ID
        
        WHERE {$favourite_table}.favourite_type = 'user' AND {$favourite_table}.user_id = {$user_id}
        
        ORDER BY {$users_table}.user_registered {$order}";

    if ($for_pagination == false) {
        $limit = CO_OWNER_PERPAGE;
        $offset = ($page - 1) * $limit;
        $query .= " LIMIT {$limit} OFFSET {$offset}";
        $users = $wpdb->get_results($query);
        return $users;
    } else {
        $users = $wpdb->get_results($query);
        $max = ceil(count($users) / CO_OWNER_PERPAGE);
        return get_pagination_html($max, CO_OWNER_SHORTLIST_PAGE, $page, 'u_page');
    }
}
// LIST VIRW


// -- FOR -- FRONT -- PAGE --

function get_is_liked_or_not($id, $user_id, $type = "post")
{
    if ($id && $user_id) {
        $array = array(
            'user_id'   =>  (int) $user_id,
            'favourite_type'   =>  $type,
            'favourite_id'   =>  (int) $id,
        );
        $result = CoOwner_Favourite::get_count($array);
        return $result->count > 0 ? true : false;
    }
    return false;
}

function get_property_is_liked($post_id)
{
    $user_id = get_current_user_id();
    if ($post_id && $user_id) {
        return get_is_liked_or_not($post_id, $user_id);
    }
    return false;
}

function get_people_is_liked($people_id)
{
    $user_id = get_current_user_id();
    if ($user_id && $people_id) {
        return get_is_liked_or_not($people_id, $user_id, 'user');
    }
    return false;
}

function get_properties_need_co_owners()
{
    $front_page = get_option('page_on_front');
    $max_select = max(0, (int) get_post_meta($front_page, '_front_page_need_co_owners_count', true));
    $meta_filter[] = array(
        'key' => '_pl_enable_pool',
        'value' => 1,
        'compare' => '!=',
        'type' => 'boolean',
    );
    $args = array(
        "orderby"          => "post_date",
        "order"            => 'DESC',
        "post_type"        => "property",
        "post_status"      => "publish",
        'numberposts'      => $max_select,
        'meta_query'       => $meta_filter
    );
    $properties = get_posts($args);
    $response = array();
    foreach ($properties as $property) {
        if (get_user_meta($property->post_author, '_user_status', true) == 1) {
            $response[] = $property;
        }
    }
    return $response;
}

function get_people_looking_for_properties()
{
    $front_page = get_option('page_on_front');
    $max_select = max(0, (int) get_post_meta($front_page, '_front_page_people_looking_for_properties_count', true));
    $meta_filter = array();
    $meta_filter[] = array(
        'key' => '_user_status',
        'value' => 1,
        'compare' => '=',
        'type' => 'boolean',
    );
    $meta_filter[] = array(
        'key' => '_user_listing_status',
        'value' => 1,
        'compare' => '=',
        'type' => 'boolean',
    );
    $args = array(
        'orderby'       => 'ID',
        'order'         => 'DESC',
        'number'        => ($max_select <= 15 ? $max_select : 15),
        "meta_query"    => $meta_filter,
        'fields'        => array(
            'ID',
            'user_login',
            'user_nicename',
            'user_email',
            'user_status',
            'display_name',
        )
    );
    return get_users($args);
}

function get_checkout_the_pools_already_created()
{
    $front_page = get_option('page_on_front');
    $max_select = max(0, (int) get_post_meta($front_page, '_front_page_pools_already_created_count', true));
    $meta_filter = array(
        array(
            'key' => '_pl_enable_pool',
            'value' => true,
            'type' => 'BOOLEAN',
            'compare' => '='
        )
    );
    $args = array(
        "orderby"          => "ID",
        "order"            => 'DESC',
        "post_type"        => "property",
        "post_status"      => "publish",
        'numberposts'      => $max_select,
        "meta_query"       => $meta_filter,
    );
    $properties = get_posts($args);
    $response = array();
    foreach ($properties as $property) {
        if (get_user_meta($property->post_author, '_user_status', true) == 1) {
            $response[] = $property;
        }
    }
    return $response;
}

function property_shares_under()
{
    $front_page = get_option('page_on_front');
    $max_select = max(0, (int) get_post_meta($front_page, '_front_page_property_shares_under_count', true));

    $selected_states = array();
    if (function_exists('carbon_get_post_meta')) {
        $selected_states = carbon_get_post_meta($front_page, 'front_page_property_shares_under_states');
    }
    global $wpdb;
    $response = array();
    foreach ($selected_states as $state) {
        $meta_filter = array();
        $meta_filter[] = array(
            'key' => '_pl_enable_pool',
            'value' => 1,
            'type' => 'BOOLEAN',
            'compare' => '='
        );
        $meta_filter[] = array(
            'relation' => 'OR',
            array(
                'key' => '_pl_address',
                'value' => $state,
                'type' => 'STRING',
                'compare' => 'LIKE',
            ),
            array(
                'key' => '_pl_state',
                'value' => $state,
                'type' => 'STRING',
                'compare' => 'LIKE',
            )
        );
        $args = array(
            "post_type"         => "property",
            "post_status"       => "publish",
            "meta_query"        => $meta_filter,
        );
        $posts = get_posts($args);

        $count = 0;
        foreach ($posts as $post) {
            if (get_user_meta($post->post_author, '_user_status', true) == 1) {
                $count++;
            }
        }

        $response[] = array(
            'state' => $state,
            'count' => $count,
        );
    }
    return $response;
}

// -- FOR -- FRONT -- PAGE --

function get_people_view_url()
{
    $args = $_GET;
    $current_url = home_url(add_query_arg($args, CO_OWNER_PEOPLE_LIST_PAGE . '/'));
    return $current_url;
}

function dd($value)
{
    echo "<pre>";
    print_r($value);
}

function get_people_looking_for_properties_list($filters = array(), $for_pagination = false)
{
    $meta_filter = array();

    $price = (isset($filters['p_budget']) && $filters['p_budget']) ? explode(',', $filters['p_budget'], 2) : array();

    $order = (isset($filters['p_order']) && $filters['p_order'] == 'oldest') ? "ASC" : "DESC";
    $page = (isset($filters['p_page']) && $filters['p_page']) ? $filters['p_page'] : 1;

    $location = (isset($filters['location']) && $filters['location']) ? $filters['location'] : null;
    if (count($price) > 1 && $price[1] != '+') {
        $price_meta_filter[] = array(
            'key' => '_min_budget',
            'value'   => array(0, $price[0]),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
        if ((isset($price[1]) && is_numeric($price[1])) && !empty($price[0])) {
            $price_meta_filter = array('relation' => 'AND');
            $price_meta_filter[] = array(
                'key' => '_min_budget',
                'value' => $price[0],
                'type' => 'NUMERIC',
                'compare' => ">=",
            );
            if ($price[1] != "1000000") {
                $price_meta_filter[] = array(
                    'key' => '_max_budget',
                    'value' => $price[1],
                    'type' => 'NUMERIC',
                    'compare' => '<=',
                );
            }
        }
        $meta_filter[] = $price_meta_filter;
    }
    if ($location) {
        $meta_filter[] = array(
            array('relation' => 'AND'),
            array(
                'key' => '_user_preferred_location',
                'value' => $location,
                'compare' => 'LIKE',
            )
        );
    }

    $meta_filter[] = array(
        'key' => '_user_status',
        'value' => 1,
        'compare' => '=',
        'type' => 'boolean',
    );
    $meta_filter[] = array(
        'key' => '_user_listing_status',
        'value' => 1,
        'compare' => '=',
        'type' => 'boolean',
    );

    $args = array(
        "orderby"          => "post_date",
        "order"            => $order,
        "meta_query"       => $meta_filter,
        'fields'           => array(
            'ID',
            'user_login',
            'user_nicename',
            'user_email',
            'user_status',
            'display_name',
        )
    );


    if ($for_pagination == false) {
        $args["number"]   = CO_OWNER_PERPAGE;
        $args["offset"]   = CO_OWNER_PERPAGE * ($page == 1 ? 0 : $page - 1);
    }

    $users = get_users($args);
    $search_filter = false;

    if (count($price) == 1) {
        $search_filter = true;
    } elseif (count($price) > 1 && $price[1] == '+') {
        $search_filter = true;
    }
    if ($search_filter) {
        $find_users = array();
        foreach ($users as $user) {
            $min_budget = (int)get_user_meta($user->ID, '_min_budget', true);
            $max_budget = (int)get_user_meta($user->ID, '_max_budget', true);
            if ($min_budget && $max_budget) {
                if (($min_budget <= $price[0]) && ($price[0] <= $max_budget)) {
                    $find_users[] = $user;
                }
            } else {
                if ($price[0] >= $min_budget) {
                    $find_users[] = $user;
                }
            }
        }
        $users = $find_users;
    }
    return $users;
}


// PROPERTY DETAIL PAGE

function get_property_available_share($id)
{
    $enable_pool = (int) get_post_meta($id, '_pl_enable_pool', true);
    $available_share = 0;

    $interested_in_selling = get_post_meta($id, '_pl_interested_in_selling', true);
    if ($interested_in_selling == 'portion_of_it') {
        $available_share = (int) get_post_meta($id, '_pl_i_want_to_sell', true);
    } else {
        $available_share = 100;
    }
    if ($enable_pool) {
        $total_of_members_share = CoOwner_Connections::get_total_column_by_property($id);
        $available_share = (float)$available_share - (float)$total_of_members_share;
    }
    return (int) $available_share;
}

function get_property_available_price($id)
{
    $enable_pool = (int) get_post_meta($id, '_pl_enable_pool', true);
    $available_price =  (float) get_post_meta($id, '_pl_property_market_price', true);

    $interested_in_selling = get_post_meta($id, '_pl_interested_in_selling', true);
    if ($interested_in_selling == 'portion_of_it') {
        $available_price = (int) get_post_meta($id, '_pl_calculated', true);
    }

    if ($enable_pool) {
        $total_of_members_price = CoOwner_Connections::get_total_column_by_property($id, 'calculated_price');
        $available_price = $available_price - $total_of_members_price;
    }
    return (int) $available_price;
}

function get_property_price_for_display($property_id, $number_format = true)
{
    $interested_in_selling  = get_post_meta($property_id, '_pl_interested_in_selling', true);
    $enable_pool  = get_post_meta($property_id, '_pl_enable_pool', true);
    if ($interested_in_selling == 'portion_of_it' && !$enable_pool) {
        $price = get_post_meta($property_id, '_pl_calculated', true);
    } else {
        $price = get_property_available_price($property_id);
    }
    return $number_format ? number_format((int) $price) : (int) $price;
}

function update_property_price_for_search($property_id)
{
    $price = get_property_price_for_display($property_id, false);
    update_post_meta($property_id, '_pl_price', $price);
}

function get_updated_property_price($property_id, $number_format = true)
{
    $price = (int) get_post_meta($property_id, '_pl_price', true);
    return $number_format ? CO_OWNER_CURRENCY_SYMBOL . " " . number_format($price) : $price;
}


function get_property_total_members($id, $without_admin = false)
{
    $members = CoOwner_Groups::get_group_members($id);
    if ($without_admin) {
        $members = array_filter($members, function ($user) {
            if (!$user->is_admin) {
                return $user;
            }
        });
    }
    return $members;
}

function get_property_total_members_without_admin($property_id)
{
    $members = get_property_total_members($property_id);
    return array_filter($members, function ($member) {
        if (!$member->is_admin && $member->interested_in > 0) {
            return $member;
        }
    });
}

function get_user_post_count($user_id)
{
    $meta_filter = array();
    $total = 0;
    $status = get_user_meta($user_id, '_user_listing_status', true);
    $total = $status > 0 ? 1 : 0;
    global $wpdb;
    $sql = "SELECT count(*) as count FROM {$wpdb->posts} where post_author = {$user_id} AND post_status in ('publish','completed') AND post_type = 'property'";
    $result = $wpdb->get_row($sql);
    return $total + $result->count;
}


function get_pl_enable_pool($property_id)
{
    return get_post_meta($property_id, '_pl_enable_pool', true);
}
function get_pl_interested_in_selling($property_id)
{
    return get_post_meta($property_id, '_pl_interested_in_selling', true);
}
function get_pl_property_market_price($property_id, $format = false)
{
    $price = (int) get_post_meta($property_id, '_pl_property_market_price', true);
    return ($price && $format) ? CO_OWNER_CURRENCY_SYMBOL . ' ' . number_format($price) : $price;
}
function get_pl_i_want_to_sell($property_id, $format = false)
{
    $price = (int) get_post_meta($property_id, '_pl_i_want_to_sell', true);
    return ($price && $format) ? number_format($price) : $price;
}
function get_pl_calculated($property_id, $format = false)
{
    $price = (int) get_post_meta($property_id, '_pl_calculated', true);
    return ($price && $format) ? CO_OWNER_CURRENCY_SYMBOL . ' ' . number_format($price) : $price;
}
function get_pl_property_original_price($property_id, $format = false)
{
    $price = (int) get_post_meta($property_id, '_pl_property_original_price', true);
    return ($price && $format) ? CO_OWNER_CURRENCY_SYMBOL . ' ' . number_format($price) : $price;
}

function get_property_detail_by_id($id)
{
    $property = get_post($id);

    if ($property && $property->post_type != 'property') {
        $property = null;
    }

    $user_id = get_current_user_id();

    if ($property && !($property->post_status == 'publish' || (in_array($property->post_status, ['draft', 'completed', 'auto-draft']) && $property->post_author == $user_id))) {
        $property = null;
    }

    if ($property) {
        $property->property_category = get_post_meta($id, '_pl_property_category', true);
        $property->property_type = get_post_meta($id, '_pl_property_type', true);
        $property->posted_by = get_post_meta($id, '_pl_posted_by', true);
        $property->posted_by = strtolower($property->posted_by) == 'agent' ? 'Agent/Non Owner' : $property->posted_by;
        $property->negotiable = get_post_meta($id, '_pl_negotiable', true);
        $property->age_year_built = get_post_meta($id, '_pl_age_year_built', true);

        $property->address = get_property_full_address($id);
        $address_manually = get_post_meta($id, '_pl_address_manually', true);
        $property->address_manually = $address_manually ? true : false;
        $property->unit_no = get_post_meta($id, '_pl_unit_no', true);
        $property->suburb = get_post_meta($id, '_pl_suburb', true);
        $property->only_display_suburb_in_my_ad = get_post_meta($id, '_pl_only_display_suburb_in_my_ad', true);
        $property->street_no = get_post_meta($id, '_pl_street_no', true);
        $property->postcode = get_post_meta($id, '_pl_postcode', true);
        $property->street_name = get_post_meta($id, '_pl_street_name', true);
        $property->state = get_post_meta($id, '_pl_state', true);

        $property->building_area = get_post_meta($id, '_pl_building_area', true);
        $property->land_area = get_post_meta($id, '_pl_land_area', true);
        $property->bathroom = (int) get_post_meta($id, '_pl_bathroom', true);
        $property->bedroom = (int) get_post_meta($id, '_pl_bedroom', true);
        $property->parking = (int) get_post_meta($id, '_pl_parking', true);
        $property->property_features = get_post_meta($id, '_pl_property_features', true);
        $property->manually_features = get_post_meta($id, '_pl_manually_features', true);
        $property->interested_in_selling = get_pl_interested_in_selling($id);
        $property->this_property_is = get_post_meta($id, '_pl_this_property_is', true);
        $property->currently_on_leased = get_post_meta($id, '_pl_currently_on_leased', true);
        $property->rent_per_month = get_post_meta($id, '_pl_rent_per_month', true);
        $property->enable_pool = get_pl_enable_pool($id);
        $property->property_market_price = get_pl_property_market_price($id);
        $property->i_want_to_sell = get_pl_i_want_to_sell($id);
        $property->calculated = get_pl_calculated($id);
        $property->property_original_price = get_pl_property_original_price($id);

        $property->images = get_post_meta($id, '_pl_images', true);
        $property->croppedImages = get_post_meta($id, '_pl_images_cropped', true);
        $property->first_image = isset($property->images[0]) ? $property->images[0] : null;
        $property->is_liked = get_property_is_liked($property->ID);

        $property->available_share = get_property_available_share($property->ID);
        $property->available_price = get_property_available_price($property->ID);
        $property->members = get_property_total_members($property->ID);
        $members = array_filter($property->members, function ($user) {
            if (!$user->is_admin) {
                return $user;
            }
        });
        $property->total_members_without_admin = count($members);

        $property->is_already_member = count(array_filter($property->members, function ($p_user) use ($user_id) {
            if (!$p_user->is_admin && $p_user->id == $user_id) {
                return $p_user;
            }
        })) > 0;
    }
    return $property;
}

function get_property_detail()
{
    $property = null;
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $property = get_property_detail_by_id($_GET['id']);
    }
    return $property;
}

function get_similar_properties($filters)
{
    $price = (isset($filters['price']) && $filters['price']) ? $filters['price'] : null;
    $state = (isset($filters['state']) && $filters['state']) ? $filters['state'] : null;
    $exclude = (isset($filters['exclude']) && $filters['exclude']) ? $filters['exclude'] : null;

    if ($price) {
        $meta_filter[] = array(
            'key' => '_pl_property_original_price',
            'value' => $price,
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
    }

    if ($state) {
        $meta_filter[] = array(
            'key' => '_pl_state',
            'value' => $state,
            'compare' => '='
        );
    }

    $args = array(
        "exclude"          => $exclude,
        "orderby"          => "post_date",
        "order"            => 'DESC',
        "post_type"        => "property",
        "post_status"      => "publish",
        "meta_query"       => $meta_filter,
        'numberposts'      => 2,
    );
    $property = get_posts($args);
    $response = array();
    foreach ($property as $item) {
        $title = ucfirst($item->post_title);
        $images = get_post_meta($item->ID, '_pl_images', true);
        $image_url = (isset($images[0]) && isset($images[0]['url'])) ? $images[0]['url'] :  get_template_directory_uri() . '/images/property-1.jpg';
        $market_price = get_post_meta($item->ID, '_pl_property_original_price', true);
        $address = get_property_full_address($item->ID);
        $response[] = (object) array(
            'ID'        => $item->ID,
            'title'     => $title,
            'address'   => $address,
            'image'     => $image_url,
            'price'     => $market_price,
        );
    }
    return $response;
}
// PROPERTY DETAIL PAGE


function get_user_budget($user_id)
{
    $budget = get_user_meta($user_id, '_user_budget', true);
    return $budget ? (int) $budget : 0;
}
function get_user_status($user_id = null)
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    $user_status = get_user_meta($user_id, '_user_status', true);
    return empty($user_status) ? 2 : $user_status;
}
function get_person_detail_by_id($id)
{
    $person = new WP_User($id);

    if ($person) {
        $person->user_status = get_user_status($person->ID);
        $person->full_name = get_user_full_name($person->ID);

        $person->listing_status = get_user_meta($person->ID, '_user_listing_status', true);
        $property_category = get_user_meta($person->ID, '_user_property_category', true);
        $person->property_category = empty($property_category) ? array() : $property_category;
        $property_type = get_user_meta($person->ID, '_user_property_type', true);
        $person->property_type = empty($property_type) ? array() : $property_type;
        $person->descriptions = get_user_meta($person->ID, '_user_descriptions', true);
        $preferred_location = get_user_meta($person->ID, '_user_preferred_location', true);
        $person->preferred_location = empty($preferred_location) ? array() : $preferred_location;
        $person->land_area = get_user_meta($person->ID, '_user_land_area', true);
        $person->building_area = get_user_meta($person->ID, '_user_building_area', true);
        $person->age_year_built = get_user_meta($person->ID, '_user_age_year_built', true);
        $person->price_range = get_user_meta($person->ID, '_user_budget_range', true);
        $user_bedroom = get_user_meta($person->ID, '_user_bedroom', true);
        $person->bedroom = empty($user_bedroom) ? 0 : $user_bedroom;
        $user_bathroom = get_user_meta($person->ID, '_user_bathroom', true);
        $person->bathroom = empty($user_bathroom) ? 0 : $user_bathroom;
        $user_parking = get_user_meta($person->ID, '_user_parking', true);
        $person->parking = empty($user_parking) ? 0 : $user_parking;
        $property_features = get_user_meta($person->ID, '_user_property_features', true);
        $person->property_features = empty($property_features) ? array() : $property_features;
        $manually_features = get_user_meta($person->ID, '_user_manually_features', true);
        $person->manually_features = (is_array($manually_features) && count($manually_features) > 0) ? $manually_features : array();
        $person->budget = get_user_budget($person->ID);
        $person->budget = $person->budget == null || empty($person->budget) ? 0 : $person->budget;
        $person->enable_pool = get_user_meta($person->ID, '_user_enable_pool', true);
        $person->enable_pool = $person->enable_pool == null || empty($person->enable_pool) ? 0 : $person->enable_pool;
        $person->is_liked = get_people_is_liked($person->ID);
        $person->mobile = get_user_meta($person->ID, '_mobile', true);
        $person->mobile = $person->mobile == null || empty($person->mobile) ? 0 : $person->mobile;

        $person->google_linked = get_user_meta($person->ID, '_user_google_id', true);
        $person->facebook_linked = get_user_meta($person->ID, '_user_facebook_id', true);
        $person->instagram_linked = get_user_meta($person->ID, '_user_instagram_id', true);
        $person->linkedin_linked = get_user_meta($person->ID, '_user_linkedin_id', true);

        $person->is_email_verified = get_user_meta($person->ID, '_user_is_email_verified', true);
        $person->is_email_verified = $person->is_email_verified == null || empty($person->is_email_verified) ? 0 : $person->is_email_verified;

        $person->is_mobile_verified = get_user_meta($person->ID, '_user_is_mobile_verified', true);
        $person->is_mobile_verified = $person->is_mobile_verified == null || empty($person->is_mobile_verified) ? 0 : $person->is_mobile_verified;
    }
    return $person;
}

function get_person_detail()
{
    $person = null;
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $person = get_person_detail_by_id($_GET['id']);
    }
    return $person;
}

function get_my_properties_options($person_id)
{
    $user_id = get_current_user_id();
    $args = array(
        'author'           => $user_id,
        "orderby"          => "post_date",
        "order"            => "DESC",
        "post_type"        => "property",
        "post_status"      => "publish",
    );
    $args["numberposts"]      = -1;
    $options = array();
    foreach (get_posts($args) as $property) {
        $property_share = get_property_available_share($property->ID);
        if ($property_share > 0) {
            $options[] = (object)array(
                'ID' => $property->ID,
                'title' => $property->post_title,
                'address' => get_property_full_address($property->ID),
                'enable_pool' => get_post_meta($property->ID, '_pl_enable_pool', true),
                'available_share' => get_property_available_share($property->ID),
                'available_price' => get_property_available_price($property->ID),
                'members' => get_property_total_members($property->ID),
            );
        }
    }

    return $options;
}

function calculate_property_share_interest($available_share, $interested_in, $available_price)
{
    $result = (((int) $interested_in * (float) $available_price) / (int) $available_share);
    return round(((float) $result > 0 ? (float) $result : 0), 2);
}



// MESSAGE PAGE

function get_connection_requests($is_received = true, $is_group = false, $is_single = false)
{
    if (!$is_group) {
        global $wpdb;
        $table = $wpdb->prefix . CO_OWNER_CONNECTIONS_TABLE;
        $sender_or_receiver = $is_received ? 'receiver_user' : 'sender_user';
        $user_id = get_current_user_id();
        $where = array(
            $table . '.' . $sender_or_receiver => $user_id,
            $table . '.is_group' => 0,
            $table . '.status' => array(0, 2, 3)
        );
        $requests = CoOwner_Connections::connection_request($where, $is_single);

        if (!$is_single) {
            $response = array();
            foreach ($requests as $request) {
                $request->requested_user_id = $user_id != $request->sender_user ? $request->sender_user : $request->receiver_user;
                $request->requested_user_full_name = get_user_full_name($request->requested_user_id);
                $request->requested_user_shield_status = get_user_shield_status($request->requested_user_id);
                $request->requested_property = $request->property_id ? get_property_full_address($request->property_id) : null;
                $response[] = $request;
            }
            return $response;
        }
        return $requests;
    } else {
        return array();
    }
}

function get_connection_request($id, $is_group = false, $is_received = true)
{
    $result = null;
    if ($id) {
        global $wpdb;
        $table = $wpdb->prefix . CO_OWNER_CONNECTIONS_TABLE;
        $sender_or_receiver = $is_received ? 'receiver_user' : 'sender_user';
        $result = CoOwner_Connections::connection_request(array(
            "$table.id" => $id,
            $table . '.' . $sender_or_receiver => get_current_user_id(),
            "$table.is_group" => $is_group ? 1 : 0,
            "$table.status" => array(0, 2, 3)
        ), true);
    }
    return $result;
}

function getConversationTime($user_id,$uid){
	/* When group false */
	global $wpdb;
	
	
	$sql="SELECT cv1.created_at, cv1.id
                   FROM  wp_co_owner_conversation cv1
                   WHERE ";
				   
					$sql.= " ( cv1.sender_user='$user_id' OR cv1.receiver_user='$user_id' )";
				   $sql.= " AND ( cv1.sender_user='$uid' OR cv1.receiver_user='$uid' )";
				   
				   
				   $sql.= " ORDER BY cv1.id DESC limit 1";
		// echo  $sql;
	return $wpdb->get_row($sql);
	
} 


/* GET CONNECTIONS SELECT (MY GROUPS or JOINED GROUPS) OR (CONNECTED CONNECTIONS)  */
function get_connected_connections($is_group = false)
{
    $auth_user = get_current_user_id();
    $response = array();
    $check_message_count = is_page(CO_OWNER_MESSAGE_PAGE);

    if ($is_group) {
	
        $response = CoOwner_Groups::get_joined_groups($auth_user)->get();
        foreach ($response as $con) {
            $total_unread = $check_message_count ? CoOwner_Notifications::count(array(
                'receiver_user' => $auth_user,
                'read_at'       => null,
                'group_id'      => $con->id,
                'notify_type'   => 3,
            )) : 0;
            $con->unread = $total_unread;
        }
    } else {
		
        $result = CoOwner_Connections::get_connected_users($auth_user);
        foreach ($result as $con) {
            $user_id = $con->receiver_user == $auth_user ? $con->sender_user : $con->receiver_user;
            $email = $con->receiver_user == $auth_user ? $con->sender_user_email : $con->receiver_user_email;

            $total_unread = $check_message_count ? CoOwner_Notifications::count(array(
                'sender_user'   => $user_id,
                'receiver_user' => $auth_user,
                'read_at'       => null,
                'group_id'      => null,
                'notify_type'   => 3,
            )) : 0;
	              ;
									   $col_name='sender_user';
									  if(empty($con->group_id)){
									     $col_name='receiver_user';
										 
									  }
									  
									  $messagedata =  getConversationTime($auth_user,$uid=$user_id);		
		
           
            $response[] = (object) array(
                'connection_id' => $con->id,
                'id' => $user_id,
                'name' => get_user_full_name($user_id),
                'email' => $email,
                'profile' => get_avatar_url($user_id),
                'mobile' => get_user_meta($user_id, '_mobile', true),
                'unread' => $total_unread,
                'user_status' => get_user_status($user_id),
                'user_shield_status' => get_user_shield_status($user_id),
                'property_name' => $con->property_id ? get_property_full_address($con->property_id) : null,
				'created_at'=>$con->created_at,
				'group_id'=>$con->group_id,
				'message_id'=>$messagedata->id,
				'message_date'=>$messagedata->created_at,
				
			
            );
        }
    }
	
	array_multisort(array_map(function($element) {
      return $element->message_id;
  }, $response), SORT_DESC, $response);

    return $response;
}

function get_chat_with_connection($with, $is_group = false)
{
    $response = null;
    if ($with) {
        $response = CoOwner_Connections::get_chat_with_connection($with, $is_group);
    }
    return $response;
}

function get_url_into_hyperlink($string)
{
    $reg_exUrl = "/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/";
    if (preg_match_all($reg_exUrl, $string, $url)) {
        return $url[0];
    }
    return array();
}
// MESSAGE PAGE

function get_user_full_name($ID)
{
    $first_name =  get_user_meta($ID, 'first_name', true);
    $last_name = get_user_meta($ID, 'last_name', true);
    if (strlen(trim($first_name)) == 0 && strlen(trim($last_name)) == 0) {
        $user = new WP_User($ID);
        return $user->display_name ? $user->display_name : $user->user_login;
    } else {
        return ucfirst($first_name) . ' ' . $last_name;
    }
}


function get_all_my_connections($type)
{
    return get_connected_connections($type == 'pools' ? true : false);
}



/* GET MY LISTING PAGE */

function get_my_properties($status = 'publish')
{
    $userId = get_current_user_id();
    $args = array(
        "orderby"          => "post_date",
        "author"           => $userId,
        "order"            => "DESC",
        "post_type"        => "property",
        "post_status"      => $status,
        "posts_per_page"   => -1
    );
    $properties = get_posts($args);
    foreach ($properties as $property) {
        $property->property_category = get_post_meta($property->ID, '_pl_property_category', true);
        $property->enable_pool = get_post_meta($property->ID, '_pl_enable_pool', true);
        $property->image = get_property_first_image($property->ID);
        $property->market_price = get_post_meta($property->ID, '_pl_property_original_price', true);
        $property->address = get_property_full_address($property->ID);
        $interested_in_selling = get_post_meta($property->ID, '_pl_interested_in_selling', true);
        $property->i_want_to_sell = $interested_in_selling !== 'full_property' ? get_post_meta($property->ID, '_pl_i_want_to_sell', true) : null;
        $property->bathroom = get_post_meta($property->ID, '_pl_bathroom', true);
        $property->bedroom = get_post_meta($property->ID, '_pl_bedroom', true);
        $property->parking = get_post_meta($property->ID, '_pl_parking', true);
    }
    return $properties;
}

/* GET MY LISTING PAGE */

function get_people_requested_for_the_same_pool($property_id, $user_id)
{
    return CoOwner_Connections::get_people_requested_for_the_same_pool($property_id, $user_id);
}


function remove_property_image($post_id, $index)
{
    $images = get_post_meta($post_id, '_pl_images', true);
    $croppedImages = get_post_meta($post_id, '_pl_images_cropped', true);

	
    if (is_array($images)) {
        if (count($images) > 0) {
            $old = array();
            foreach ($images as $key => $image) {
                if ($index == $key) {
                    if (file_exists($image['file'])) {
                        unlink($image['file']);
                    }
                } else {
                    $old[] = $image;
                }
            }
            update_post_meta($post_id, '_pl_images', $old);
			/* Create new image from saved property Images  added Techinno */
			makePropertiesImageCroppedByID($post_id);
			
			
            $response = array(
                'status' => true,
                'message' => 'Image Removed Successfully.'
            );
        } else {
            $response = array(
                'status' => false,
                'message' => 'Please You don\'t remove all images.'
            );
        }
    } else {
        $response = array(
            'status' => false,
            'message' => 'Something went wrong please try again'
        );
    }
    return (object) $response;
}

function remove_user_document($user_id, $index)
{
    $document = get_user_meta($user_id, '_user_profile_documents', true);
    if (is_array($document)) {
        $old = array();
        foreach ($document as $key => $image) {
            if ($index == $key) {
                if (file_exists($image['file'])) {
                    unlink($image['file']);
                }
            } else {
                $old[] = $image;
            }
        }
        update_user_meta($user_id, '_user_profile_documents', $old);
        return true;
    } else {
        return false;
    }
}

function get_user_shield_status($user_id)
{
    return get_user_meta($user_id, '_document_shield_status', true);
}

function get_user_shield($user_id, $big = false)
{
    $status = get_user_shield_status($user_id);
    if ($status == 1) {
        return co_owner_get_svg($big ? 'big_shield' : 'shield');
    }
    return "";
}

function check_is_maching_property($user_id, $property_id)
{
    $user = get_person_detail_by_id($user_id);
    $property = new stdClass();
    $property->property_category = get_post_meta($property_id, '_pl_property_category', true);
    $property->property_category = get_post_meta($property_id, '_pl_property_category', true);
    $property->property_type = get_post_meta($property_id, '_pl_property_type', true);
    $property->state = get_post_meta($property_id, '_pl_state', true);
    $property->land_area = get_post_meta($property_id, '_pl_land_area', true);
    $property->building_area = get_post_meta($property_id, '_pl_building_area', true);
    $property->age_year_built = (int) get_post_meta($property_id, '_pl_age_year_built', true);
    $property->enable_pool = (int) get_post_meta($property_id, '_pl_enable_pool', true);
    $property->property_original_price = (int) get_post_meta($property_id, '_pl_property_original_price', true);


    $is_match = false;
    $is_residential = $property->property_category == 'residential';
    if (
        in_array($property->property_category, $user->property_category) &&
        in_array($property->property_type, $user->property_type) &&
        in_array($property->state, $user->preferred_location) &&
        (strpos(strtolower($user->land_area), strtolower($property->land_area)) >= 0) &&
        (strpos(strtolower($user->building_area), strtolower($property->building_area)) >= 0) &&
        ($user->age_year_built >= $property->age_year_built) &&
        ($user->enable_pool == $property->enable_pool) &&
        ($user->budget >= $property->property_original_price)
    ) {
        $is_match = true;
        if ($is_residential) {
            $property->bedroom = get_post_meta($property_id, '_pl_bedroom', true);
            $property->bathroom = get_post_meta($property_id, '_pl_bathroom', true);
            $property->parking = get_post_meta($property_id, '_pl_parking', true);
            if (
                ($user->bedroom >= $property->bedroom) &&
                ($user->bathroom >= $property->bathroom) &&
                ($user->parking >= $property->parking)
            ) {
                $is_match = true;
            }
        }
    }
    return $is_match;
}

function number_format_short($num)
{
    if ($num > 1000) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('K', 'M', 'B', 'T');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . substr($x_array[1], 0, 2) : '');
        $x_display .= isset($x_parts[$x_count_parts - 1]) ? $x_parts[$x_count_parts - 1] : 'T';
        return $x_display;
    }
    return $num;
}

function price_range_show($price)
{

    foreach (get_price_range_dropdown_options() as $p_value => $p_key) :
        if ($price == $p_value)
            return $p_key;
    endforeach;
}



// define the get_avatar_url callback
function filter_get_avatar_url($url, $id_or_email, $args)
{
    $avatar = get_user_meta($id_or_email, '_user_profile_avatar', true);
    if ($avatar && isset($avatar['url'])) {
        return $avatar['url'];
    }
    return CO_OWNER_THEME_DIR_URI . '/images/avatar.png';
};

// add the filter
add_filter('get_avatar_url', 'filter_get_avatar_url', 10, 3);

function property_mark_as_complete($id, $status = 'completed')
{
    $update_post = array(
        'post_type' => 'property',
        'ID' => $id,
        'post_status' => $status,
        'edit_date' => true,
    );
    wp_update_post($update_post);
    $enable_pool = get_post_meta($id, '_pl_enable_pool', true);
    if ($enable_pool) {
        $group = CoOwner_Groups::find(array('property_id' => $id));
        if ($group) {
            $status = $status == 'completed' ? 2 : 1;
            CoOwner_Groups::update($group->id, 'status', (int) $status);
        }
    }
}

function co_owner_init_actions()
{
    add_action('d4p_bbpresstools_bbcode_notice', function () {
        echo "<span class='remove-my-parent'></span>";
    });

    if (
        isset($_GET['co_owner_auto_login']) &&
        isset($_GET['username']) && !empty($_GET['username']) &&
        isset($_GET['password']) && !empty($_GET['password'])
    ) {
        $credentials = array(
            'user_login' => $_GET['username'],
            'user_password' => base64_decode($_GET['password'])
        );
        $user_obj = wp_signon($credentials, is_ssl());
        if ($user_obj instanceof WP_User) {
            wp_set_current_user($user_obj->ID, $user_obj->user_login);
        }
        wp_redirect(home_url());
        exit();
    }

    if (isset($_GET['download_file']) && !empty($_GET['download_file']) && is_numeric($_GET['download_file'])) {
        CoOwner_Conversation_Files::download_file($_GET['download_file']);
    }

    if (isset($_GET['ts_test'])) {
        $user_id = $_GET['ts_test'];
        $user = new WP_User($user_id);
        wp_set_current_user($user_id, $user->user_login);
        wp_set_auth_cookie($user_id);
        do_action('wp_login', $user->user_login, $user);
    }
}

add_action('init', 'co_owner_init_actions');

/*Redirect Code here*/
add_action('template_redirect', function () {
    $old = get_permalink();

    $pages = array();

    $user = wp_get_current_user();
	/* Ask subscription when user created with social accounts  */
	$is_user_created_by_social = false;	
	$google_linked = get_user_meta($user->ID, '_user_google_id', true);
	$facebook_linked = get_user_meta($user->ID, '_user_facebook_id', true);
	$instagram_linked = get_user_meta($user->ID, '_user_instagram_id', true);
	$linkedin_linked = get_user_meta($user->ID, '_user_linkedin_id', true);	
	if($google_linked || $facebook_linked || $instagram_linked || $linkedin_linked){
		$is_user_created_by_social = true;
	}
	/* Ask subscription when user created with social accounts end  */

    if (current_user_can('administrator')) {
        if (is_page('shortlist') || is_page('messages')) {
            wp_redirect(home_url());
            die;
        }
        if (
            is_page(CO_OWNER_MY_ACCOUNT_PAGE) ||
            is_page(CO_OWNER_MY_LISTINGS_PAGE) ||
            is_page(CO_OWNER_MY_ACCOUNT_VERIFICATION) ||
            is_page(CO_OWNER_MY_CONNECTIONS_PAGE) ||
            is_page(CO_OWNER_MY_NOTIFICATION_SETTINGS)
        ) {
            wp_redirect(admin_url('profile.php'));
            die;
        }
        if (is_page(CO_OWNER_CREATE_A_PROPERTY_PAGE)) {
            wp_redirect(admin_url('post-new.php?post_type=property'));
            die;
        }
        if (is_page(CO_OWNER_CREATE_A_PERSON_PAGE)) {
            wp_redirect(admin_url('user-new.php'));
            die;
        }
    }

    if (function_exists('carbon_get_theme_option')) {
        $pages = carbon_get_theme_option('crb_protect_pages');
    }

    $page = get_the_ID();

    $is_admin = in_array('administrator', $user->roles);

    if (
        is_page(CO_OWNER_MESSAGE_PAGE) ||
        is_page(CO_OWNER_SHORTLIST_PAGE) ||
        is_page(CO_OWNER_CREATE_A_PROPERTY_PAGE) ||
        is_page(CO_OWNER_CREATE_A_PERSON_PAGE)
    ) {
        $status = get_user_status($user->ID);
        if ($status != 1) {
            wp_redirect(home_url(CO_OWNER_MY_ACCOUNT_PAGE) . '?alert=your_account_is_inactive');
            exit;
        }
    }

    if (!$user->exists() && !is_front_page() && in_array($page, $pages)) {
        wp_redirect(home_url('/login?redirect_to=' . base64_encode(get_permalink())));
        exit;
    }
    if (is_page(CO_OWNER_PROPERTY_DETAILS_PAGE) && !isset($_GET['id']) && !is_numeric($_GET['id'])) {
        wp_redirect(home_url());
        exit;
    } elseif (is_page(CO_OWNER_PROPERTY_FORM_PAGE) && isset($_GET['id'])) {
        $property = get_post($_GET['id']);
        if ($property->post_author != $user->ID) {
            wp_redirect(home_url(CO_OWNER_PROPERTY_DETAILS_PAGE) . '?id=' . $_GET['id'] . '&alert=you_cannot_edit_this_property');
            exit;
        }
    } elseif (is_page(CO_OWNER_CREATE_A_PROPERTY_PAGE) && isset($_GET['id'])) {
        $property = get_post($_GET['id']);
        if ($property) {
            if ($property->post_author != $user->ID) {
                wp_redirect(CO_OWNER_CREATE_A_PROPERTY_PAGE . '?alert=error&alert_message=You don\'t have permission for edit property. ');
                exit;
            }
        }
    } elseif (is_singular('property') && $page) {
       // wp_redirect(CO_OWNER_PROPERTY_DETAILS_PAGE . "?id=$page");
        //exit();
    } elseif (is_singular('faqs')) {
        wp_redirect(home_url(CO_OWNER_FAQS_PAGE));
        exit();
    } elseif (is_singular('feedback')) {
        wp_redirect(home_url(CO_OWNER_FAQS_PAGE));
        exit();
    }

    // Check user mobile is unverified then redirect to verify mobile no.
    $is_verified_mobile = false;
    if ($user->exists() && !$is_admin && !is_page(CO_OWNER_MY_ACCOUNT_VERIFICATION)) {
        $is_verified_mobile = get_user_meta($user->ID, '_user_is_mobile_verified', true);
        if ($is_verified_mobile != 1) {
            //wp_redirect(CO_OWNER_MY_ACCOUNT_VERIFICATION);
            //exit();
        }
    }
	
/* Ask subscription when user created with social accounts  */
   if( is_user_logged_in() && $is_user_created_by_social){
        $role = co_owner_get_user_field('s2member_access_role', $user->ID);

        if (isset($_GET['co_owner_action']) && $_GET['co_owner_action'] == 'active_trial_subscription' && $user) {
            $user = wp_get_current_user();
            $is_admin = in_array('administrator', $user->roles);
            if (!$is_admin) {
                $response = register_user_subscription_information($user, 'trial');
                if ($response) {
                    wp_redirect(home_url("?subscription=success"));
                    exit();
                }
            }
        } // ?co_owner_action=active_trial_subscription

        if ((isset($_GEt['action']) && $_GET['action'] != 'subscription' || !isset($_GET['action']))) {
            $sub_type = get_user_meta($user->ID, '_user_subscription_type', true);
            $is_subscr_id = co_owner_get_user_field('s2member_subscr_id', $user->ID);


            if ($role == 'subscriber' && empty($is_subscr_id) && !empty($sub_type) && $sub_type != 'trial' && !$is_admin) {
                wp_redirect(home_url("?action=subscription&subscription_type={$sub_type}"));
                exit();
            }
        }

        $is_expire = false;
        if ($role == 's2member_level0') {
            $is_expire = user_plan_is_expire($user);
        }

        if (
            ($is_expire || !in_array($role, ['s2member_level0', 's2member_level1', 's2member_level2'])) &&
            (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] != 'subscription')) &&
            (!isset($_GET['subscription']) || (isset($_GET['subscription']) && $_GET['subscription'] != 'subscription')) &&
            !$is_admin
        ) {
			/* #change007  */
			$usergetSteps =		 get_user_meta($user->ID,'steps_complete',true);
		     wp_redirect(home_url("?action=subscription"));
					 exit();
			/*if($usergetSteps){} */
       
            
        }	  
	   
   }
/* Ask subscription when user created with social accounts end  */

    // Check user have a subscription if no then always show subscription and payment modal
    if ($user->exists() && $is_verified_mobile == true) {
        $role = co_owner_get_user_field('s2member_access_role', $user->ID);

        if (isset($_GET['co_owner_action']) && $_GET['co_owner_action'] == 'active_trial_subscription' && $user) {
            $user = wp_get_current_user();
            $is_admin = in_array('administrator', $user->roles);
            if (!$is_admin) {
                $response = register_user_subscription_information($user, 'trial');
                if ($response) {
                    wp_redirect(home_url("?subscription=success"));
                    exit();
                }
            }
        } // ?co_owner_action=active_trial_subscription

        if ((isset($_GEt['action']) && $_GET['action'] != 'subscription' || !isset($_GET['action']))) {
            $sub_type = get_user_meta($user->ID, '_user_subscription_type', true);
            $is_subscr_id = co_owner_get_user_field('s2member_subscr_id', $user->ID);


            if ($role == 'subscriber' && empty($is_subscr_id) && !empty($sub_type) && $sub_type != 'trial' && !$is_admin) {
                wp_redirect(home_url("?action=subscription&subscription_type={$sub_type}"));
                exit();
            }
        }

        $is_expire = false;
        if ($role == 's2member_level0') {
            $is_expire = user_plan_is_expire($user);
        }

        if (
            ($is_expire || !in_array($role, ['s2member_level0', 's2member_level1', 's2member_level2'])) &&
            (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] != 'subscription')) &&
            (!isset($_GET['subscription']) || (isset($_GET['subscription']) && $_GET['subscription'] != 'subscription')) &&
            !$is_admin
        ) {
            wp_redirect(home_url("?action=subscription"));
            exit();
        }
    }

    $data = strpos($_SERVER['REQUEST_URI'], '/forum/users/');
    if ($data >= 0) {
        $username = str_replace(["/forum/users/"], "", $_SERVER['REQUEST_URI']);
        $username = explode('/', $username);
        if ($forum_user = get_user_by('slug', $username[0])) {
            wp_redirect(home_url(CO_OWNER_PERSON_DETAILS_PAGE . "?id={$forum_user->ID}"));
            exit;
        }
    }


    if (
        $user->exists() &&
        isset($_GET['update_property_status']) &&
        isset($_GET['id'])
    ) {
        if (get_user_status($user->ID) != 1) {
            wp_redirect(home_url(CO_OWNER_MY_ACCOUNT_PAGE) . '?alert=your_account_is_inactive');
            exit;
        }

        $post = get_post($_GET['id']);
        if ($post && $post->post_author == $user->ID) {
            wp_update_post(array(
                'ID' => $post->ID,
                'post_status' => 'publish'
            ));
            $page = CO_OWNER_PROPERTY_DETAILS_PAGE;
            if (is_page(CO_OWNER_MY_LISTINGS_PAGE)) {
                $page = CO_OWNER_MY_LISTINGS_PAGE;
            }
            //wp_redirect(home_url("{$page}?id={$post->ID}&alert=success&alert_message=Great work! Your listing has been posted successfully."));
            //exit();
        }
    }

    if (
        $user->exists() &&
        isset($_GET['action']) &&
        !empty($_GET['action']) &&
        $_GET['action'] == 'property_mark_as_completed' &&
        isset($_GET['id']) &&
        !empty($_GET['id']) &&
        is_numeric($_GET['id'])
    ) {
        if (get_user_status($user->ID) != 1) {
            wp_redirect(home_url(CO_OWNER_MY_ACCOUNT_PAGE) . '?alert=your_account_is_inactive');
            exit;
        }

        $id = $_GET['id'];
        $post = get_post($id);
        if ($post && $post->post_author == $user->ID) {
            property_mark_as_complete($id, 'completed');
           // wp_redirect(home_url(CO_OWNER_PROPERTY_DETAILS_PAGE) . "?id={$id}&alert=property_mark_as_completed");
            //die;
        }
    }

    if (
        $user->exists() &&
        isset($_GET['update_person_status']) &&
        isset($_GET['id'])
    ) {
        $is_update = update_user_meta($_GET['id'], '_user_listing_status', $_GET['update_person_status']);
        if ($is_update) {
            $page = CO_OWNER_PERSON_DETAILS_PAGE;
            wp_redirect(home_url("{$page}?id={$_GET['id']}&alert=success&alert_message=Great work! Your listing has been posted successfully."));
            exit();
        }
    }
}, 10, 1);


add_filter('ws_plugin__s2member_login_redirect', function ($user_login, $args) {
    return false;
}, 11, 2);

function co_owner_show_admin_bar()
{
    $user = wp_get_current_user();
    return in_array('administrator', $user->roles);
}
add_filter('show_admin_bar', 'co_owner_show_admin_bar');

add_filter('login_url', 'custom_login_url', 10, 3);
function custom_login_url($login_url, $redirect, $force_reauth)
{
    return home_url('/login/?redirect_to=' . $redirect);
}

add_action("delete_post_meta", 'co_owner_delete_post', 10, 4);
function co_owner_delete_post($meta_ids, $object_id, $meta_key, $_meta_value)
{
    if ($meta_key == '_pl_images' and is_array($_meta_value)) {
        foreach ($_meta_value as $image) {
            if (file_exists($image['file'])) {
                unlink($image['file']);
            }
        }
    }
}


add_action('bbp_theme_before_topic_form_submit_button', 'co_owner_bbp_theme_before_topic_form_submit_button');
function co_owner_bbp_theme_before_topic_form_submit_button()
{
    echo '<a href="#" data-bs-dismiss="modal" class="btn btn-orange-text rounded-pill">Cancel</a>';
}

function co_owner_bbp_rc_reported_reply($replay_id)
{
    $post = get_post($replay_id);
    $type = $post->post_type;

    if ($post->post_type == 'reply') {
        $topic_id = get_post_meta($replay_id, '_bbp_topic_id', true);
        $post = get_post($topic_id);
    }
    $content = get_the_excerpt($post);
    $user = get_user_by('ID', $post->post_author);
    if ($user) {
        $auth_user_id = get_current_user_id();
        $auth_user = get_user_full_name($auth_user_id);
        $site = get_bloginfo('name');
        $username = ucfirst($user->first_name);
        $subject = "Property Mates Forum: Reply reported by user";

        $html = "
            Hey! {$username}<br><br>
            Your {$type} has been reported by a user. Admin will review the content and get back to you if it was done by mistake.<br><br>
            " . (ucfirst($post->post_type)) . " :- {$post->post_title}<br><br> 
            Excerpt: {$content}<br><br>
            Best Regards,<br>
            {$site}
        ";
        wp_mail($user->user_email, $subject, $html, array('Content-Type: text/html; charset=UTF-8'));
        CoOwner_Notifications::create_entry($auth_user_id, $user->ID, '', '', '');
    }
}
add_action('bbp_rc_reported_reply', 'co_owner_bbp_rc_reported_reply', 10, 1);
add_action('bbp_rc_reported_topic', 'co_owner_bbp_rc_reported_reply', 10, 1);


function co_owner_bbb_new_replay($reply_id, $topic_id)
{

    if (
        isset($_POST['bbp_topic_id']) &&
        isset($_POST['bbp_reply_to']) &&
        !empty($_POST['bbp_topic_id']) &&
        !empty($_POST['bbp_reply_to']) &&
        ($_POST['bbp_topic_id']) > 0 &&
        ($_POST['bbp_reply_to']) > 0
    ) {
        $replay_to = get_post($_POST['bbp_reply_to']);
        $topic_to = get_post($_POST['bbp_topic_id']);
        $reply = get_post($reply_id);

        $content = get_the_excerpt($reply);
        $user = get_user_by('ID', $replay_to->post_author);

        $auth_user = get_user_full_name(get_current_user_id());

        $site = get_bloginfo('name');
        $subject = "[{$site}] New reply for topic {$topic_to->post_title}";
        $user->first_name = ucfirst($user->first_name);
        $html = "Hey! <br><br>
            Hey! {$user->first_name}<br><br>
            
            A new reply has been posted by {$auth_user}. <br><br>
            
            Topic title: {$topic_to->post_title}<br>
            Topic url: {$topic_to->guid}<br><br>
            
            Excerpt:<br>
            {$content}<br><br>
            
            Best Regards,<br>
            Property Mates
        ";
        wp_mail($user->user_email, $subject, $html, array('Content-Type: text/html; charset=UTF-8'));
    }
}
add_action('bbp_new_reply', 'co_owner_bbb_new_replay', 10, 2);


function co_owner_bbp_insert_reply($reply_id = 0, $topic_id = 0, $forum_id = 0)
{
    $author_id = get_post_field('post_author', $topic_id);
    $replay = get_post($reply_id);
    if ($replay && $replay->post_author != $author_id) {
        CoOwner_Notifications::create($replay->post_author, $author_id, $replay->post_content, 8, $reply_id);
    }
}
add_action('bbp_new_reply', 'co_owner_bbp_insert_reply', 10, 3);


function co_owner_bbp_trashed_reply($reply_id)
{
    $replay = get_post($reply_id);
    if ($replay && $replay->post_type = 'reply') {
        CoOwner_Notifications::delete_row(CO_OWNER_NOTIFICATIONS_TABLE, array(
            'sender_user'   => $replay->post_author,
            'notify_type'   => 8,
            'notify_id'     => $replay->ID,
        ));
    }
}
add_action('bbp_trashed_reply', 'co_owner_bbp_trashed_reply', 10, 3);

function co_owner_bbp_trashed_topic($topic_id)
{
    $topic = get_post($topic_id);
    if ($topic && $topic->post_type = 'topic') {
        $replys = get_post_meta($topic->ID, '_bbp_pre_trashed_replies', true);
        foreach ($replys as $reply) {
            CoOwner_Notifications::delete_row(CO_OWNER_NOTIFICATIONS_TABLE, array(
                'sender_user'   => $topic->post_author,
                'notify_type'   => 8,
                'notify_id'     => $reply,
            ));
        }
    }
}
add_action('bbp_trashed_reply', 'co_owner_bbp_trashed_topic', 10, 3);


if (!is_admin()) {
    // GET PROPERTY WITH USER STATUS 1 //
    function co_owner_property_join_query_for_front($query, $type)
    {
        if (
            isset($type->query_vars) &&
            isset($type->query_vars['post_type']) &&
            $type->query_vars['post_type'] == 'property'
        ) {
            global $wpdb;
            $sql = " LEFT JOIN {$wpdb->usermeta} as um ON (um.user_id = {$wpdb->posts}.post_author AND um.meta_key = '_user_status')";
            return $query . $sql;
        }
        return $query;
    }

    add_filter('posts_join_paged', 'co_owner_property_join_query_for_front', 10, 2);

    function co_owner_property_where_query_for_front($query, $type)
    {
        if (
            isset($type->query_vars) &&
            isset($type->query_vars['post_type']) &&
            $type->query_vars['post_type'] == 'property'
        ) {
            global $wpdb;
            $sql = " AND um.meta_value = 1 ";
            return $query . $sql;
        }
        return $query;
    }

    add_filter('posts_where_paged', 'co_owner_property_where_query_for_front', 10, 2);
    // GET PROPERTY WITH USER STATUS 1 //
}


//function co_owner_dynamic_sidebar_params($parms)
//{
//    if($parms[0]['id'] == 'footer_menu_2')
//        CoOwner::print_a($parms);
//    return $parms;
//}
//add_filter('dynamic_sidebar_params','co_owner_dynamic_sidebar_params',10,1);

function co_owner_bbp_get_reply_author_display_name($username, $post_id)
{
    $post = get_post($post_id);
    $full_name = get_user_full_name($post->post_author);
    return $full_name ? $full_name : $username;
}
add_filter('bbp_get_reply_author_display_name', 'co_owner_bbp_get_reply_author_display_name', 11, 2);
add_filter('bbp_get_topic_author_display_name', 'co_owner_bbp_get_reply_author_display_name', 11, 2);
add_filter('bbp_get_forum_author_display_name', 'co_owner_bbp_get_reply_author_display_name', 11, 2);







function co_owner_wp_mail($attr)
{
    $site = get_bloginfo('name');
    if ($attr['subject'] == PROPERTY_MATES_PASSWORD_RESET) {
        $attr['headers'] = array('Content-Type: text/html; charset=UTF-8');
    } elseif ($attr['subject'] == 's2_member_subscription') {
        $user = get_user_by('email', $attr['to']);
        $type = get_user_subscription_level($user);
        $plan = get_subscription_information($type);
        $attr['subject'] = 'Welcome to Property Mates!';
        $planName = $plan->name == 'Standard' ?  'Standard' : 'Business';
        $attr['message'] = str_replace('{{plan_name}}', $planName, $attr['message']);
    }

    return $attr;
}
add_filter('wp_mail', 'co_owner_wp_mail', 10, 1);
// USER RESET PASSWORD OVERRIDE
