<?php

/**
 * Bootstrap on WordPress functions and definitions
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package 	WordPress
 * @subpackage 	CoOwner
 * @autor 		Tech-Xpert
 */


define('BOOTSTRAP_VERSION', '5.0.0');
define('CO_OWNER_SCRIPT_VERSION', '3.2.8');
define('CO_OWNER_THEME_DIR', get_template_directory() . '/');
define('CO_OWNER_THEME_DIR_URI', get_template_directory_uri() . '/');

define('CO_OWNER_PROPERTY_FORM_PAGE', 'forum');
define('CO_OWNER_PROPERTY_SEARCH_PAGE', 'property-search');
define('CO_OWNER_SHORTLIST_PAGE', 'shortlist');
define('CO_OWNER_MESSAGE_PAGE', 'messages');
define('CO_OWNER_CREATE_A_PROPERTY_PAGE', 'create-a-property-listing');
define('CO_OWNER_CREATE_A_PERSON_PAGE', 'create-a-person-listing');
define('CO_OWNER_PROPERTY_LIST_PAGE', 'property-list');
define('CO_OWNER_POOL_PROPERTY_LIST_PAGE', 'pool-property-list');
define('CO_OWNER_PEOPLE_LIST_PAGE', 'people-list');
define('CO_OWNER_PROPERTY_DETAILS_PAGE', 'property-details');
define('CO_OWNER_PERSON_DETAILS_PAGE', 'person-details');
define('CO_OWNER_MY_ACCOUNT_PAGE', 'my-account');
define('CO_OWNER_MY_LISTINGS_PAGE', 'my-listings');
define('CO_OWNER_MY_CONNECTIONS_PAGE', 'my-connections');
define('CO_OWNER_MY_ACCOUNT_VERIFICATION', 'my-account-verification');
define('CO_OWNER_MY_NOTIFICATION_SETTINGS', 'my-notification-settings');
define('CO_OWNER_FAQS_PAGE', 'faq');
define('CO_OWNER_ABOUT_US_PAGE', 'about-us');
define('CO_OWNER_CONTACT_US_PAGE', 'contact-us');

define('CO_OWNER_PERPAGE', 15);
define('CO_OWNER_CURRENCY_SYMBOL', '$');
define('CO_OWNER_MAXIMUM_INPUT_FILE_SIZE', 5);

define('CO_OWNER_PROPERTY_IMAGE_LIMIT_MB', 100 * 1000000);


define('CO_OWNER_GROUP_TABLE', 'co_owner_groups');
define('CO_OWNER_CONNECTIONS_TABLE', 'co_owner_connections');
define('CO_OWNER_CONVERSATION_TABLE', 'co_owner_conversation');
define('CO_OWNER_CONVERSATION_FILES_TABLE', 'co_owner_conversation_files');
define('CO_OWNER_NOTIFICATIONS_TABLE', 'co_owner_notifications');
define('CO_OWNER_REPORTS_TABLE', 'co_owner_reports');
define('CO_OWNER_FAVOURITE_TABLE', 'co_owner_favourite');



define('CO_OWNER_FEEDBACK_Q_1', 'How satisfied are you with Property Mates?');
define('CO_OWNER_FEEDBACK_Q_2', 'Were you able to find what you were looking for on Property Mates?');
define('CO_OWNER_FEEDBACK_Q_3', 'Do you feel Property Mates is worth the cost?');


define('PROPERTY_MATES_PASSWORD_RESET', 'Property Mates Password Reset');


//- Make Dynamic Home page
//- Create person listing page with filter


define('YOU_ARE_ALREADY_CONNECTED_WITH_THIS_PROPERTY', "You are already connected with this property.");
define('YOU_HAVE_ALREADY_PLACED_A_REQUEST_ON_THIS_PROPERTY', "You have already placed a request on this property.");
define('YOU_HAVE_ALREADY_RECEIVED_A_REQUEST_ON_THIS_PROPERTY', "You have already received a request on this property.");

