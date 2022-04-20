<?php

//login settings page

function bsp_style_settings_theme_support() {
 ?>
			
	<h3>
		<?php _e ('Theme Support' , 'bbp-style-pack' ) ; ?>
	</h3>
	
	<?php 
/////////////////////  TWENTY TWENTY TWO	
	if (wp_get_theme()== 'Twenty Twenty-Two') {
	?>
		
	<p><b>
		<?php _e ('You are using the theme Twenty Twenty-Two - enable this section to get bbpress to display correctly' , 'bbp-style-pack' ) ; ?>
	</b></p>
	
	<p>
		<?php _e ('Twenty Twenty-Two is one of the few "block themes" - This is a new way that WordPress plans to develop themes, and the editor is still a "beta" version.  I have done sufficient to get this theme to display 
		bbpress, but much of the functionality of WordPress eg Sidebars, conditional logic etc. is not easily achieved.' , 'bbp-style-pack' ) ; ?>
	</p>
	
	<p>
		<?php _e ('You may find that features within bbpress and also in this plugin do not work, but you will have forums, topics and replies' , 'bbp-style-pack' ) ; ?>
	</p>
	
	<p>
		<?php _e ('If you want to carry on using this theme, you will need to accept this.' , 'bbp-style-pack' ) ; ?>
	</p>
		
	<?php global $bsp_style_settings_theme_support ;
	?>
	<form method="post" action="options.php">
	<?php wp_nonce_field( 'style-settings-theme-support', 'style-settings-nonce' ) ?>
	<?php settings_fields( 'bsp_style_settings_theme_support' );
	//create a style.css on entry and on saving
	generate_style_css() ;
	?>
					
			<table class="form-table">
			
			<!-- ACTIVATE  -->	
	<!-- checkbox to activate  -->
		<tr valign="top">  
			 <th style="width: 350px">
				<?php _e('Enable Twenty Twenty-Two bbpress theme support', 'bbp-style-pack'); ?>
			</th>
						
			<td>
				<?php 
				$item = (!empty( $bsp_style_settings_theme_support['twentytwentytwo_activate'] ) ?  $bsp_style_settings_theme_support['twentytwentytwo_activate'] : '');
				echo '<input name="bsp_style_settings_theme_support[twentytwentytwo_activate]" id="bsp_style_settings_theme_support[twentytwentytwo_activate]" type="checkbox" value="1" class="code" ' . checked( 1,$item, false ) . ' />' ;
				?>
				<label class="description" for="bsp_settings[new_topic_description]">
					<?php _e( 'Enable Theme Support', 'bbp-style-pack' ); ?>
				</label>
			</td>
		
		</tr>
		<?php 
		$name = __('Enter Forum Width','bbp-style-pack')  ; 
		$item =  'bsp_style_settings_theme_support["twentytwentytwo_width"]' ;
		$item1 = (!empty($bsp_style_settings_theme_support['twentytwentytwo_width'] ) ? $bsp_style_settings_theme_support['twentytwentytwo_width']  : ''); 
		?>
		<tr>
		<td>
		<?php echo $name ; ?>
		</td>
		<td>
				<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $item1 ).'"<br>' ; ?> 
				<label class="description"><?php _e( 'Default 75%', 'bbp-style-pack' ); ?></label><br/>
			</td>
			</tr>
		
		
		
		
		</table>
		
		
		
		
		
		
<?php
	} // end of twenty twenty two
		
?>	
	
	<!-- save the options -->
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save', 'bbp-style-pack' ); ?>" />
		</p>
	</form>
	</div><!--end sf-wrap-->
	</div><!--end wrap-->
	
<?php
}







