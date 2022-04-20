<?php

//quote style settings page

function bsp_style_settings_quote () {
	global $bsp_style_settings_quote ;
	?> 
	<Form method="post" action="options.php">
		<?php wp_nonce_field( 'style-settings_quote', 'style-settings-nonce' ) ?>
		<?php settings_fields( 'bsp_style_settings_quote' );
		//create a style.css on entry and on saving
		generate_style_css() ;
		generate_quote_style_css();
		?>
		<table class="form-table">
		<tr valign="top">
			<th colspan="2">
				<h3>
					<?php _e ('Quotes' , 'bbp-style-pack' ) ; ?>
				</h3>
		</tr>
		
		<table>
		<tr>
			<td>
				<p>
					<?php _e('This section allows you to add Quotes to your forums', 'bbp-style-pack'); ?>
				</p><p>
					<?php _e('<strong>NOTE- THIS ONLY WORKS FOR THE VISUAL EDITOR</strong>', 'bbp-style-pack'); ?>
				</p>
			</td>
		</tr>
		<tr>
			<td>	
				<?php echo '<img src="' . plugins_url( 'images/quotes1.png',dirname(__FILE__)  ) . '" > '; ?>
			
				<?php echo '<img src="' . plugins_url( 'images/quotes2.png',dirname(__FILE__)  ) . '" > '; ?>
			</td>
		</tr>
	</table>
	</table>
	<!-- save the options -->
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e( 'Save changes', 'bbp-style-pack' ); ?>" />
	</p>
	
	<hr>
	
	<table class="form-table">
	<!-- CREATE TOPIC BUTTON  -->	
	<!-- checkbox to activate  -->
		<tr valign="top">  
			<th>
				1. <?php _e('Activate Quotes', 'bbp-style-pack'); ?>
			</th>
			
			<td>
				<?php 
				$item = (!empty( $bsp_style_settings_quote['quote_activate'] ) ?  $bsp_style_settings_quote['quote_activate'] : '');
				echo '<input name="bsp_style_settings_quote[quote_activate]" id="bsp_style_settings_quote[quote_activate]" type="checkbox" value="1" class="code" ' . checked( 1,$item, false ) . ' />' ;
				?>
			</td>
		</tr>
						
		
		<tr>
		
			<tr valign="top">
			<th>
				2. <?php _e('Quote Name', 'bbp-style-pack'); ?>
			</th>
			
			<td colspan="2">
				<?php 
				$item1 = (!empty ($bsp_style_settings_quote['quote_name'] ) ? $bsp_style_settings_quote['quote_name']  : '' ) ?>
				<input id="bsp_style_settings_quote[quote_name]" class="medium-text" name="bsp_style_settings_quote[quote_name]" type="text" value="<?php echo esc_html( $item1 ) ;?>" /><br/>
				<label class="description" for="bsp_settings[quote_name]">
					<?php _e( 'Default : Quote', 'bbp-style-pack' ); ?>
				</label>
				<br/>
			</td>
		</tr>
		
		<!-- QUOTE POSITION -->					
		<tr>	
			<th>
				3. <?php _e('Quote Position', 'bbp-style-pack'); ?>
			</th>
		
		<?php
			$item =  'bsp_style_settings_quote[quote_position]' ;
			$item1 = (!empty($bsp_style_settings_quote['quote_position']) ? $bsp_style_settings_quote['quote_position'] : 1); 
			?>
		
			<td style="vertical-align:top">
			<p>
				<?php
				echo '<input name="'.$item.'" id="'.$item.'" type="radio" value="1" class="code"  ' . checked( 1,$item1, false ) . ' />' ;
				_e ('In the Admin Links' , 'bbp-style-pack' ) ;?>
				</p>
				<p>
				<?php
				echo '<input name="'.$item.'" id="'.$item.'" type="radio" value="2" class="code"  ' . checked( 2,$item1, false ) . ' />' ;
				_e ('Above the Content' , 'bbp-style-pack' ) ;?>
				</p>
				<p>
			<?php
				echo '<input name="'.$item.'" id="'.$item.'" type="radio" value="3" class="code"  ' . checked( 3,$item1, false ) . ' />' ;
				_e ('Below the Content' , 'bbp-style-pack' ) ;?>
				</p>
			</td>
		</tr>
		
	
		
		<tr valign="top">
			<th>
				4. <?php _e('Quote Heading Preamble', 'bbp-style-pack'); ?>
			</th>
			
			<td colspan="2">
				<?php					
				$item1 = (!empty ($bsp_style_settings_quote['quote_preamble'] ) ? $bsp_style_settings_quote['quote_preamble']  : '' ) ?>
				<input id="bsp_style_settings_quote[quote_preamble]" class="medium-text" name="bsp_style_settings_quote[quote_preamble]" type="text" value="<?php echo esc_html( $item1 ) ;?>" /><br/>
				<label class="description" for="bsp_settings[quote_preamble]">
					<?php _e( 'Default : On ', 'bbp-style-pack' ); ?>
				</label>
				<br/>
			</td>
		</tr>
					
	
	<!-- checkbox to activate  -->
					
		<tr valign="top">  
			<th>
				5. <?php _e('Show date & time', 'bbp-style-pack'); ?>
			</th>
			
			<td>
				<?php 
				$item = (!empty( $bsp_style_settings_quote['date'] ) ?  $bsp_style_settings_quote['date'] : '');
				echo '<input name="bsp_style_settings_quote[date]" id="bsp_style_settings_quote[date]" type="checkbox" value="1" class="code" ' . checked( 1,$item, false ) . ' />' ;
				?>
			</td>
		</tr>
						
		<tr valign="top">
			<th>
				6. <?php _e('Quote Heading conclusion', 'bbp-style-pack'); ?>
			</th>
			
			<td colspan="2">
				<?php 
				$item1 = (!empty ($bsp_style_settings_quote['conclusion'] ) ? $bsp_style_settings_quote['conclusion']  : '' ) ?>
				<input id="bsp_style_settings_quote[conclusion]" class="medium" name="bsp_style_settings_quote[conclusion]" type="text" value="<?php echo esc_html( $item1 ) ;?>" /><br/>
				<label class="description" for="bsp_settings[conclusion]">
					<?php _e( 'Default : Said', 'bbp-style-pack' ); ?>
				</label>
				<br/>
			</td>
		</tr>
			
		<!--7. Font - Quote headings  ------------------------------------------------------------------->
			<tr>
			<?php 
			$name = ('Quote') ;
			$name0 = __('Quote Headings Font', 'bbp-style-pack') ;
			$name1 = __('Size', 'bbp-style-pack') ;
			$name2 = __('Color', 'bbp-style-pack') ;
			$name3 = __('Font', 'bbp-style-pack') ;
			$name4 = __('Style', 'bbp-style-pack') ;
			$area1='Size' ;
			$area2='Color' ;
			$area3='Font' ;
			$area4='Style';
			$item1="bsp_style_settings_quote[".$name.$area1."]" ;
			$item2="bsp_style_settings_quote[".$name.$area2."]" ;
			$item3="bsp_style_settings_quote[".$name.$area3."]" ;
			$item4="bsp_style_settings_quote[".$name.$area4."]" ;
			$value1 = (!empty($bsp_style_settings_quote[$name.$area1]) ? $bsp_style_settings_quote[$name.$area1]  : '') ;
			$value2 = (!empty($bsp_style_settings_quote[$name.$area2]) ? $bsp_style_settings_quote[$name.$area2]  : '') ;
			$value3 = (!empty($bsp_style_settings_quote[$name.$area3]) ? $bsp_style_settings_quote[$name.$area3]  : '') ;
			$value4 = (!empty($bsp_style_settings_quote[$name.$area4]) ? $bsp_style_settings_quote[$name.$area4]  : '') ;
			?>
			<th>
				<?php echo '7. '.$name0 ?>
			</th>
			<td>
				<?php echo $name1 ; ?>
			</td>
			<td>
				<?php echo '<input id="'.$item1.'" class="small-text" name="'.$item1.'" type="text" value="'.esc_html( $value1 ).'"<br>' ; ?> 
				<label class="description"><?php _e( 'Default 12px - see help for further info', 'bbp-style-pack' ); ?></label><br/>
			</td>
		</tr>
			
		<tr>
			<td>
			</td>
			<td>
				<?php echo $name2 ; ?> 
			</td>
			<td>
				<?php echo '<input id="'.$item2.'" class="bsp-color-picker" name="'.$item2.'" type="text" value="'.esc_html( $value2 ).'"<br>' ; ?> 
				<label class="description"><?php _e( 'Click to set color - You can select from palette or enter hex value - see help for further info', 'bbp-style-pack') ; ?>
				</label><br/>
			</td>
		</tr>
		
		<tr>
			<td>
			</td>
			<td>
				<?php echo $name3 ; ?> 
			</td>
			<td>
				<?php echo '<input id="'.$item3.'" class="medium-text" name="'.$item3.'" type="text" value="'.esc_html( $value3 ).'"<br>' ; ?> 
				<label class="description"><?php _e( 'Enter Font eg Arial - see help for further info', 'bbp-style-pack' ); ?></label><br/>
			</td>
		</tr>
		
		<tr>
			<td>
			</td>
			<td>
				<?php echo $name4 ; ?>
			</td>
			<td>
				<select name="<?php echo $item4 ; ?>">
				<?php echo '<option value="'.esc_html( $value4).'">'.esc_html( $value4) ; ?> 
				<option value="Normal">Normal</option>
				<option value="Italic">Italic</option>
				<option value="Bold">Bold</option>
				<option value="Bold and Italic">Bold and Italic</option>
				</select>
			</td>
		</tr>
		
	
			<?php 
			$name = ('Quote') ;
			$name1 = __('Background Color', 'bbp-style-pack') ;
			$name2 = __('Border Color', 'bbp-style-pack') ;
						
			$area1='_background_color';
			$area2='_border_color';
						
			$item1="bsp_style_settings_quote[".$name.$area1."]" ;
			$item2="bsp_style_settings_quote[".$name.$area2."]" ;
						
			$value1 = (!empty($bsp_style_settings_quote[$name.$area1]) ? $bsp_style_settings_quote[$name.$area1]  : '#eeeeee52') ;
			$value2 = (!empty($bsp_style_settings_quote[$name.$area2]) ? $bsp_style_settings_quote[$name.$area2]  : '#cccccc9e') ;
						
			?>
			
		<tr>
			<th>
				8. <?php echo $name1 ; ?>
			</th>
			
			<td>
				<?php echo '<input id="'.$item1.'" class="bsp-color-picker" name="'.$item1.'" type="text" value="'.esc_html( $value1 ).'"<br>' ; ?> 
			</td>
			<td>
				<label class="description">
					<?php _e( 'Click to set color - You can select from palette or enter hex value - see help for further info', 'bbp-style-pack') ; ?>
				</label>
				<br/>
			</td>
		</tr>
		
		<tr>
			<th>
				9.<?php echo $name2 ; ?>
			</th>
			
			<td>
				<?php echo '<input id="'.$item2.'" class="bsp-color-picker" name="'.$item2.'" type="text" value="'.esc_html( $value2 ).'"<br>' ; ?> 
			</td>
			<td>
				<label class="description">
					<?php _e( 'Click to set color - You can select from palette or enter hex value - see help for further info', 'bbp-style-pack') ; ?>
				</label>
				<br/>
			</td>
		</tr>
		
	
	</table>
	<hr>
	<table>
	
	<?php
	//work out isplay in order
	global $bsp_style_settings_quote ;
	if (!empty($bsp_style_settings_quote['date'] ) ? $total_items=4 : $total_items=3  ) ;
	?>
	
		<tr>
			<td>
				<?php _e ('DISPLAY ORDER' , 'bbp-style-pack' ) ; ?>
			</td>
		</tr>
		
		<tr>
			<td colspan = "2">
				<?php _e ('You may want to display the elements in a different order' , 'bbp-style-pack' ) ; ?>
			</td>
		</tr>
		
		<tr>
			<td style="vertical-align:top">
				<?php _e ('Quote Heading Preamble' , 'bbp-style-pack' ) ; ?>
			</td>
			
			<td style="vertical-align:top">
				<?php $item='bsp_style_settings_quote[preamble_order]' ; ?>
				<?php $value = (!empty($bsp_style_settings_quote["preamble_order"]) ? $bsp_style_settings_quote["preamble_order"] : '') ; ?>
				<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
				<label class="description">
					<?php _e( 'Enter the order ie a number from 1 to', 'bbp-style-pack' ); ?>
					<?php echo $total_items ; ?>
				</label>
				</br>
			</td>
		</tr>
		
		
		<?php 
		if (!empty($bsp_style_settings_quote['date'] ) )  {
		?>
		<tr>
			<td style="vertical-align:top">
				<?php _e ('Date' , 'bbp-style-pack' ) ; ?>
			</td>
			
			<td style="vertical-align:top">
				<?php $item='bsp_style_settings_quote[date_order]' ; ?>
				<?php $value = (!empty($bsp_style_settings_quote["date_order"]) ? $bsp_style_settings_quote["date_order"] : '') ; ?>
				<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
				<label class="description">
					<?php _e( 'Enter the order ie a number from 1 to', 'bbp-style-pack' ); ?>
					<?php echo $total_items ; ?>
				</label>
				</br>
			</td>
		</tr>
		
		<?php
		}
		?>
		
		<tr>
			<td style="vertical-align:top">
				<?php _e ('Author' , 'bbp-style-pack' ) ; ?>
			</td>
			
			<td style="vertical-align:top">
				<?php $item='bsp_style_settings_quote[author_order]' ; ?>
				<?php $value = (!empty($bsp_style_settings_quote["author_order"]) ? $bsp_style_settings_quote["author_order"] : '') ; ?>
				<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
				<label class="description">
					<?php _e( 'Enter the order ie a number from 1 to', 'bbp-style-pack' ); ?>
					<?php echo $total_items ; ?>
				</label>
				</br>
			</td>
		</tr>
		
		
		
		
		<tr>
			<td style="vertical-align:top">
				<?php _e ('Quote Heading conclusion ' , 'bbp-style-pack' ) ; ?>
			</td>
			
			<td style="vertical-align:top">
				<?php $item='bsp_style_settings_quote[conclusion_order]' ; ?>
				<?php $value = (!empty($bsp_style_settings_quote["conclusion_order"]) ? $bsp_style_settings_quote["conclusion_order"] : '') ; ?>
				<?php echo '<input id="'.$item.'" class="small-text" name="'.$item.'" type="text" value="'.esc_html( $value ).'"<br>' ; ?> 
				<label class="description">
					<?php _e( 'Enter the order ie a number from 1 to', 'bbp-style-pack' ); ?>
					<?php echo $total_items ; ?>
				</label>
				</br>
			</td>
		</tr>
		
	
	
	
	</table>
	<!-- save the options -->
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e( 'Save changes', 'bbp-style-pack' ); ?>" />
	</p>
	</form>
	</div><!--end sf-wrap-->
	</div><!--end wrap-->
	<?php
}
		

	