define('WWW_DOMAIN_COM_AU_PROPERTY_PROFILE', "https://www.domain.com.au/property-profile");


/* ========================================================================================================================

	Add language support to theme

	======================================================================================================================== */
add_action('after_setup_theme', 'co_owner_theme_setup');
function co_owner_theme_setup()
{
    load_theme_textdomain('co_owner', get_template_directory() . '/language');
    add_theme_support('custom-logo');
    \Carbon_Fields\Carbon_Fields::boot();
}


function cookie_logged_in() {
$cookie_name = "is_logged_in";
$cookie_value = "Yes";
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
}
add_action('wp_login', 'cookie_logged_in');

function wpdocs_clear_loggedin_cookie() {
	$cookie_name = "is_logged_in";
    unset($_COOKIE[$cookie_name]);
    setcookie($cookie_name, null, -1, '/'); 
}
add_action( 'wp_logout', 'wpdocs_clear_loggedin_cookie' );

/*
     * _document_shield_status
     *  0 requested
     *  1 accepted
     *  2 rejected
     * */

/* ========================================================================================================================

	Required external files

	======================================================================================================================== */

require_once('vendor/autoload.php');
require_once('external/helpers/co-owner.php');
require_once('external/helpers/svg.php');
require_once('external/helpers/co-owner-nav-walker.php');
require_once('external/helpers/imageCropper.php');
require_once('external/helpers/filters.php');
require_once('external/helpers/ajax.php');
require_once('external/helpers/social-login.php');
require_once('external/helpers/pusher.php');
require_once('external/helpers/CoOwner_Twilio.php');
require_once('external/helpers/subscription.php');

require_once('external/admin-include/theme-activation.php');
require_once('external/admin-include/admin-actions.php');
require_once('external/admin-include/admin-users.php');
require_once('external/admin-include/theme-settings.php');
require_once('external/admin-include/custom-post.php');


if (is_admin()) {
    require_once('external/admin-include/Listings/listings.php');
}

require_once('external/models/model.php');
require_once('external/models/favourite.php');
require_once('external/models/connections.php');
require_once('external/models/conversation.php');
require_once('external/models/groups.php');
require_once('external/models/notifications.php');
require_once('external/models/reports.php');


function pr($obj){
	echo '<pre>';
		print_r($obj);
	echo '</pre>';
}

function co_owner_admin_style()
{
    echo '
        <style>
            .preview.button {
                display: none;
            }
        </style>
        ';
}
add_action('admin_head', 'co_owner_admin_style');

/* ========================================================================================================================

Add html 5 support to wordpress elements

======================================================================================================================== */

add_theme_support('html5', array(
    'comment-list',
    'search-form',
    'comment-form',
    'gallery',
    'caption',
));

/* ========================================================================================================================

	Theme specific settings

	======================================================================================================================== */

add_theme_support('post-thumbnails');

//add_image_size( 'name', width, height, crop true|false );

register_nav_menus(array(
    'primary' => 'Primary Navigation',
    'footer_1' => 'Footer 1',
    'footer_2' => 'Footer 2'
));

/* ========================================================================================================================

	Actions and Filters

	======================================================================================================================== */

add_action('wp_enqueue_scripts', 'co_owner_script_init');

add_filter('body_class', array('CoOwner', 'add_slug_to_body_class'));

/* ========================================================================================================================

	Custom Post Types - include custom post types and taxonomies here e.g.

	e.g. require_once( 'custom-post-types/your-custom-post-type.php' );

	======================================================================================================================== */

function is_userstep_done($user_id){
	
	$usergetSteps =get_user_meta($user_id,'steps_complete',true);
	$personData = get_person_detail_by_id($user_id);
   $args = array (
        'post_type' => 'property',
        'author'        =>  $user_id,
        'posts_per_page'    => -1,
        
    );

    $propertys = get_posts($args);
   	
	$steps = 0;
	if($propertys){
		$steps = $steps+1;
		$stepsAr['step1']='complete';
	}
	if(!empty($personData) && $personData->budget > 0){
	   $steps = $steps+1;	
	   $stepsAr['step2']='complete';
	}
	
	if($steps ==0){
		if($usergetSteps){
			return true;
		}else{
			return false;
		}		
	}else{
		if($stepsAr){
			 update_user_meta($user_id,'steps_complete',$stepsAr);
			}
			return true;
	}	
}

