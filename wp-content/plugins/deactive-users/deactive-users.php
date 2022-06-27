<?php
/**
* Plugin Name: Deactive Users
* Plugin URI: https://propertymates.io
* Description: Plugin keep data of deleted users data
* Version: 1.0.0
* Author: Propertymates Team
* Author URI: https://propertymates.io
* License: GPL2
*/


function installDB()
{      
  global $wpdb; 
  $db_table_name = $wpdb->prefix . 'deactive_users';  // table name
  $db_table_meta =  $wpdb->prefix . 'deactive_usersmeta';  // table name
  $charset_collate = $wpdb->get_charset_collate();
  $test_db_version = '1.0.0';
 //Check to see if the table exists already, if not, then create it
if($wpdb->get_var( "show tables like '$db_table_name'" ) != $db_table_name ) 
 {		
	$sql ="
		CREATE TABLE IF NOT EXISTS $db_table_name (
		  `ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
		  `user_login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
		  `user_pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
		  `user_nicename` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
		  `user_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
		  `user_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
		  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `user_activation_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
		  `user_status` int NOT NULL DEFAULT '0',
		  `display_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
		  PRIMARY KEY (`ID`),
		  KEY `user_login_key` (`user_login`),
		  KEY `user_nicename` (`user_nicename`),
		  KEY `user_email` (`user_email`)
		) $charset_collate;";	
		

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
   add_option( 'test_db_version', $test_db_version );
 }
if($wpdb->get_var( "show tables like '$db_table_meta'" ) != $db_table_meta ) 
 {		
	$sql ="
	 CREATE TABLE IF NOT EXISTS $db_table_meta (
	  `umeta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
	  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
	  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
	  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
	  PRIMARY KEY (`umeta_id`),
	  KEY `user_id` (`user_id`),
	  KEY `meta_key` (`meta_key`(191))
	) $charset_collate;";	
		

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
   add_option( 'test_db_version', $test_db_version );
 } 
 
 
} 

register_activation_hook( __FILE__, 'installDB' );
add_action('plugins_loaded', 'load_the_plug_files');
function load_the_plug_files() {
	require_once plugin_dir_path( __FILE__ ) . 'include/functions.php';
	require_once plugin_dir_path( __FILE__ ) . 'include/users_table.php';
	require_once plugin_dir_path( __FILE__ ) . 'include/menu.php';

}
?>