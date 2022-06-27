<?php

//functions for the quote tab
global $bsp_style_settings_quote ;



if ($bsp_style_settings_quote['quote_position'] == 1) {
	add_filter('bbp_topic_admin_links', 'bsp_quote_admin_link');
	add_filter('bbp_reply_admin_links', 'bsp_quote_admin_link');
}
if ($bsp_style_settings_quote['quote_position'] == 2) {
		add_action ('bbp_theme_before_topic_content' ,  'bsp_quote_reply_content');
		add_action ('bbp_theme_before_reply_content' ,  'bsp_quote_reply_content');
}
if ($bsp_style_settings_quote['quote_position'] == 3) {		
		add_action ('bbp_theme_after_reply_content' ,  'bsp_quote_reply_content');
		add_action ('bbp_theme_after_topic_content' ,  'bsp_quote_reply_content');
}
	

function bsp_quote_reply_content($content) {
	if(bbp_current_user_can_access_create_reply_form()) {
	echo  '<div class="bsp-quote-block">'.bsp_quote().'</div>' ;
	}
}
	
	
function bsp_quote_admin_link($links) {
	$links['Quote'] = bsp_quote() ;
return $links ;
}


function bsp_quote() {
		global $bsp_style_settings_quote ;
        $id = bbp_get_reply_id();

        $is_reply = true;
        if ($id == 0) {
            $is_reply = false;
            $id = bbp_get_topic_id();
        }

        if ($is_reply) {
                $url = bbp_get_reply_url($id);
                $ath = bbp_get_reply_author_display_name($id);
            } else {
                $url = get_permalink($id);
                $ath = bbp_get_topic_author_display_name($id);
            }
		$quote_name = (!empty ($bsp_style_settings_quote['quote_name'] ) ? $bsp_style_settings_quote['quote_name']  : 'Quote' ) ;
        return '<a href="#'.$id.'" bbp-url="'.$url.'" bbp-author="'.$ath.'" class="bsp-quote-link">'.$quote_name.'</a>';
}


//amend allowed tags to accept <div class> and <span class> tags
add_filter ('bbp_kses_allowed_tags' , 'bsp_allow_div_tag', 100 ) ;

function bsp_allow_div_tag( $tags ) {
	$tags['div'] = array(
			'class'     => true
		);
		$tags['span'] = array(
			'class'     => true
		);
		$tags['br'] = array();
		
					
	
	update_option ('rew_tags' , $tags) ;
return $tags ;
	
	
	
}

//ajax functions

//backend
add_action ('wp_ajax_get_status_by_ajax' , 'bsp_function') ;
//front end
add_action ('wp_ajax_nopriv_get_status_by_ajax' , 'bsp_function') ;

function bsp_function () {
	//check_ajax_referrer
	//this comes from the variables set in generate_css.php wp_localise_script function
	wp_verify_nonce( $_POST['quote'], 'get_id_content' ) ;
	global $bsp_style_settings_quote ;
	$id = $_POST['id'] ;
	//set up elements
	$preamble = (!empty ($bsp_style_settings_quote['quote_preamble'] ) ? $bsp_style_settings_quote['quote_preamble']  : 'On' ) ;
	$conclusion = (!empty ($bsp_style_settings_quote['conclusion'] ) ? $bsp_style_settings_quote['conclusion']  : 'said' ) ;
	if (bbp_is_reply ($id)) {
		$content = bbp_get_reply_content($id );
		$author = bbp_get_reply_author_link(array( 'type' => 'name', 'post_id'    => $id, ) );
		$date = (!empty ($bsp_style_settings_quote['date'] ) ? '<span class="bbp-reply-post-date">'.bbp_get_reply_post_date($id).'</span>'  : '' ) ;
	}
	if (bbp_is_topic ($id)) {
		$content = bbp_get_topic_content($id );
		$author = bbp_get_topic_author_link(array( 'type' => 'name', 'post_id'    => $id, ));
		$date = (!empty ($bsp_style_settings_quote['date'] ) ? '<span class="bbp-topic-post-date">'.bbp_get_topic_post_date($id).'</span>'  : '' ) ;
	}
	//set up default order
	if (!empty($bsp_style_settings_quote['date'] ) ? $total_items=4 : $total_items=3  ) ;
	if ($total_items==3) {
	$default_preamble=1;
	$default_author=2 ;
	$default_conclusion=3 ;
	}
	if ($total_items==4) {
	$default_preamble=1;
	$default_date = 2 ;
	$default_author=3 ;
	$default_conclusion=4 ;
	}
	//now change if set
	$order = array() ;
	$i=1 ;
	//set the limit to $total_items and set up order
		while($i<=$total_items)   {
		if ((!empty($bsp_style_settings_quote["preamble_order"]) ? $bsp_style_settings_quote["preamble_order"] : $default_preamble) == $i) $order[$i] = 'preamble_order' ;
		if ((!empty($bsp_style_settings_quote["date_order"]) ? $bsp_style_settings_quote["date_order"] : $default_date) == $i) $order[$i] = 'date_order' ;
		if ((!empty($bsp_style_settings_quote["author_order"]) ? $bsp_style_settings_quote["author_order"] : $default_author) == $i)  $order[$i] = 'author_order' ;
		if ((!empty($bsp_style_settings_quote["conclusion_order"]) ? $bsp_style_settings_quote["conclusion_order"] : $default_conclusion) == $i)  $order[$i] = 'conclusion_order' ;
		//increments $i	
		$i++;	
		}	 
		//start output
		echo '<blockquote><div class="bsp-quote-title">' ;
		$i=1 ;
		while($i<=$total_items)   {
		//then work out which is active and output
		if (!empty($order[$i])) {
		if ($order[$i] == 'preamble_order') echo $preamble ;
		if ($order[$i] == 'date_order') echo $date ;
		if ($order[$i] == 'author_order') echo $author ;
		if ($order[$i] == 'conclusion_order') echo $conclusion ;
		}
		$i++ ;
		echo ' ' ;
		}
		
	//output
	echo '</div>' ;
	echo $content ;
	echo '</blockquote>';
	echo '<br>'	;
wp_die() ;
}
