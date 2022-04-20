<?php

function get_subscription_information($type = null)
{
    $type = strtolower($type);
    if ($type == 'trial' || $type == 's2member_level0') {
        $info = new stdClass();
        $info->name = 'Trial';
        $info->slug = 'trial';
        $info->create_property_upto = 3;
        $info->join_upto_pools = 2;
        $info->amount = 0;
        $info->level = 0;
        $info->duration = '4 Week';
        return $info;
    } elseif ($type == 'standard' || $type == 's2member_level1') {
        $info = new stdClass();
        $info->name = 'Standard';
        $info->slug = 'standard';
        $info->create_property_upto = 3;
        $info->join_upto_pools = 2;
        $info->amount = 14.95;
        $info->level = 1;
        $info->duration = 'month';
        return $info;
    } elseif ($type == 'professional' || $type == 's2member_level2') {
        $info = new stdClass();
        $info->name = 'Business/Agent';
        $info->slug = 'professional';
        $info->create_property_upto = 50;
        $info->join_upto_pools = 20;
        $info->amount = 199.95;
        $info->level = 2;
        $info->duration = 'month';
        return $info;
    }
    return null;
}

function register_user_subscription_information($user_id, $subscription_type)
{
    $user_id = is_object($user_id) ? $user_id->ID : $user_id;
    $role = co_owner_get_user_field('s2member_access_role', $user_id);

    if ($subscription_type == 'trial' && $role != 'subscriber') {
        return false;
    }

    if ($subscription_type == 'trial' && $role == 'subscriber') {
        $expire = date('Y-m-d H:i:s', strtotime('+4 week'));
        $id = 'FREE-' . uniqid();
        $capabilities = array(
            's2member_level0' => 0,
            'bbp_participant' => 1
        );
        update_user_meta($user_id, '_user_subscription_valid_at', $expire);
        update_user_option($user_id, 's2member_subscr_cid', $id);
        update_user_option($user_id, 's2member_subscr_id', $id);
        update_user_option($user_id, 's2member_subscr_or_wp_id', $id);
        update_user_option($user_id, 's2member_subscr_gateway', 'stripe');
        update_user_option($user_id, 'capabilities', $capabilities);
        update_user_meta($user_id, 'user_touch_subscription', $id);

        $site = get_bloginfo('name');
        $user = get_user_by('ID', $user_id);
        $username = ucfirst($user->first_name);
        $subject = "Welcome {$username}! Here’s how to get started with Property Mates.";
        $expire_at = date("d M Y", strtotime($expire));

        $html = "Hi {$username},<br><br>Thanks for signing up to Property Mates! My name is Paras. Think of me as your personal assistant, here to guide you through your trial.<br><br>Hopefully you’ve had a chance to log in and look around. If you haven’t, don’t worry. Check out the FAQs for an overview of the basics, including how to get started.<br><br>Over the next two weeks, I may send you one or two emails to check in and see how things are going. If you have any questions at all, you can reply directly to this message or email Hello@PropertyMates.io and I’ll be happy to assist you.<br><br>Best Regards,<br>Paras";
        wp_mail($user->user_email, $subject, $html, array('Content-Type: text/html; charset=UTF-8'));
    }
    update_user_meta($user_id, '_user_subscription_type', $subscription_type);
    return true;
}

function get_user_subscription_level($user = null)
{
    $user_id = is_object($user) ? $user->ID : ($user ? $user : get_current_user_id());
    $current_subscription = co_owner_get_user_field('s2member_access_role', $user_id);
    return $current_subscription;
}

function user_plan_is_expire($user)
{
    $user_id = is_object($user) ? $user->ID : $user;
    $user_subscription = get_user_subscription_level($user);

    //    $subscription = s2member_pro_stripe_customer_subscription();
    //    CoOwner::print_a($subscription->status);
    //    CoOwner::print_a($subscription->status == 'canceled');
    //    CoOwner::print_a($subscription->status == 'active');

    if ($user_subscription == 's2member_level0') {
        $expire_at = get_user_meta($user_id, '_user_subscription_valid_at', true);
        if ($expire_at) {
            return date('Y-m-d') >= date('Y-m-d', strtotime($expire_at));
        }
    }
    return false;
}

function check_create_upto_listings_by_plan($user)
{
    $user_id = (is_object($user) ? $user->ID : $user);
    if ($user_id <= 0) {
        return false;
    }

    $property_count = get_user_post_count($user_id);
    $role = co_owner_get_user_field('s2member_access_role', $user_id);
    $plan = get_subscription_information($role);

    if ($plan->slug == 'trial' && user_plan_is_expire($user)) {
        return false;
    }

    if ($plan && $role && $property_count > 0 && $plan->create_property_upto <= $property_count) {
        return false;
    }
    return true;
}

