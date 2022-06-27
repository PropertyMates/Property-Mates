<?php

class Co_owner_wp_nav_menu_walker extends Walker_Nav_menu {
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $output .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $li_attributes = '';
        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
        $classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
        $classes[] = 'nav-item';
        $classes[] = 'nav-item-' . $item->ID;

        $is_message_menu = home_url(CO_OWNER_MESSAGE_PAGE.'/') == $item->url;
        $title = $is_message_menu ? 'is-message-menu' : 'is-'.str_replace([" "],'-',strtolower($item->title)).'-menu';
        $classes[] = "action-{$item->post_name} $title";

        if ($depth && $args->walker->has_children) {
            $classes[] = 'dropdown-menu dropdown-menu-end';
        }

        if ( ! empty( $id ) ) {
            if ( $item->ID == $id ) {
                $classes[] = 'current_page_item';
            }
        }

        $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));

        $class_names = ' class="' . esc_attr($class_names) . '"';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $url = $item->url;
        if($is_message_menu){
            $user_id = get_current_user_id();
            $user_status = get_user_status($user_id);
            if($user_status == 1){
                $first_connection = CoOwner_Connections::get_connected_users($user_id,1);
                if(!$first_connection){
                    $first_connection = get_connection_requests(true,false,true);
                    if(!$first_connection){
                        $first_connection = get_connection_requests(false,false,true);
                    }
                }
                $url = home_url(CO_OWNER_MESSAGE_PAGE);
                if($first_connection) {
                    $with_id = $first_connection->sender_user == $user_id ? $first_connection->receiver_user : $first_connection->sender_user;
                    $is_request = $first_connection->status == 0 ? "request={$first_connection->id}" : "with={$with_id}";
                    $url = home_url(CO_OWNER_MESSAGE_PAGE . "?is_pool=false");
                }
            }
        }

        $itemHref = $args->walker->has_children ? '#' : esc_attr($url);
        $attributes .= !empty($item->url) ? ' href="' . $itemHref . '"' : '';

        $attributes .= ($args->walker->has_children) ? ' class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false"' : ' class="nav-link '.($is_message_menu ? 'd-flex' : '').'"';

        $item_output = $args->before;
        $item_output .= ($depth > 0) ? '<a class="dropdown-item"' . $attributes . '>' : '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;

        if($is_message_menu){

            $is_group = isset($_GET['is_pool']) ? filter_var($_GET['is_pool'], FILTER_VALIDATE_BOOLEAN)  : false;
            $chat_with = isset($_GET['with']) ? $_GET['with'] : null;
            if($chat_with){
                CoOwner_Notifications::mark_as_read_my_notifications(" = 3 AND ".($is_group ? 'group_id' : 'sender_user')." = {$chat_with}");
            }

            $count = ($user_status == 1) ? CoOwner_Notifications::count(array(
                'receiver_user' => $user_id,
                'notify_type' => 3,
                'read_at' => null
            )) : 0;
            $item_output .= $count > 0 ? "<span class='message-alert-dot orange-circle'></span>" : "<span class='message-alert-dot'></span>";
        }

        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

function co_owner_login_out_menu_link( $items, $args ) {
    if ($args->theme_location == 'primary') {
        $user = wp_get_current_user();
        if ($user->exists()) {
            $user_status = get_user_status($user->ID);
            $count = ($user_status == 1) ? CoOwner_Notifications::count(array(
                'receiver_user' => $user->ID,
                'read_at' => null
            )) : 0;
            $notification_dot = $count > 0 ? "orange-circle" : '';
            $items.='<li class="nav-item dropdown">
                        <a class="nav-link d-flex" title="New Activity" href="#" id="notification-dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                            Notifications
                            <span class="notification-alert-dot '.$notification_dot.'"></span>
                        </a>
                        <ul class="dropdown-menu noti-custom notification-dropdown" aria-labelledby="notification-dropdown" style="">                          
                        </ul>
                    </li>';

            if($user->first_name){
                $username = ucfirst($user->first_name).' '.substr($user->last_name,0,1);
            } else {
                $username = $user->display_name;
            }
          //  $url = get_avatar_url($user->ID);
				$url = avatar_default();
				if(avatar_exist($user->ID)){
				$url =  get_avatar_url($user->ID); 
				}
			
            $items .= '<li>
                            <a title="Account" class="d-flex nav-link text-nowrap user-forst-name" href="'.home_url('/my-account').'">
                                <img class="user-menu-avatar" src="'.$url.'" alt=""/>
                                '.$username.'
                            </a>
                        </li>';
        } else {
            $items .= '<li title="Sign in your account" class="nav-item"><a class="nav-link" href="'. home_url('login') .'">'.co_owner_get_svg('user-avatar').'Log In</a></li>';
        }
    }
    return $items;
}

add_filter( 'wp_nav_menu_items', 'co_owner_login_out_menu_link', 10, 2 );