/* ========================================================================================================================

	Scripts

	======================================================================================================================== */
//    CoOwner::print_a($_SERVER['REQUEST_SCHEME']);
//    die;
/**
 * Add scripts via wp_head()
 *
 * @return void
 * @author Keir Whitaker
 */
if (!function_exists('co_owner_script_init')) {
    function co_owner_script_init()
    {

        // Get theme version number (located in style.css)
        $theme = wp_get_theme();
        wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), BOOTSTRAP_VERSION, 'all');
        wp_enqueue_style('select2-css', get_template_directory_uri() . '/js/plugins/select2/css/select2.min.css', array(), CO_OWNER_SCRIPT_VERSION, 'screen');
        wp_enqueue_style('toastr-css', get_template_directory_uri() . '/js/plugins/toastr/toastr.min.css', array(), CO_OWNER_SCRIPT_VERSION, 'screen');
        wp_enqueue_style('owl.carousel-css', get_template_directory_uri() . '/js/plugins/owlcarousel/css/owl.carousel.min.css', array(), CO_OWNER_SCRIPT_VERSION, 'screen');
        wp_enqueue_style('owl.carousel-theme-css', get_template_directory_uri() . '/js/plugins/owlcarousel/css/owl.theme.default.min.css', array(), CO_OWNER_SCRIPT_VERSION, 'screen');
        wp_enqueue_style('flexslider-css', get_template_directory_uri() . '/js/plugins/flex-slider/css/flexslider.css', array(), CO_OWNER_SCRIPT_VERSION, 'screen');
        wp_enqueue_style('sweetalert2-css', get_template_directory_uri() . '/js/plugins/sweetalert2/sweetalert2.min.css', array(), CO_OWNER_SCRIPT_VERSION, 'screen');

        wp_enqueue_style('co-owner', get_template_directory_uri() . '/style.css', array(), CO_OWNER_SCRIPT_VERSION, 'screen');
        wp_enqueue_style('app', get_template_directory_uri() . '/css/app.css', array('co-owner'), CO_OWNER_SCRIPT_VERSION, 'screen');

        $slug = basename(get_permalink());
        $user = wp_get_current_user();
        $current_user = $user ? $user->ID : 0;
        $response = CoOwner_Groups::get_joined_groups($current_user)->pluck('id');

        $open_subscription_modal = isset($_SESSION['open_subscription_modal']) ? $_SESSION['open_subscription_modal'] : false;
        if ($open_subscription_modal) {
            unset($_SESSION['open_subscription_modal']);
        }
        $open_payment_modal = isset($_SESSION['open_payment_modal']) ? $_SESSION['open_payment_modal'] : false;
        if ($open_payment_modal) {
            unset($_SESSION['open_payment_modal']);
        }

        if (isset($_GET['subscription']) && $_GET['subscription'] == 'success') {
            $open_subscription_modal = true;
        }
		/* #change007  */
	   if(is_user_logged_in()) {  
			//$usergetSteps =get_user_meta($user->ID,'steps_complete',true);
			$usergetSteps =is_userstep_done($user->ID);
            $is_subscr_id = co_owner_get_user_field('s2member_subscr_id', $user->ID);	
            if($is_subscr_id){			
				if($usergetSteps){
					 $usergetSteps = 200;
				 }else{
					 $usergetSteps = 400;
				 }	
			}else{
				$usergetSteps =100;  
			}			
		}else{ 
			$usergetSteps =100;         		   
		}
	 
		
		
		
		/* #change007  */
        $dataToBePassed = array(
		    'site_url'               =>site_url(),
            'page'                  => $slug,
			'post_type'				=>get_post_type(),
			'steps_complete'		=> $usergetSteps,
            'currency_symbol'       => CO_OWNER_CURRENCY_SYMBOL,
            'is_front_page'         => is_front_page(),
            'ajax_url'              => admin_url('admin-ajax.php'),
            'user_id'               => $current_user,
            'user_status'           => get_user_status($current_user),
            'ajax_nonce'            => wp_create_nonce('ajax_nonce'),
            'alert'                 => (isset($_GET['alert']) && !empty($_GET['alert']) ? $_GET['alert'] : null),
            'alert_message'         => (isset($_GET['alert']) && isset($_GET['alert_message']) && !empty($_GET['alert']) && !empty($_GET['alert_message']) ? $_GET['alert_message'] : null),
            'svg'                   =>  array(
                'pool' => Svg::get_svg('pool'),
                'verifying' => Svg::get_svg('verifying'),
                'verified' => Svg::get_svg('verified'),
                'trash' => Svg::get_svg('trash'),
            ),
            'joined_groups'         => $response,
            'maximum_file_size'     => CO_OWNER_MAXIMUM_INPUT_FILE_SIZE,
            'query'                 => $_GET,
            'is_admin'              => in_array('administrator', $user ? $user->roles : array()),
            'sessions'              => array(
                'open_subscription_modal' => $open_subscription_modal,
            )
        );

        if (
            get_option('_crb_pusher_cluster') &&
            get_option('_crb_pusher_instance_id')
        ) {
            $cluster = get_option('_crb_pusher_cluster');
            $auth_key = get_option('_crb_pusher_instance_id');
            $dataToBePassed['pusher'] = array(
                'cluster'    =>  $cluster,
                'instance_id' =>  $auth_key
            );
        }

        if (
            is_page(CO_OWNER_MESSAGE_PAGE) &&
            isset($_GET['with']) &&
            isset($_GET['is_pool'])
        ) {
            $dataToBePassed['message'] = array(
                'with'    =>  $_GET['with'],
                'is_pool' =>  $_GET['is_pool']
            );
        }
        wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', array('jquery'), BOOTSTRAP_VERSION, true);
        wp_enqueue_script('block-ui-js', get_template_directory_uri() . '/js/plugins/block-ui/jquery.blockUI.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
        wp_enqueue_script('select2-js', get_template_directory_uri() . '/js/plugins/select2/js/select2.min.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);

        wp_enqueue_script('owl.carousel-js', get_template_directory_uri() . '/js/plugins/owlcarousel/js/owl.carousel.min.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
        wp_enqueue_script('flexslider-js', get_template_directory_uri() . '/js/plugins/flex-slider/js/jquery.flexslider.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
        wp_enqueue_script('sweetalert2-js', get_template_directory_uri() . '/js/plugins/sweetalert2/sweetalert2.all.min.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
        wp_enqueue_script('resumable-js', get_template_directory_uri() . '/js/plugins/resumable/resumable.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
        wp_enqueue_script('pusher-js', 'https://js.pusher.com/7.0/pusher.min.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);

        wp_enqueue_script('toastr-js', get_template_directory_uri() . '/js/plugins/toastr/toastr.min.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
        wp_enqueue_script('jquery-validation', get_template_directory_uri() . '/js/plugins/jquery-validation/jquery.validate.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
        wp_enqueue_script('jquery-validation-additional-methods', get_template_directory_uri() . '/js/plugins/jquery-validation/additional-methods.js', array('jquery', 'jquery-validation'), CO_OWNER_SCRIPT_VERSION, true);

       
            $key = get_option('_crb_google_map_api_key');
            wp_enqueue_script('google', $_SERVER['REQUEST_SCHEME'] . "://maps.google.com/maps/api/js?sensor=false&key={$key}", array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
            wp_enqueue_script('google-marker', $_SERVER['REQUEST_SCHEME'] . '://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
       

        wp_enqueue_script('co-owner', get_template_directory_uri() . '/js/co-owner.js', array('jquery', 'bootstrap'), CO_OWNER_SCRIPT_VERSION, true);
        wp_enqueue_script('site', get_template_directory_uri() . '/js/site.js', array('jquery', 'bootstrap'), CO_OWNER_SCRIPT_VERSION, true);
        wp_localize_script('site', 'php_vars', $dataToBePassed);
    }
}

/* #Changed 0012 */
function post_remove()      //creating functions post_remove for removing menu item
{
    //remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
}

add_action('admin_menu', 'post_remove');



function wd_admin_submenu_rename() {
     global $menu; // Global to get menu array
     global $submenu; // Global to get submenu array
     $menu[5][0] = 'Blog'; // Change name of posts to blog
	 

     $submenu['edit.php'][5][0] = 'All Blog Items'; // Change name of all posts to all blog items
}
add_action( 'admin_menu', 'wd_admin_submenu_rename' );

/* #Changed 0012 end  */

function co_owner_enqueue_admin_scripts($hook)
{
    remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");
    wp_enqueue_style('co-owner-admin-css', get_template_directory_uri() . '/css/admin-custom.css', array(), CO_OWNER_SCRIPT_VERSION, 'screen');
    wp_enqueue_style('admin-select2-css', get_template_directory_uri() . '/js/plugins/select2/css/select2.min.css', array(), CO_OWNER_SCRIPT_VERSION, 'screen');
    wp_enqueue_style("wp-jquery-ui-dialog");
    wp_enqueue_script('admin-block-ui-js', get_template_directory_uri() . '/js/plugins/block-ui/jquery.blockUI.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('admin-jquery-validation', get_template_directory_uri() . '/js/plugins/jquery-validation/jquery.validate.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
    wp_enqueue_script('admin-jquery-validation-additional-methods', get_template_directory_uri() . '/js/plugins/jquery-validation/additional-methods.js', array('jquery', 'jquery-validation'), CO_OWNER_SCRIPT_VERSION, true);

    wp_enqueue_script('admin-select2-js', get_template_directory_uri() . '/js/plugins/select2/js/select2.min.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
    wp_enqueue_script('admin-toastr-js', get_template_directory_uri() . '/js/plugins/toastr/toastr.min.js', array('jquery'), CO_OWNER_SCRIPT_VERSION, true);
    wp_enqueue_script('admin-site-js', get_template_directory_uri() . '/js/admin.js');

    $dataToBePassed = array(
        'ajax_url'              => admin_url('admin-ajax.php'),
        'ajax_nonce'            => wp_create_nonce('ajax_nonce'),
		
    );
    $dataToBePassed['svg'] =  array(
        'verified' => Svg::get_svg('verified'),
    );
    if (isset($_GET['co_owner_toastr'])) {
        $dataToBePassed['alert_toastr'] = $_GET['co_owner_toastr'];
        $dataToBePassed['alert_toastr_type'] = isset($_GET['co_owner_toastr_type']) ? $_GET['co_owner_toastr_type'] : 'success';
    }


    wp_localize_script('admin-site-js', 'php_vars', $dataToBePassed);
}

add_action('admin_enqueue_scripts', 'co_owner_enqueue_admin_scripts', 11, 1);


/* ========================================================================================================================

Security & cleanup wp admin

======================================================================================================================== */

//remove wp version
function theme_remove_version()
{
    return '';
}

add_filter('the_generator', 'theme_remove_version');

//remove default footer text
function remove_footer_admin()
{
    echo "";
}

add_filter('admin_footer_text', 'remove_footer_admin');

//remove wordpress logo from adminbar
function wp_logo_admin_bar_remove()
{
    global $wp_admin_bar;

    /* Remove their stuff */
    $wp_admin_bar->remove_menu('wp-logo');
}

add_action('wp_before_admin_bar_render', 'wp_logo_admin_bar_remove', 0);

// Remove default Dashboard widgets
if (!function_exists('disable_default_dashboard_widgets')) {
    function disable_default_dashboard_widgets()
    {

        //remove_meta_box('dashboard_right_now', 'dashboard', 'core');
        remove_meta_box('dashboard_activity', 'dashboard', 'core');
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
        remove_meta_box('dashboard_plugins', 'dashboard', 'core');

        remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
        remove_meta_box('dashboard_primary', 'dashboard', 'core');
        remove_meta_box('dashboard_secondary', 'dashboard', 'core');
    }
}
add_action('admin_menu', 'disable_default_dashboard_widgets');

remove_action('welcome_panel', 'wp_welcome_panel');

// Disable the emoji's
if (!function_exists('disable_emojis')) {
    function disable_emojis()
    {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        // Remove from TinyMCE
        add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
    }
}
add_action('init', 'disable_emojis');
function co_owner_session_start()
{
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    // if (isset($_REQUEST['post'])) {
    // }
}

add_action('init', 'co_owner_session_start');

// Filter out the tinymce emoji plugin.
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}




/* ========================================================================================================================

	Custom login

	======================================================================================================================== */

// Add custom css
if (!function_exists('my_custom_login')) {
    function my_custom_login()
    {
        wp_enqueue_style('co-owner-admin-css', get_template_directory_uri() . '/css/custom-login-style.css', array(), CO_OWNER_SCRIPT_VERSION, 'screen');
    }
}
add_action('login_head', 'my_custom_login');

// Link the logo to the home of our website
if (!function_exists('my_login_logo_url')) {
    function my_login_logo_url()
    {
        return get_bloginfo('url');
    }
}
add_filter('login_headerurl', 'my_login_logo_url');

// Change the title text
if (!function_exists('my_login_logo_url_title')) {
    function my_login_logo_url_title()
    {
        return get_bloginfo('name');
    }
}
add_filter('login_headertext', 'my_login_logo_url_title');


/* ========================================================================================================================

	Comments

	======================================================================================================================== */

/**
 * Custom callback for outputting comments
 *
 * @return void
 * @author Keir Whitaker
 */
if (!function_exists('bootstrap_comment')) {
    function bootstrap_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
?>
        <?php if ($comment->comment_approved == '1') : ?>
            <li class="row">
                <div class="col-12 col-md-12 usr-details">
                    <?php echo get_avatar($comment); ?>
                    <h4>Posted by <?php comment_author_link() ?></h4>
                    <time><a href="#comment-<?php comment_ID() ?>"><?php comment_date() ?> at <?php comment_time() ?></a></time>
                </div>
                <div class="col-12 col-md-12 user-cmment">
                    
                    <?php comment_text() ?>
                </div>
    <?php endif;
    }
}


add_action( 'admin_menu', 'add_google_to_admin' );
function add_google_to_admin() {
    add_menu_page( 'add_google_to_admin', 'Comments', 'read', get_site_url().'/wp-admin/edit-comments.php', '', 'dashicons-text', 2 );
}

function co_owner_widgets_init()
{
    register_sidebar(
        array(
            'name'              => __('Footer Menu 1', 'co_owner'),
            'id'                => 'footer_menu_1',
            'description'       => __('Widgets in this area will be displayed in the second column in the footer.', 'co_owner'),
            'before_title'      => '<h5>',
            'after_title'       => '</h5>',
            'before_widget'     => '<div class="d-block max-w-306px">',
            'after_widget'      => '</div>',
        )
    );
    register_sidebar(
        array(
            'name'              => __('Footer Menu 2', 'co_owner'),
            'id'                => 'footer_menu_2',
            'description'       => __('Widgets in this area will be displayed in the second column in the footer.', 'co_owner'),
            'before_title'      => '<h5>',
            'after_title'       => '</h5>',
            'before_widget'     => '',
            'after_widget'      => '',
        )
    );
    register_sidebar(
        array(
            'name'              => __('Footer Menu 3', 'co_owner'),
            'id'                => 'footer_menu_3',
            'description'       => __('Widgets in this area will be displayed in the second column in the footer.', 'co_owner'),
            'before_title'      => '<h5>',
            'after_title'       => '</h5>',
            'before_widget'     => '',
            'after_widget'      => '',
        )
    );
}
add_action('widgets_init', 'co_owner_widgets_init');


add_filter('bbp_current_user_can_access_create_topic_form', 'custom_bbp_access_topic_form');
function custom_bbp_access_topic_form($retval)
{
    if (bbp_is_forum_archive()) {
        $retval = bbp_current_user_can_publish_topics();
    }
    return $retval;
}

add_filter('wp_link_query_args', 'my_wp_link_query_args');

function my_wp_link_query_args($query)
{
    //print_r($query);exit;
    // check to make sure we are not in the admin
    //if ( !is_admin() ) {
    $query['post_type'] = array('forum', 'topic', 'reply'); // show only posts and pages
    //}

    return $query;
}

/** 2-10-2021 */
add_filter('xmlrpc_enabled', '__return_false');
/** 2-10-2021 */
function my_ajax_callback_function() { 
			if (isset($_POST['email'])) {
				//user posted variables
				$name = $_POST['name'];
				

				
				//php mailer variables
				$to = 'hello@propertymates.io';
				$subject = "Enquiry came for legal assistance".$_POST['title'];
				$headers='';
				$headers.="MIME-Version: 1.0 \r\n";
				//$headers .= 'Cc:' . $_POST['email'] . "\r\n";
				$headers.="Content-Type: text/html; charset=UTF-8";
				
				$headers .= 'From: '. $_POST['email'] . "\r\n" .
				'Reply-To: ' . $_POST['email'] . "\r\n";
				//  $message = 'Enquire Now with'. $_POST['email'] .( (isset($_POST['agreement']) && !empty($_POST['agreement']) ) ? 'for'.$_POST['agreement'] : '');
				$message = '<table><tr><td>Username - '.$_POST['name']. '</td></tr><tr><td> Email address - '.$_POST['email']. '</td></tr><tr><td> Enquire Now with : '. $_POST['email'] .( (isset($_POST['agreement']) && !empty($_POST['agreement']) ) ? ' for '.$_POST['agreement'] : ''). '</td></tr><tr><td> Listing - <a href="'.$_POST['urls'].'">'.$_POST['urls'].'</a></td></tr></table>';
				
				$sent = wp_mail(  $to, $subject, $message, $headers);
				
				if($sent) {
					echo json_encode(array("sent"=>"sent")); exit;
				}//mail sent!
				else  {
					echo json_encode(array("sent"=>"failed"));  exit;
				}//message wasn't sent
			}
		}
		add_action( 'wp_ajax_my_action_name', 'my_ajax_callback_function' );    // If called from admin panel
		add_action( 'wp_ajax_nopriv_my_action_name', 'my_ajax_callback_function' );    // If called from front end
		
			?>
				
				
				
               <?php 
               /*
 * Change the comment reply link to use 'Reply to <Author First Name>'
 */
function add_comment_author_to_reply_link($link, $args, $comment){

    $comment = get_comment( $comment );

    // If no comment author is blank, use 'Anonymous'
    if ( empty($comment->comment_author) ) {
        if (!empty($comment->user_id)){
            $user=get_userdata($comment->user_id);
            $author=$user->user_login;
        } else {
            $author = __('Anonymous');
        }
    } else {
        $author = $comment->comment_author;
    }

    // If the user provided more than a first name, use only first name
    if(strpos($author, ' ')){
        $author = substr($author, 0, strpos($author, ' '));
    }

    // Replace Reply Link with "Reply to <Author First Name>"
    $reply_link_text = $args['reply_text'];
    $link = str_replace($reply_link_text, 'Reply to ' . $author, $link);

    return $link;
}
add_filter('comment_reply_link', 'add_comment_author_to_reply_link', 10, 3);

function default_comments_on( $data ) {
    if( $data['post_type'] == 'property' ) {
        $data['comment_status'] = 'open';
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'default_comments_on' );


function getTotalVoteByPostId($post_id){
global $wpdb;
$sql='SELECT t1.*,t2.meta_value as vote
 FROM `wp_comments` t1 INNER JOIN wp_commentmeta t2 ON(t1.comment_ID=t2.comment_id)
 WHERE t1.`comment_post_ID`= "'.$post_id.'" AND t2.meta_key="wpdiscuz_votes" ';	

 $rows = $wpdb->get_results($sql);
 $totalVote=0;
 if($rows){
	 foreach($rows as $data){
		 $totalVote = $totalVote+$data->vote;
		 
	 }
 }
 return $totalVote;
	
}

//add_action('init','testMail');
function testMail(){
	
	var_dump(genEmailVerification(176));
	
}

function custom_redirects() {
 
    if ( is_page('my-account-verification') ) {

			if(is_user_logged_in()) {
				$current_user = wp_get_current_user();
				  $account_activated =  get_user_meta($current_user->ID,'account_activated',true);
				  if($account_activated==0){
					wp_clear_auth_cookie();
					wp_set_current_user( 0 );
					wp_redirect(get_site_url(). '/email-verification?checkVarify=1');
					exit();  
				  }
		   }		
        
    }
 
}
/*
add_action( 'template_redirect', 'custom_redirects',99 );
*/

add_shortcode('CHECK_EMAIL_VERIFICATION',function(){
	$html='';
	ob_start();
	include('Shortcode/email_verify_tpl.php');
	$html= ob_get_contents();
	ob_get_clean();
	echo $html;
});


add_action('wp_ajax_site_logout_fnc','site_logout_fnc');
add_action('wp_ajax_nopriv_site_logout_fnc','site_logout_fnc');
function site_logout_fnc(){
	
	wp_logout();	
	die;
}


add_action('init',function(){

	//pr($_COOKIE);
	

});

add_filter('get_avatar', 'site_get_avatar', 99, 5);

function site_get_avatar($avatar, $id_or_email, $size, $default, $alt){
    //$avatar format includes the tag <img>

	
	preg_match('%<img.*?src=["\'](.*?)["\'].*?/>%i', $avatar, $matches);
	$path = array_pop($matches);
	
		$headers = get_headers($path, 1);
		if (strpos($headers['Content-Type'], 'image/') !== false) {
			
		} else {
			$path = site_url().'/wp-content/themes/co-owner/images/avatar.png';
		}
    $avatar = "<img src='".$path."' alt='".$alt."' height='".$size."' width='".$size."' />";
    return $avatar;
}

 function ci_get_related_posts( $post_id, $related_count, $args = array() ) {
        $args = wp_parse_args( (array) $args, array(
            'orderby' => 'rand',
            'return'  => 'query', // Valid values are: 'query' (WP_Query object), 'array' (the arguments array)
        ) );

        $related_args = array(
            'post_type'      => get_post_type( $post_id ),
            'posts_per_page' => $related_count,
            'post_status'    => 'publish',
            'post__not_in'   => array( $post_id ),
            'orderby'        => $args['orderby'],
            'tax_query'      => array()
        );

        $post       = get_post( $post_id );
        $taxonomies = get_object_taxonomies( $post, 'names' );

        foreach ( $taxonomies as $taxonomy ) {
            $terms = get_the_terms( $post_id, $taxonomy );
            if ( empty( $terms ) ) {
                continue;
            }
            $term_list                   = wp_list_pluck( $terms, 'slug' );
            $related_args['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term_list
            );
        }

        if ( count( $related_args['tax_query'] ) > 1 ) {
            $related_args['tax_query']['relation'] = 'OR';
        }

        if ( $args['return'] == 'query' ) {
            return new WP_Query( $related_args );
        } else {
            return $related_args;
        }
    }
	
	
	
	function auto_login( $user_ID ) {
		
		    wp_clear_auth_cookie();
			wp_set_current_user ( $user_ID );
			wp_set_auth_cookie  ( $user_ID );

		
}



?> 