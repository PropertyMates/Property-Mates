<?php

//add actions when plugin loaded to add these to the tools>export options
add_action( 'export_filters', 'bsp_export_filters' );
add_filter( 'export_args', 'bsp_export_args' );
add_action( 'export_wp', 'bsp_export_settings'  );

//if tab called - link to the export page
function bsp_style_settings_export() {
	
	?>
	<table>
	<tr>
	<td>
<h3>
		<?php _e ('Export Plugin Settings' , 'bbp-style-pack' ) ; ?>
	</h3>
	<p>
		<?php _e ('This tab lets you export settings to an file.' , 'bbp-style-pack' ) ; ?>
	</p>
	<p>
		<?php _e ('This can be useful if you want to move settings from say a test or development site to a live site ' , 'bbp-style-pack' ) ; ?>
	</p>
	<p>
		<?php _e ('or' , 'bbp-style-pack' ) ; ?>
	</p>
	<p>
		<?php _e ('to let you save a set of settings, so that you can come back to them if need-be' , 'bbp-style-pack' ) ; ?>
	</p>
	<p><b>
		<?php _e ('Click the button below which will take you to the export options in Wordpress and select \'bbp style settings\' at the bottom of the list', 'bbp-style-pack' ) ; ?>
	</b></p>
	</td>
	<td>
	<?php
				//show style image
				echo '<img src="' . plugins_url( 'images/export.JPG',dirname(__FILE__)  ) . '" width = "600" > '; ?>
	</td>
	</tr>
	</table>
	<?php $export = home_url().'/wp-admin/export.php' ; ?>
	<input type="submit" value="<?php _e( 'Export Settings', 'bbp-style-pack' ); ?>" class="button-primary" onClick="document.location.href='<?php echo $export ; ?>'" />
 <?php
}



//set options as the filter
function bsp_export_filters() {
		?>
		<p><label><input type="radio" name="content" value="bsp" /> <?php _e( 'bbp Style Pack Settings', 'bbp-style-pack' ); ?></label></p>
		<?php
	}	
		
function bsp_export_args( $args ) {
		if ( ! empty( $_GET['content'] ) && 'bsp' == $_GET['content'] ) {
			return array( 'bsp' => true );
		}
		return $args;

}

function bsp_export_settings ($args='') {
	global $wpdb;
	if ( ! empty( $args['bsp'] ) ) {

			$sitename = sanitize_key( get_bloginfo( 'name' ) );
			if ( ! empty( $sitename ) ) {
				$sitename .= '.';
			}
			$filename = $sitename . 'bbp Style Settings.' . date( 'Y-m-d' ) . '.json';
			$export_options = array();
			// we're going to use a random hash as our default, to know if something is set or not
			$hash = '048f8580e913efe41ca7d402cc51e848';
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ), true );
			$option_names = array (
			'bsp_style_settings_f',
			'bsp_style_settings_ti',
			'bsp_style_settings_t',
			'bsp_style_settings_la',
			'bsp_style_settings_form',
			'bsp_profile',
			'bsp_forum_display',
			'bsp_forum_order',
			'bsp_login',
			'bsp_breadcrumb',
			'bsp_templates',
			'bsp_css',
			'bsp_roles',
			'bsp_css_location',
			'bsp_style_settings_freshness',
			'bsp_style_settings_buttons',
			'bsp_topic_order',
			'bsp_style_settings_search',
			'bsp_style_settings_unread',
			'bsp_login_fail',
			'bsp_style_settings_email',
			'bsp_style_settings_translation',
			'bsp_style_settings_bugs',
			'bsp_style_settings_topic_preview',
			) ;
						
			foreach ( $option_names as $option_name ) {
				$option_value = get_option( $option_name, $hash );
					// only export the setting if it's present
					if ( $option_value !== $hash ) {
						$export_options[ $option_name ] = maybe_serialize( $option_value );
					}
			}
			$JSON_PRETTY_PRINT = defined( 'JSON_PRETTY_PRINT' ) ? JSON_PRETTY_PRINT : null;
			echo json_encode( array( 'version' => 1, 'options' => $export_options), $JSON_PRETTY_PRINT );
			exit ;
	}
			
}

