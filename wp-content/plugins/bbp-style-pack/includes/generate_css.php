<?php
global $bsp_style_settings_form ;
global $bsp_style_settings_search ;
global $bsp_style_settings_t ;
global $bsp_style_settings_quote ;


function generate_style_css() {
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	global $bsp_css_location ;
	ob_start(); // Capture all output (output buffering)
	require (BSP_PLUGIN_DIR . '/css/styles.php');
	$css = ob_get_clean(); // Get generated CSS (output buffering)
	if (!empty ($bsp_css_location ['activate css location']) && !empty($bsp_css_location ['location'])) {
		$location = $bsp_css_location ['location'] ;
			// if it starts with '/' -  remove
			if (0 === strpos($location, '/')) {
			$location = substr( $location, 1, strlen($location) ) ;
			}
		// if it doesn't end with a '/' add one
		if (substr( $location, strlen($location)-1, strlen($location) ) !== '/') {
			$location = $location.'/' ;
		}
		$path = get_home_path();
		$path = $path.'/'.$location ;
		file_put_contents($path.'bspstyle.css', $css, LOCK_EX ); // Save it
		//then copy the test admin file to the same location (otherwise we don't need to as it is already in the css directory
		copy(BSP_PLUGIN_DIR . '/css/bsp_test.css', $path.'bsp_test.css');
	}
	else 
	file_put_contents(BSP_PLUGIN_DIR . '/css/bspstyle.css', $css, LOCK_EX ); // Save it

	
}

function bsp_enqueue_css() {
	global $bsp_css_location ;
	$bsp_ver = get_option('bsp_version') ;
	//register style so that it runs after bbpress (bbp-default)
	if (!empty ($bsp_css_location ['activate css location']) && !empty($bsp_css_location ['location'])) {
		$location = $bsp_css_location ['location'] ;
			// if it starts with '/' -  remove
		if (0 === strpos($location, '/')) {
			$location = substr( $location, 1, strlen($location) ) ;
		}
		// if it doesn't end with a '/' add one
		if (substr( $location, strlen($location)-1, strlen($location) ) !== '/') {
			$location = $location.'/' ;
		}
		$location = home_url().'/'.$location ;
		wp_register_style('bsp', $location.'bspstyle.css', array( 'bbp-default' ), $bsp_ver, 'screen');
	}
	else wp_register_style('bsp', plugins_url('css/bspstyle.css',dirname(__FILE__) ), array( 'bbp-default' ), $bsp_ver, 'screen');
	wp_enqueue_style( 'bsp');
	
	wp_enqueue_style( 'dashicons');
}

add_action('wp_enqueue_scripts', 'bsp_enqueue_css');


add_action( 'admin_enqueue_scripts', 'bsp_enqueue_color_picker' );
add_action( 'admin_enqueue_scripts', 'bsp_admin' );


//adds admin file for the 'not working' tab and tab settings styling
function bsp_admin () {
	global $bsp_css_location ;
	$bsp_ver = get_option('bsp_version') ;
	//register style so that it runs after bbpress (bbp-default)
if (!empty ($bsp_css_location ['activate css location']) && !empty($bsp_css_location ['location'])) {
	$location = $bsp_css_location ['location'] ;
			// if it starts with '/' -  remove
			if (0 === strpos($location, '/')) {
			$location = substr( $location, 1, strlen($location) ) ;
			}
		// if it doesn't end with a '/' add one
		if (substr( $location, strlen($location)-1, strlen($location) ) !== '/') {
			$location = $location.'/' ;
		}
	$location = home_url().'/'.$location ;
	wp_register_style('bsp_admin', $location.'bsp_admin.css');
	}
	else wp_register_style('bsp_admin', plugins_url('css/bsp_admin.css',dirname(__FILE__) ));	
	wp_enqueue_style( 'bsp_admin');
		
}

//add admin styling
add_action('admin_head', 'bsp_admin_css');

function bsp_admin_css() {
	if (!empty ($_REQUEST['page']) &&  $_REQUEST['page'] == 'bbp-style-pack' ) {
		
		echo '<style>
			#wpbody-content {
				background-color: #fff!important;
			}
	</style>';
	}
}

function bsp_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'bsp_enqueue_color_picker', plugins_url('js/bsp.js',dirname( __FILE__ )), array( 'wp-color-picker' ), false, true );
	
	}

	
if (!empty ( $bsp_style_settings_form['SubmittingActivate'])) add_action( 'wp_enqueue_scripts', 'bsp_enqueue_submit' );

	function bsp_enqueue_submit() {
		wp_enqueue_script( 'bsp_enqueue_submit', plugins_url('js/bsp_enqueue_submit.js',dirname( __FILE__ )));
	}
	
	
