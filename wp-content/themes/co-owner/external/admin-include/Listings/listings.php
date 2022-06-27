<?php

function sports_bench_team_admin_menu()
{
    add_menu_page(
        'Connection Requests',
        'Connection Requests',
        'edit_posts',
        'connection_requests',
        'co_owner_connections_requests',
        'dashicons-groups',
        6
    );

    add_menu_page(
        'User shield Requests',
        'User shield Requests',
        'edit_posts',
        'user_shield_requests',
        'co_owner_user_shield_requests',
        'dashicons-shield',
        6
    );

    add_menu_page(
        'Chat Abuse',
        'Chat Abuse',
        'edit_posts',
        'user_chat_abuse',
        'co_owner_user_reports_messages',
        'dashicons-twitch',
        6
    );
}

add_action('admin_menu', 'sports_bench_team_admin_menu',10);


function co_owner_connections_requests() {
    ob_start();
    require_once('co_owner_connections_requests_table.php');
    echo ob_get_clean();
}

function co_owner_user_shield_requests()
{
    ob_start();
    require_once('co_owner_user_shield_requests_table.php');
    echo ob_get_clean();
}

function co_owner_user_reports_messages()
{
    ob_start();
    require_once('co_owner_user_reports_table.php');
    echo ob_get_clean();
}
