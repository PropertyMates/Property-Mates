<?php

class CoOwner_Notifications extends CoOwner_Model
{

    public static $table = CO_OWNER_NOTIFICATIONS_TABLE;

    /*
     *  0 = simple notification,
     *  1 = connection request notification,
     *  2 = message request notification,
     *  3 = message notification,
     *  4 = matching property notification,
     *  5 = matching person notification,
     *  6 = property shortlist
     *  7 = person shortlist
     *  8 = forum replay
     *  9 = accept request
     *  10 = Add to pool
     *  11 = Leave from pool
     */

    public static function create_entry($sender_user, $receiver_user, $type, $type_id, $message, $group_id = null)
    {
        if ($sender_user == $receiver_user) {
            return true;
        }
        global $wpdb;
        $array = array(
            'sender_user' => (int) $sender_user,
            'receiver_user' => (int) $receiver_user,
            'group_id' => $group_id ? (int) $group_id : null,
            'notify_type' => (int) $type,
            'notify_id' => $type_id ? (int) $type_id : null,
            'message' => $message,
            'created_at' => wp_date('Y-m-d H:i:s')
        );
        $table = $wpdb->prefix . self::$table;

        $already_created = self::get(self::$table, array(
            'sender_user' => (int) $sender_user,
            'receiver_user' => (int) $receiver_user,
            'notify_type' => (int) $type,
            'notify_id' => $type_id ? (int) $type_id : null,
        ), true);
        if (empty($already_created)) {
            $wpdb->insert($table, $array);
        }
    }

    public static function create($sender_user, $receiver_user, $message = null, $type = 0, $type_id = null, $is_group = false, $is_cron = false)
    {
        if ($sender_user == $receiver_user) {
            return true;
        }
        global $wpdb;
        $table = $wpdb->prefix . self::$table;

        $sender_user = new WP_User((int)$sender_user);
        if ($type == 3) {
            $args = array(
                array(
                    'sender_user' => $sender_user->ID,
                    'receiver_user' => $receiver_user,
                    'is_group' => $is_group,
                    'message' => $message,
                    'type' => $type,
                    'type_id' => $type_id,
                )
            );
            wp_schedule_single_event(time(),  'send_message_notification_by_five_minutes_cron', $args);
        } else {
            $receiver_user = new WP_User((int)$receiver_user);
            self::create_entry($sender_user->ID, $receiver_user->ID, $type, $type_id, $message);
            if (!in_array($type, [6, 7, 8, 10])) {
                self::email_notify($sender_user, $receiver_user, $message, false, null, $type, $type_id, $is_cron);
                self::mobile_notify($sender_user, $receiver_user, $message, false, null, $type, $type_id, $is_cron);
            }
        }
        return true;
    }