function check_join_upto_pools_by_plan($user)
{
    $user_id = (is_object($user) ? $user->ID : $user);
    if ($user_id <= 0) {
        return false;
    }

    $filter = array(
        'receiver_user' => $user_id,
        'is_group' => 1,
    );
    $count = CoOwner_Connections::count($filter);
    $role = co_owner_get_user_field('s2member_access_role', $user_id);
    $plan = get_subscription_information($role);

    if ($plan->slug == 'trial' && user_plan_is_expire($user)) {
        return false;
    }

    if ($plan && $role && $count > 0 && $plan->join_upto_pools <= $count) {
        return false;
    }
    return true;
}

function co_owner_get_user_field($key, $user_id = FALSE)
{
    if (function_exists('get_user_field')) {
        return get_user_field($key, $user_id);
    }
    return null;
}


add_filter('ws_plugin__s2member_user_access_label', function ($level) {
    if ($level == 'Bronze Member') {
        return 'Standard';
    } elseif ($level == 'Silver Member') {
        return 'Business/Agent';
    }
    return $level;
}, 10, 1);

add_action('ws_plugin__s2member_after_activation', function ($array = array()) {
    $roles = get_option('wp_user_roles');
    if (!in_array('s2member_level0', array_keys($roles))) {
        $trial_role = array(
            'name' => 's2Member Level Trial',
            'capabilities' => array(
                'read' => 1,
                'level_0' => 1,
                'access_s2member_level0' => 1,
                'access_s2member_level1' => 1,
                'access_s2member_level2' => 1,
                'access_s2member_level3' => 1,
                'spectate' => 1,
                'participate' => 1,
                'read_private_forums' => 1,
                'publish_topics' => 1,
                'edit_topics' => 1,
                'publish_replies' => 1,
                'edit_replies' => 1,
                'assign_topic_tags' => 1,
            )
        );
        $roles['s2member_level0'] = $trial_role;
        update_option('wp_user_roles', $roles);
    }
}, 10, 1);


function co_owner_ws_plugin__s2member_pro_after_stripe_notify_event_switch($vars)
{
    extract($vars);
    try {
        if ($event->type == 'customer.subscription.created') {
            if ($user = check_value_in_usermeta('wp_s2member_subscr_baid', $event->data->object->id)) {
                update_user_meta($user->user_id, 'user_touch_subscription', $event->data->object->id);
            }
        } elseif ($event->type == 'customer.subscription.deleted') {
            if ($user = check_value_in_usermeta('wp_s2member_subscr_baid', $event->data->object->id)) {
                delete_user_meta($user->user_id, 'wp_s2member_last_payment_time');
                delete_user_meta($user->user_id, 'wp_s2member_first_payment_txn_id');
                delete_user_meta($user->user_id, 'wp_s2member_ipn_signup_vars');
                delete_user_meta($user->user_id, 'wp_s2member_custom');
                delete_user_meta($user->user_id, 'wp_s2member_paid_registration_times');
                delete_user_meta($user->user_id, 'wp_s2member_subscr_gateway');
                delete_user_meta($user->user_id, 'wp_s2member_subscr_or_wp_id');
                delete_user_meta($user->user_id, 'wp_s2member_subscr_id');
                delete_user_meta($user->user_id, 'wp_s2member_subscr_cid');
                delete_user_meta($user->user_id, 'wp_s2member_subscr_baid');
                delete_user_meta($user->user_id, 'wp_s2member_registration_ip');
                delete_user_meta($user->user_id, 'wp_s2member_login_counter');
                delete_user_meta($user->user_id, 'wp_s2member_last_login_time');
                delete_user_meta($user->user_id, 'wp_s2member_access_cap_times');
                $capabilities = get_user_meta($user->user_id, 'wp_capabilities', true);
                unset($capabilities['s2member_level2']);
                unset($capabilities['s2member_level1']);
                unset($capabilities['s2member_level0']);
                $capabilities['subscriber'] = 1;
                update_user_meta($user->user_id, 'wp_capabilities', $capabilities);
            }
        }
    } catch (\Exception $exception) {
    }
}
add_action('ws_plugin__s2member_pro_after_stripe_notify_event_switch', 'co_owner_ws_plugin__s2member_pro_after_stripe_notify_event_switch');
