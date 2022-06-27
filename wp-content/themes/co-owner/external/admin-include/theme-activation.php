<?php
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class ThemeActivation{

    public static function active_theme(){
        self::create_favourite_table();
        self::create_group_table();
        self::create_connections_table();
        self::create_conversation_table();
        self::create_conversation_files_table();
        self::create_notification_table();
        self::create_reports_table();
        self::check_and_install_pages();
    }

    public static function create_favourite_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix.CO_OWNER_FAVOURITE_TABLE;
        $users = $wpdb->users;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE `{$table_name}`(
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` BIGINT UNSIGNED NOT NULL,
                `favourite_type` VARCHAR(255) NOT NULL DEFAULT 'post',
                `favourite_id` INT NOT NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(`id`)
            ) {$charset_collate};";
            dbDelta($sql);
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_user_id` FOREIGN KEY (`user_id`) REFERENCES `{$users}`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
        }
    }

    public static function create_group_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix.CO_OWNER_GROUP_TABLE;
        $users = $wpdb->users;
        $posts = $wpdb->posts;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE `{$table_name}`(
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id`  BIGINT UNSIGNED NOT NULL,
                `property_id`  BIGINT UNSIGNED NOT NULL,
                `name` VARCHAR(255) NOT NULL,
                `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 = On, 2 = Complete',
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                PRIMARY KEY(`id`)
            ) {$charset_collate};";
            dbDelta($sql);
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_user_id` FOREIGN KEY (`user_id`) REFERENCES `{$users}`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_property_id` FOREIGN KEY (`property_id`) REFERENCES `$posts`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
        }
    }

    public static function create_connections_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix.CO_OWNER_CONNECTIONS_TABLE;
        $group_table = $wpdb->prefix.CO_OWNER_GROUP_TABLE;
        $users = $wpdb->users;

        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE `{$table_name}`(
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `sender_user` BIGINT UNSIGNED NULL DEFAULT NULL,
                `receiver_user` BIGINT UNSIGNED NULL DEFAULT NULL,
                `group_id` BIGINT UNSIGNED NULL DEFAULT NULL,
                `property_id` BIGINT UNSIGNED NULL DEFAULT NULL,
                `is_group` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = Not A Group,1 = Is Group',
                `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = Pending,1 = Approved, 2 = Rejected, 3 = Block ',
                `comment` TEXT NULL,
                `interested_in` INT NULL,
                `calculated_price` DOUBLE(20,2) NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_by` BIGINT UNSIGNED NULL,                  
                PRIMARY KEY(`id`)
            ) {$charset_collate};";
            dbDelta($sql);
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_sender_user` FOREIGN KEY (`sender_user`) REFERENCES `{$users}`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_receiver_user` FOREIGN KEY (`receiver_user`) REFERENCES `{$users}`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_group_id` FOREIGN KEY (`group_id`) REFERENCES `{$group_table}`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;");
            dbDelta("ALTER TABLE `{$table_name}` CHANGE `updated_at` `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;");
        }
    }

    public static function create_conversation_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix.CO_OWNER_CONVERSATION_TABLE;
        $groups = $wpdb->prefix.CO_OWNER_GROUP_TABLE;
        $users = $wpdb->users;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE `{$table_name}`(
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `sender_user` BIGINT UNSIGNED NULL,
                `receiver_user` BIGINT UNSIGNED NULL,
                `group_id` BIGINT UNSIGNED NULL,
                `is_group` TINYINT NOT NULL DEFAULT '0' COMMENT '0 = Not A Group,1 = Is Group',
                `message` LONGTEXT NOT NULL,
                `is_request` TINYINT NOT NULL DEFAULT '0',
                `interested_in` FLOAT NULL,
                `calculated_price` DOUBLE(20,2) NOT NULL DEFAULT '0',
                `property_id` BIGINT UNSIGNED NULL,                
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,                
                PRIMARY KEY(`id`)
            ) {$charset_collate};";
            dbDelta($sql);
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_sender_user` FOREIGN KEY (`sender_user`) REFERENCES `{$users}`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_receiver_user` FOREIGN KEY (`receiver_user`) REFERENCES `{$users}`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_group_id` FOREIGN KEY (`group_id`) REFERENCES `{$groups}`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;");
        }
    }

    public static function create_conversation_files_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix.CO_OWNER_CONVERSATION_FILES_TABLE;
        $groups = $wpdb->prefix.CO_OWNER_GROUP_TABLE;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE `{$table_name}`(
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` BIGINT UNSIGNED NOT NULL,
                `message_id` BIGINT UNSIGNED NOT NULL,
                `connection_id` BIGINT UNSIGNED NULL,
                `group_id` BIGINT UNSIGNED NULL,
                `file` LONGTEXT NOT NULL,
                `is_link` TINYINT(1) NULL DEFAULT '0',
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(`id`)
            ) {$charset_collate};";
            dbDelta($sql);
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_user_id` FOREIGN KEY (`user_id`) REFERENCES `{$wpdb->users}`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
            dbDelta("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$table_name}_group_id` FOREIGN KEY (`group_id`) REFERENCES `{$groups}`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;");
        }
    }

    public static function create_notification_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix.CO_OWNER_NOTIFICATIONS_TABLE;
        $group = $wpdb->prefix.CO_OWNER_GROUP_TABLE;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE `{$table_name}`(
                `id` INT NOT NULL AUTO_INCREMENT,
                `sender_user` BIGINT UNSIGNED NOT NULL,
                `receiver_user` BIGINT UNSIGNED NOT NULL,
                `group_id` BIGINT UNSIGNED NULL DEFAULT NULL,
                `notify_type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = simple,1 = connection request,2 = message, 3 = matching property,4 = matching person,5 = offers',
                `notify_id` INT NULL,
                `message` LONGTEXT NULL,
                `read_at` TIMESTAMP NULL,
                `created_at` TIMESTAMP NULL,
                PRIMARY KEY(`id`)
            ) {$charset_collate};";
            dbDelta($sql);
            dbDelta("ALTER TABLE `{$table_name}` ADD  CONSTRAINT `{$table_name}_sender_user` FOREIGN KEY (`sender_user`) REFERENCES `{$wpdb->users}`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
            dbDelta("ALTER TABLE `{$table_name}` ADD  CONSTRAINT `{$table_name}_receiver_user` FOREIGN KEY (`receiver_user`) REFERENCES `{$wpdb->users}`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
            dbDelta("ALTER TABLE `{$table_name}` ADD  CONSTRAINT `{$table_name}_group_id` FOREIGN KEY (`group_id`) REFERENCES `{$group}`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;");
        }
    }

    public static function create_reports_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix.CO_OWNER_REPORTS_TABLE;
        $message = $wpdb->prefix.CO_OWNER_CONVERSATION_TABLE;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE `{$table_name}`(
                `id` INT NOT NULL AUTO_INCREMENT,
                `user_id` BIGINT UNSIGNED NOT NULL,
                `message_id` BIGINT UNSIGNED NOT NULL,
                `created_at` TIMESTAMP NULL,
                PRIMARY KEY(`id`)
            ) {$charset_collate};";
            dbDelta($sql);
            dbDelta("ALTER TABLE `{$table_name}` ADD  CONSTRAINT `{$table_name}_user_id` FOREIGN KEY (`user_id`) REFERENCES `{$wpdb->users}`(`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;");
            dbDelta("ALTER TABLE `{$table_name}` ADD  CONSTRAINT `{$table_name}_message_id` FOREIGN KEY (`message_id`) REFERENCES `{$message}`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;");

        }
    }

    public static function check_and_install_pages()
    {
        $pages = new CoOwner_ArrayResponse(get_pages());
        $co_owner_pages = array(
            'login',
            'register',
            'home',
            'about-us',
            'contact-us',
            'property-search',
            'create-a-person-listing',
            'create-a-property-listing',
            'faqs',
            'forum',
            'how-it-works',
            'messages',
            'my-account',
            'my-connections',
            'my-listings',
            'shortlist',
            'notification-settings',
            'notifications',
            'people-list',
            'person-details',
            'property-details',
            'property-list',
            'pool-property-list',
            'forgot-password',
            'reset-password',
            'my-account',
            'my-account-verification',
            'my-listings',
            'my-connections',
            'my-notification-settings',
        );
        $wp_pages = $pages->pluck('post_name');
        foreach (array_diff($co_owner_pages,$wp_pages) as $page){
            if(in_array($page,$co_owner_pages)) {
                $attr = array(
                    'post_title' => ucfirst($page),
                    'post_name' => $page,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_author' => 1,
                    'post_date' => wp_date('Y-m-d H:i:s')
                );
                wp_insert_post($attr);
            }
        }
    }
}


add_action("after_switch_theme", array('ThemeActivation','active_theme'));