    public static function email_notify($sender_user, $receiver_user, $message, $is_group = false, $group = null, $type = 0, $type_id = null, $is_cron = false)
    {
        $send = false;
        $site = get_bloginfo('name');
        $sender_title = ucfirst($sender_user->first_name) . ' ' . $sender_user->last_name;

        $html = "";
        $subject = "";

        if (($type == 1 || $type == 2) && $type_id && (get_user_meta($receiver_user->ID, '_user_notify_when_have_new_connection_request_email', true) == true)) {
            $send = true;
            $sub = $type == 1 ? 'connection' : '';
            $subject = "You have a new request on Property Mates";
            ob_start();
            $link = home_url(CO_OWNER_MESSAGE_PAGE . ($type == 2 ? "?is_pool=false&with={$sender_user->ID}" : "?is_pool=false&request={$type_id}&is_received=true"));
            include(CO_OWNER_THEME_DIR . '/parts/mails/connection-request.php');
            $html = ob_get_clean();
        } elseif ($type == 9 && $type_id && (get_user_meta($receiver_user->ID, '_user_notify_when_have_new_connection_request_mobile', true) == true)) {
            $send = true;
            $subject = "Property Mates – request accepted";
            ob_start();
            $link = home_url(CO_OWNER_MESSAGE_PAGE . "?is_pool=false&with={$sender_user->ID}");
            include(CO_OWNER_THEME_DIR . '/parts/mails/connection-request.php');
            $html = ob_get_clean();
        } elseif ($type == 3 && (get_user_meta($receiver_user->ID, '_user_notify_when_have_new_message_email', true) == true)) {

            $send = true;
            $link = home_url(CO_OWNER_MESSAGE_PAGE . "?is_pool=" . ($is_group ? 'true' : 'false') . "&with=" . ($is_group ? $group->id : $sender_user->ID));
            $subject = "Property Mates: you have a new message";
            ob_start();
            include(CO_OWNER_THEME_DIR . '/parts/mails/message.php');
            $html = ob_get_clean();
        } elseif ($type == 4 && $type_id && (get_user_meta($receiver_user->ID, '_user_notify_when_have_new_matching_listing_email', true) == true)) {

            $send = true;
            $property = get_property_detail_by_id($type_id);
            $link = home_url(CO_OWNER_PROPERTY_DETAILS_PAGE . "?id={$property->ID}");
            $subject = "New matching property(s) on Property Mates";
            ob_start();
            include(CO_OWNER_THEME_DIR . '/parts/mails/same-property.php');
            $html = ob_get_clean();
        } elseif ($type == 5 && $type_id && (get_user_meta($receiver_user->ID, '_user_notify_when_have_new_matching_listing_mobile', true) == true)) {
            $send = true;
            $property = get_property_detail_by_id($type_id);
            $subject = "New matching profile(s) on Property Mates";
            ob_start();
            include(CO_OWNER_THEME_DIR . '/parts/mails/same-person.php');
            $html = ob_get_clean();
        }

        if ($send) {
            wp_mail($receiver_user->user_email, $subject, $html, array('Content-Type: text/html; charset=UTF-8'));
        }
    }

    public static function mobile_notify($sender_user, $receiver_user, $message, $is_group = false, $group = null, $type = 0, $type_id = null, $is_cron = false)
    {
        $send = false;
        $site = get_bloginfo('name');
        $link = home_url(CO_OWNER_MESSAGE_PAGE . "?is_pool=" . ($is_group ? 'true' : 'false') . "&with=" . ($is_group ? $group->id : $sender_user->ID));
        $sender_title = ($is_group and !empty($group)) ? $group->name : ucfirst($sender_user->first_name) . ' ' . $sender_user->last_name;
        $receiver_name = ucfirst($receiver_user->first_name);
        $sender_name = ucfirst($sender_user->first_name) . ' ' . $sender_user->last_name;

        $txt = "";
        $to = get_user_meta($receiver_user->ID, '_mobile', true);

        if (($type == 1 || $type == 2) && $type_id && (get_user_meta($receiver_user->ID, '_user_notify_when_have_new_connection_request_mobile', true) == true)) {
            $send = true;
            $link = home_url(CO_OWNER_PERSON_DETAILS_PAGE . "?id={$sender_user->ID}");
            $sub = $type == 1 ? 'connection' : '';
            $receiver_name = ucfirst($receiver_user->first_name);
            $txt = "Hi {$receiver_name} \n\n {$sender_name}. Sent you a connection request.\n\nBest Regards,\n{$site}";
        } elseif ($type == 9 && $type_id && (get_user_meta($receiver_user->ID, '_user_notify_when_have_new_connection_request_mobile', true) == true)) {
            $send = true;
            $receiver_name = ucfirst($receiver_user->first_name);
            $txt = "Hi {$receiver_name} \n\n {$sender_title}. has accepted your request. You can now go to your Messages and start a conversation! \n\nBest Regards,\n{$site}";
        } elseif ($type == 3 && (get_user_meta($receiver_user->ID, '_user_notify_when_have_new_message_mobile', true) == true)) {
            $receiver_name = ucfirst($receiver_user->first_name);
            $send = true;
            $txt = "Hi {$receiver_name} \n\nYou have a new message from {$sender_title} \n\n{$sender_name} :- \n{$message}\n\nBest Regards,,\n{$site}";
        } elseif ($type == 4 && $type_id && (get_user_meta($receiver_user->ID, '_user_notify_when_have_new_matching_listing_mobile', true) == true)) {

            $send = true;
            $property = get_property_detail_by_id($type_id);
            $link = home_url(CO_OWNER_PROPERTY_DETAILS_PAGE . "?id={$property->ID}");
            $receiver_name = ucfirst($receiver_user->first_name);
            $txt = "Hi {$receiver_name} \n\nYou have a new matching property\n\nTitle:-{$property->post_title} \nAddress:- {$property->address}\nMarket Price:- " . (get_updated_property_price($property->ID)) . "\n\n{$link}\n\nCheers,\n{$site}";
        } elseif ($type == 5 && $type_id && (get_user_meta($receiver_user->ID, '_user_notify_when_have_new_matching_listing_mobile', true) == true)) {
            $property = get_post($type_id);
            $address = get_property_full_address($type_id);
            $mobile = get_user_meta($sender_user->ID, '_mobile', true);
            $budget = get_user_budget($sender_user->ID);
            $budget_range = get_user_meta($sender_user->ID, '_user_budget_range', true);
            $send = true;
            $link = home_url(CO_OWNER_PERSON_DETAILS_PAGE . "?id={$sender_user->ID}");
            $receiver_name = ucfirst($receiver_user->first_name);
            $preferred_location = implode(', ', get_user_meta($sender_user->ID, '_user_preferred_location', true));
            $txt = "Hi {$receiver_name}\n\nYou have a new matching profile for your listing.\n\nEmail:- {$sender_user->user_email}\n\nMobile:- {$mobile}\n\nBudget range:- " . (price_range_show(($budget_range))) . "\n\nPreferred Locations:-{$preferred_location}\n\n{$link}\n\nCheers,\n{$site}";
        }


        if ($send = true) {
            CoOwner_Twilio::sand_message($to, $txt);
        }
    }