//if quotes active	
if (!empty ( $bsp_style_settings_quote['quote_activate'])) {
	//add the style sheet
	add_filter( 'mce_css', 'bsp_add_custom_editor_style' );
	//andenqueue the js
	add_action( 'wp_enqueue_scripts', 'bsp_enqueue_quote' );
}

//this function creates the style sheet, generated when the quotes tab is accessed.
function generate_quote_style_css() {
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	global $bsp_css_location ;
	ob_start(); // Capture all output (output buffering)
	require (BSP_PLUGIN_DIR . '/css/styles-quote.php');
	$css = ob_get_clean(); // Get generated CSS (output buffering)
	if (!empty ($bsp_css_location ['activate css location']) && !empty($bsp_css_location ['location'])) {
		$location = $bsp_css_location ['location'] ;
			// if it starts with '/' -  remove
			if (0 === strpos($location, '/')) {
			$location = substr( $location, 1, strlen($location) ) ;
			}
		// if it doesn't end with a '/' add one
		if (substr( $location, strlen($location)-1, strlen($location) ) !== '/') {
			$location = $location.'/' ;
		}
		$path = get_home_path();
		$path = $path.'/'.$location ;
		file_put_contents($path.'bspstyle-quotes.css', $css, LOCK_EX ); // Save it
	}
	else 
	file_put_contents(BSP_PLUGIN_DIR . '/css/bspstyle-quotes.css', $css, LOCK_EX ); // Save it

	
}

	function bsp_add_custom_editor_style() {
		return plugins_url('/css/bspstyle-quotes.css',dirname( __FILE__ ));
	}

	function bsp_enqueue_quote() {
		wp_enqueue_script( 'bsp_quote', plugins_url('js/bsp_quote.js',dirname( __FILE__ )) ) ;
		wp_localize_script( 'bsp_quote', 'bsp_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),'quote' => wp_create_nonce('get_id_content')  ) );
	}
	
if (!empty ( $bsp_style_settings_quote['SearchingActivate'])) add_action( 'wp_enqueue_scripts', 'bsp_enqueue_search' );

	function bsp_enqueue_search() {
		wp_enqueue_script( 'bsp_enqueue_search', plugins_url('js/bsp_enqueue_search.js',dirname( __FILE__ )) );
	}
	
	
	
	
//add the author delete topic/reply if enabled
if (!empty ($bsp_style_settings_t['participant_trash_topic_confirm']) || !empty ($bsp_style_settings_t['participant_trash_reply_confirm'] ) ) {
add_action( 'wp_enqueue_scripts', 'bsp_delete_check' );
}

function bsp_delete_check() {
	wp_enqueue_script( 'bsp_delete_check', plugins_url('js/bsp_delete.js',dirname( __FILE__ )) );
}

function generate_delete_js() {
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	global $bsp_style_settings_t ;
	$message = (!empty($bsp_style_settings_t['participant_trash_topic_text']) ? $bsp_style_settings_t['participant_trash_topic_text'] : 'Are you sure you want to delete this topic?');
	ob_start(); // Capture all output (output buffering)
	if (!empty ($bsp_style_settings_t['participant_trash_topic_confirm'] ) ) {
	echo 'jQuery( function($) {       
    $(\'a.bbp-topic-trash-link\').click( function( event ) {
		if( ! confirm( \''.$message.'\' ) ) {
            event.preventDefault();
        }           
		});
	});' ;
	}
	if (!empty ($bsp_style_settings_t['participant_trash_reply_confirm'] ) ) {
	$message = (!empty($bsp_style_settings_t['participant_trash_reply_text']) ? $bsp_style_settings_t['participant_trash_reply_text'] : 'Are you sure you want to delete this reply?');
				
	echo 'jQuery( function($) {       
    $(\'a.bbp-reply-trash-link\').click( function( event ) {
		if( ! confirm( \''.$message.'\' ) ) {
            event.preventDefault();
        }           
		});
	});' ;
	}
	$js = ob_get_clean(); // Get generated js (output buffering)
	file_put_contents(BSP_PLUGIN_DIR . '/js/bsp_delete.js', $js, LOCK_EX ); // Save it
}

//add select2 script if needed for topic tags
if (!empty ($bsp_style_settings_form['topic_tag_list'])) {
	add_action( 'wp_enqueue_scripts', 'bsp_select2_enqueue' );
}

function bsp_select2_enqueue(){
 
	wp_enqueue_style('bsp_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
	wp_enqueue_script('bsp_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery') );
	wp_enqueue_script( 'bsp_select2_class', plugins_url('js/bspselect2.js',dirname( __FILE__ )) );
}


