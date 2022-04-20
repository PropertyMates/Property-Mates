<?php

//Theme Support functions
global $bsp_style_settings_theme_support ;

if (!empty ($bsp_style_settings_theme_support['twentytwentytwo_activate'])  && wp_get_theme() == 'Twenty Twenty-Two') {
add_filter ('bbp_template_include_theme_compat' , 'bsp_twentytwenty_bbpress_fix' ) ;
}
	
function bsp_twentytwenty_bbpress_fix ($template) {
	$template= BSP_PLUGIN_DIR.'/templates/bbpress.php' ;
return $template ;
}

//I don't think these make a difference, but added just in case !!
add_filter( 'bbp_register_topic_post_type', 'bsp_bbpress_fix_topic_header') ;
add_filter( 'bbp_register_reply_post_type', 'bsp_bbpress_fix_reply_header') ;


//this is added to fix this support thread   https://bbpress.org/forums/topic/bbpress-elementor-header/
function bsp_bbpress_fix_topic_header ($topic_post_type) {

$topic_post_type = array(
				'labels'              => bbp_get_topic_post_type_labels(),
				'rewrite'             => bbp_get_topic_post_type_rewrite(),
				'supports'            => bbp_get_topic_post_type_supports(),
				'description'         => esc_html__( 'bbPress Topics', 'bbpress' ),
				'capabilities'        => bbp_get_topic_caps(),
				'capability_type'     => array( 'topic', 'topics' ),
				'menu_position'       => 555555,
				'has_archive'         => ( 'forums' === bbp_show_on_root() ) ? bbp_get_topic_archive_slug() : false,
				'exclude_from_search' => true,
				'show_in_nav_menus'   => true,
				'public'              => true,
				'show_ui'             => current_user_can( 'bbp_topics_admin' ),
				'can_export'          => true,
				'hierarchical'        => false,
				'query_var'           => true,
				'menu_icon'           => '',
				'source'              => 'bbpress',
			)  ;
			
return $topic_post_type ;
}

function bsp_bbpress_fix_reply_header ($reply_post_type) {

$reply_post_type = array(
				'labels'              => bbp_get_reply_post_type_labels(),
				'rewrite'             => bbp_get_reply_post_type_rewrite(),
				'supports'            => bbp_get_reply_post_type_supports(),
				'description'         => esc_html__( 'bbPress Replies', 'bbpress' ),
				'capabilities'        => bbp_get_reply_caps(),
				'capability_type'     => array( 'reply', 'replies' ),
				'menu_position'       => 555555,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'show_in_nav_menus'   => true,
				'public'              => true,
				'show_ui'             => current_user_can( 'bbp_replies_admin' ),
				'can_export'          => true,
				'hierarchical'        => false,
				'query_var'           => true,
				'menu_icon'           => '',
				'source'              => 'bbpress',
			)  ;
			
return $reply_post_type ;
}

//NOT USED

function bbp_get_the_block_template_html() {
	$content='
	<!-- wp:group {"tagName":"main"} -->
<main class="wp-block-group"><!-- wp:group {"layout":{"inherit":true}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":1,"align":"wide","style":{"spacing":{"margin":{"bottom":"var(--wp--custom--spacing--medium, 6rem)"}}}} /-->

<!-- wp:post-featured-image {"align":"wide","style":{"spacing":{"margin":{"bottom":"var(--wp--custom--spacing--medium, 6rem)"}}}} /-->

<!-- wp:separator {"align":"wide","className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide is-style-wide"/>
<!-- /wp:separator --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":32} -->
<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:post-content {"layout":{"inherit":true}} /-->

<!-- wp:group {"layout":{"inherit":true}} -->
<div class="wp-block-group">
<!-- wp:post-comments {"style":{"spacing":{"padding":{"top":"var(--wp--custom--spacing--medium, 6rem)"}}}} /--></div>
<!-- /wp:group --></main>
<!-- /wp:group -->' ;
	update_option ('rew-content',$content ) ;
	//global $wp_current_template_content;
	
	global $wp_embed;

	$content = $wp_embed->run_shortcode( $content );
	$content = $wp_embed->autoembed( $content );
	$content = do_blocks( $content );
	$content = wptexturize( $content );
	$content = convert_smilies( $content );
	$content = shortcode_unautop( $content );
	$content = wp_filter_content_tags( $content );
	$content = do_shortcode( $content );
	$content = str_replace( ']]>', ']]&gt;', $content );

	// Wrap block template in .wp-site-blocks to allow for specific descendant styles
	// (e.g. `.wp-site-blocks > *`).
	return '<div class="wp-site-blocks">' . $content . '</div>';
}