    public static function same_property($property_id, $is_cron = false)
    {
        $property = get_property_detail_by_id($property_id);
        if ($property) {
            $meta_filter = array();
            $meta_filter[] = array(
                'key' => '_user_status',
                'value' => 1,
                'compare' => '=',
                'type' => 'boolean',
            );
            $meta_filter[] = array(
                'key' => '_user_preferred_location',
                'value' => $property->state,
                'compare' => 'LIKE',
            );

            $meta_filter[] = array(
                'relation' => 'OR',
                array(
                    'key' => '_user_property_category',
                    'value' => serialize($property->property_category),
                    'compare' => 'LIKE',
                )
            );

            if ($property->property_category != 'commercial') {
                $meta_filter[] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_user_bedroom',
                        'value' => $property->bedroom,
                        'compare' => '<=',
                        'type' => 'NUMERIC',
                    )
                );
                $meta_filter[] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_user_bathroom',
                        'value' => $property->bathroom,
                        'compare' => '<=',
                        'type' => 'NUMERIC',
                    )
                );
                $meta_filter[] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_user_parking',
                        'value' => $property->parking,
                        'compare' => '<=',
                        'type' => 'NUMERIC',
                    )
                );
            }
            $args = array(
                "meta_query" => $meta_filter,
                'fields' => array(
                    'ID',
                    'user_login',
                    'user_nicename',
                    'user_email',
                    'user_status',
                    'display_name',
                )
            );
            $users = get_users($args);
            if ($users) {
                foreach ($users as $user) {
                    if ($property->post_author != $user->ID) {
                        $matching_user_id = $user->ID;
                        $min_budget = (int)get_user_meta($matching_user_id, '_min_budget', true);
                        $max_budget = (int)get_user_meta($matching_user_id, '_max_budget', true);
                        $user_property_features = get_user_meta($matching_user_id, '_user_property_features', true);
                        $user_property_type = get_user_meta($matching_user_id, '_user_property_type', true);
                        if (!empty($min_budget) && !empty($max_budget)) {
                            if (($min_budget <= $property->property_market_price) && ($property->property_market_price <= $max_budget) || ($min_budget <= $property->calculated) && ($property->calculated <= $max_budget)) {
                                $exist = true;
                                if ($user_property_features) {
                                    $property_features = (array)$property->property_features;
                                    if ($property->manually_features) {
                                        $property_features = array_merge((array)$property->property_features, (array)$property->manually_features);
                                    }
                                    foreach ($user_property_features as $features) {
                                        if (!in_array($features, $property_features))
                                            $exist = false;
                                    }
                                }
                                if ($user_property_type) {
                                    if (!in_array($property->property_type, $user_property_type))
                                        $exist = false;
                                }

                                if ($exist) {
                                    CoOwner_Notifications::create($property->post_author, $user->ID, 'You have a new matching property.', 4, $property->ID, false, $is_cron);
                                }
                            }
                        } elseif ($min_budget) {
                            if (($property->property_market_price >= $min_budget) || ($property->calculated >= $min_budget)) {
                                $exist = true;
                                if ($user_property_features) {
                                    $property_features = (array)$property->property_features;
                                    if ($property->manually_features) {
                                        $property_features = array_merge((array)$property->property_features, (array)$property->manually_features);
                                    }
                                    foreach ($user_property_features as $features) {
                                        if (!in_array($features, $property_features))
                                            $exist = false;
                                    }
                                }
                                if ($user_property_type) {
                                    if (!in_array($property->property_type, $user_property_type))
                                        $exist = false;
                                }

                                if ($exist) {
                                    CoOwner_Notifications::create($property->post_author, $user->ID, 'You have a new matching property.', 4, $property->ID, false, $is_cron);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function same_person($person_id, $is_cron = false)
    {
        $person = get_person_detail_by_id($person_id);
        if (empty($person)) {
            return;
        }

        $meta_filter = array();
        // $person->enable_pool = 0;
        if ($person->price_range)
            $range = array_map('intval', explode(',', $person->price_range));

        // $meta_filter[] = array(
        //     'key' => '_pl_enable_pool',
        //     'value' => 1,
        //     'type' => 'BOOLEAN',
        //     'compare' => $person->enable_pool ? '=' : '!='
        // );

        $meta_filter[] = array(
            'relation' => 'OR',
            array(
                'key' => '_pl_property_category',
                'value' => $person->property_category,
                'compare' => 'IN',
            )
        );
        $meta_filter[] = array(
            'relation' => 'OR',
            array(
                'key' => '_pl_property_type',
                'value' => $person->property_type,
                'compare' => 'IN',
            )
        );

        if ($range) {
            if ($range[1] > 0) {
                $meta_filter[] = array(
                    'relation' => 'AND',
                    array(
                        'key' => '_pl_price',
                        'value' => $range,
                        'type'    => 'numeric',
                        'compare' => 'BETWEEN',
                    )
                );
            } else {
                $meta_filter[] = array(
                    'relation' => 'AND',
                    array(
                        'key' => '_pl_price',
                        'value' => $range[0],
                        'compare' => '>=',
                    )
                );
            }
        }

        $args = array(
            "post_type"         => "property",
            "post_status"       => "publish",
            "meta_query"        => $meta_filter,
        );

        $posts = get_posts($args);

        foreach ($posts as $property) {
            $exists = false;
            if (count($person->preferred_location) > 0) {
                $pl_address = get_post_meta($property->ID, '_pl_address', true);
                $pl_postcode = get_post_meta($property->ID, '_pl_postcode', true);
                $pl_state = get_post_meta($property->ID, '_pl_state', true);
                if (in_array($pl_address, $person->preferred_location))
                    $exists = true;
                elseif (in_array($pl_postcode, $person->preferred_location))
                    $exists = true;
                elseif (in_array($pl_state, $person->preferred_location))
                    $exists = true;
            }
            if ($exists) {
                if ($person_id != $property->post_author) {
                    CoOwner_Notifications::create($person_id, $property->post_author, 'You have a new matching person.', 5, $property->ID, false, $is_cron);
                }
            }
        }
    }

    public static function send_new_property_notification_cron($property_id)
    {
        $args = array('id' => $property_id);
        update_option('my_cron_cstm_1', 1);
        wp_schedule_single_event(time(),  'send_new_property_notification_cron', [$args]);
    }

    public static function send_new_person_notification_cron($person_id)
    {
        $args = array('id' => $person_id);
        wp_schedule_single_event(time(),  'send_new_person_notification_cron', [$args]);
    }

    public static function send_all_same_person($type)
    {
        $users = get_users(array(
            'orderby'                 => 'ID',
            'order'                   => 'DESC',
        ));
        foreach ($users as $user) {
            $role = co_owner_get_user_field('s2member_access_role', $user->ID);
            if ($role == 's2member_level0') {
                $is_expire = user_plan_is_expire($user);
                if ($is_expire && empty(get_user_meta($user->ID, '_user_trial_expire_notified'))) {
                    $site = get_bloginfo('name');
                    $subject = "Thank you! Your account has been updated.";
                    $username = $user->first_name . ' ' . $user->last_name;
                    $html = "Hiii {$username}!<br><br>Your account now has trial plan is expire please update your plan..<br><br>If you have any trouble,please feel free to contact us.<br><br>Best Regards,<br>{$site}";
                    //wp_mail($user->user_email, $subject, $html, array('Content-Type: text/html; charset=UTF-8'));
                }
            }


            if (
                !in_array('administrator', $user->roles) &&
                (
                    ($type == 'daily' && get_user_meta($user->ID, '_user_notify_when_have_new_notify_me_daily', true) == true) ||
                    ($type == 'weekly' && get_user_meta($user->ID, '_user_notify_when_have_new_notify_me_weekly', true) == true) ||
                    ($type == 'monthly' && get_user_meta($user->ID, '_user_notify_when_have_new_notify_me_monthly', true) == true))
            ) {
                CoOwner_Notifications::same_person($user->ID, true);
            }
        }
    }

    public static function send_all_same_property($type)
    {
        $properties = get_posts(array(
            'orderby'       => 'ID',
            'order'         => 'DESC',
            'post_status'   => 'publish',
            'post_type'     => 'property',
        ));
        foreach ($properties as $property) {
            if (
                ($type == 'daily' && get_user_meta($property->ID, '_user_notify_when_have_new_notify_me_daily', true) == true) ||
                ($type == 'weekly' && get_user_meta($property->ID, '_user_notify_when_have_new_notify_me_weekly', true) == true) ||
                ($type == 'monthly' && get_user_meta($property->ID, '_user_notify_when_have_new_notify_me_monthly', true) == true)
            ) {
                CoOwner_Notifications::same_property($property->ID, true);
            }
        }
    }

    public static function get_my_notifications($user_id, $page = 1)
    {
        global $wpdb;
        $table = $wpdb->prefix . self::$table;
        $sql = "SELECT *,(CASE WHEN read_at is null THEN 1 WHEN read_at is not null THEN 2 END) as custom_read_at  FROM {$table} WHERE receiver_user = {$user_id} ORDER BY custom_read_at ASC, id DESC";

        $limit = 20;
        $offset = ($page - 1) * $limit;
        $sql .= " LIMIT {$limit} OFFSET {$offset}";

        return $wpdb->get_results($sql);
    }

    public static function mark_as_read_my_notifications($notify_type = null)
    {
        $user_id = get_current_user_id();
        global $wpdb;
        $table = $wpdb->prefix . self::$table;
        $date = wp_date('Y-m-d H:i:s');
        $where = $notify_type ? "AND notify_type {$notify_type}" : null;
        $sql = "UPDATE {$table} SET read_at = '{$date}' WHERE receiver_user = {$user_id} AND read_at IS NULL {$where}";
        return $wpdb->query($sql);
    }

    public static function count($array)
    {
        global $wpdb;
        $table = $wpdb->prefix . self::$table;
        $sql = self::make_query_from_array($table, $array);
        $result = $wpdb->get_row($sql);
        return (int) $result->count;
    }
}


add_filter('cron_schedules', 'example_add_cron_interval');
function example_add_cron_interval($schedules)
{
    $schedules['monthly'] = array(
        'display' => __('Once monthly'),
        'interval' => 2592000,
    );
    $schedules['every_five_minutes'] = array(
        'interval'  => 60,
        'display'   => __('Every 5 Minutes')
    );
    return $schedules;
}

add_action('init', function () {
    $args = array(false);
    if (!wp_next_scheduled('co_owner_daily_cron', $args)) {
        wp_schedule_event(time(), 'daily', 'co_owner_daily_cron', $args);
    }
    add_action('co_owner_daily_cron', function () {
        CoOwner_Notifications::send_all_same_person('daily');
        CoOwner_Notifications::send_all_same_property('daily');
    });


    if (!wp_next_scheduled('co_owner_weekly_cron', $args)) {
        wp_schedule_event(time(), 'weekly', 'co_owner_weekly_cron', $args);
    }
    add_action('co_owner_weekly_cron', function () {
        CoOwner_Notifications::send_all_same_person('weekly');
        CoOwner_Notifications::send_all_same_property('weekly');
    });

    if (!wp_next_scheduled('co_owner_monthly_cron', $args)) {
        wp_schedule_event(time(), 'monthly', 'co_owner_monthly_cron', $args);
    }
    add_action('co_owner_monthly_cron', function () {
        CoOwner_Notifications::send_all_same_person('monthly');
        CoOwner_Notifications::send_all_same_property('monthly');
    });


    add_action('send_message_notification_by_five_minutes_cron', function ($args) {

        $sender_user = isset($args['sender_user']) ? $args['sender_user'] : null;
        $receiver_user = isset($args['receiver_user']) ? $args['receiver_user'] : null;
        $is_group = isset($args['is_group']) ? $args['is_group'] : false;
        $message = isset($args['message']) ? $args['message'] : null;
        $type = isset($args['type']) ? $args['type'] : null;
        $type_id = isset($args['type_id']) ? $args['type_id'] : null;

        if ($sender_user && $receiver_user && $message && $type && $type_id) {
            $sender_user = new WP_User($sender_user);
            if ($is_group) {
                $group = CoOwner_Groups::find(array('id' => $receiver_user));
                if (!$group) {
                    return;
                }
                $members = get_property_total_members($group->property_id);
                foreach ($members as $member) {
                    if ($sender_user->ID != $member->id) {
                        $receiver_user = new WP_User($member->id);
                        CoOwner_Notifications::create_entry($sender_user->ID, $receiver_user->ID, $type, $type_id, $message, $group->id);
                        CoOwner_Notifications::email_notify($sender_user, $receiver_user, $message, true, $group, $type);
                        CoOwner_Notifications::mobile_notify($sender_user, $receiver_user, $message, true, $group, $type);
                    }
                }
            } else {
                $receiver_user = new WP_User((int)$receiver_user);
                CoOwner_Notifications::create_entry($sender_user->ID, $receiver_user->ID, $type, $type_id, $message);
                if (!in_array($type, [6, 7, 8])) {
                    CoOwner_Notifications::email_notify($sender_user, $receiver_user, $message, false, null, $type, $type_id);
                    CoOwner_Notifications::mobile_notify($sender_user, $receiver_user, $message, false, null, $type, $type_id);
                }
            }
        }
    });

    add_action('send_new_property_notification_cron', function ($args) {
        update_option('my_cron_cstm_2', 2);
        if (isset($args['id'])) {
            CoOwner_Notifications::same_property($args['id']);
        }
    });
    add_action('send_new_person_notification_cron', function ($args) {
        if (isset($args['id'])) {
            CoOwner_Notifications::same_person($args['id']);
        }
    });
});



function send_user_mail_for_shield_approved($user_id, $status = 1)
{
    $user = get_user_by('ID', $user_id);
    $reason = $status == 1 ? null : get_user_meta($user_id, '_document_shield_reject_reason', true);
    $site = get_bloginfo('name');
    $subject = "Document " . ($status == 1 ? 'Approved' : 'rejected') . " on {$site}!";

    ob_start();
    include CO_OWNER_THEME_DIR . '/parts/mails/shield-approved.php';
    $html = ob_get_clean();

    wp_mail($user->user_email, $subject, $html, array('Content-Type: text/html; charset=UTF-8'));
}
function send_user_message_for_shield_approved($user_id, $status = 1)
{
    $to = get_user_meta($user_id, '_mobile', true);
    if (!empty($to)) {
        $user = get_user_by('ID', $user_id);
        $full_name = $user->first_name;
        $site = get_bloginfo('name');
        $subject = "Document " . ($status == 1 ? 'Approved' : 'Rejected') . " on {$site}!";
        if ($status == 1) {
            $txt = "Congratulations!\n\nHi {$full_name}!\n\nYour document has been approved and you are listed as a verified and trusted user. Your profile will receive a golden shield.\n\nBest Regards,\n{$site}";
        } else {
            $reason = get_user_meta($user_id, '_document_shield_reject_reason', true);
            $txt = "Oops…\n\nHi {$full_name}\n\nThe document you submitted has been rejected. Please refer to the below details and try resubmitting it.\n\nReason:-{$reason}\n\nBest Regards,\n{$site}";
        }
        CoOwner_Twilio::sand_message($to, $txt);
    }
}
