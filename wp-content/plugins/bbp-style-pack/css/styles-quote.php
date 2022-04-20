
/*----------------------  Quotes Styling --------------------------*/

<?php
global $bsp_style_settings_quote ;
if (!empty ($bsp_style_settings_quote['quote_activate'])) {
	?>

body#tinymce blockquote  {
    padding: 30px 20px 30px 20px !important ;
    margin: 0 0 15px 0!important ;
    quotes: none !important;
}

<?php

$field= (!empty($bsp_style_settings_quote['Quote_background_color']) ? $bsp_style_settings_quote['Quote_background_color'] : '#eeeeee52')  ;
		if (!empty ($field)){
			?>
body#tinymce blockquote  {
	background-color: <?php echo $field ; ?> !important;
}

<?php
}

$field= (!empty($bsp_style_settings_quote['Quote_border_color']) ? $bsp_style_settings_quote['Quote_border_color'] : '#cccccc9e')  ;
		if (!empty ($field)){
			?>
body#tinymce blockquote {
		 border-left: 4px solid <?php echo $field ; ?> !important ;
}
<?php
		}
?>

body#tinymce blockquote:before {
   	content: none !important;
    line-height: 0em !important ;
    margin-right: 15px !important ;
    vertical-align: -0.5em !important ;
	color: #ccc !important;
	
}

body#tinymce blockquote p {
     padding: 0 !important ;
    margin: 0 !important ;
}

body#tinymce blockquote .bsp-quote-title {
    margin-bottom: 15px;
}


/* ----------------------  Font - quote headings --------------------------*/
 
	<?php 
		$field= (!empty($bsp_style_settings_quote['QuoteSize']) ? $bsp_style_settings_quote['QuoteSize'] : '')  ;
		if (!empty ($field)){
			if (is_numeric($field)) $field=$field.'px' ;
	?>
			body#tinymce blockquote .bsp-quote-title
			{
				font-size:  <?php echo $field ; ?> ;
			}
		 
		<?php 
		}
		?>
 
	<?php 
	$field= (!empty($bsp_style_settings_quote['QuoteColor']) ? $bsp_style_settings_quote['QuoteColor'] : '')  ;
	if (!empty ($field)){
	?>
		body#tinymce blockquote .bsp-quote-title
		{
			color:  <?php echo $field ; ?> ;
		}
	 
	<?php
	} 
	?>
 
	<?php 
		$field= (!empty($bsp_style_settings_quote['QuoteFont']) ? $bsp_style_settings_quote['QuoteFont'] : '')  ;
		if (!empty ($field)){
	?>
			body#tinymce blockquote .bsp-quote-title
			{
				Font-Family:  <?php echo $field ; ?> ;
			}
	 
	<?php
		} 
		?>
		
	<?php 
		$field= (!empty($bsp_style_settings_quote['QuoteStyle']) ? $bsp_style_settings_quote['QuoteStyle'] : '')  ;
		if (!empty ($field)){
			if (strpos($field,'Italic') !== false){
	?>
				body#tinymce blockquote .bsp-quote-title,bsp-quote-title a
				{
					Font-Style:  italic ; 
				}
	 
	<?php
		} 
		if (strpos($field,'Bold') !== false){
		?>
			body#tinymce blockquote .bsp-quote-title,bsp-quote-title a
			{
				Font-weight:  bold ; 
			}
	 
		<?php
		}
		else { ?>
			body#tinymce blockquote .bsp-quote-title, bsp-quote-title a
			{
				Font-weight:  normal ; 
			}
	 
	<?php
		} // end of else
			
	?>
	 




<?php
}
}
?>
	

