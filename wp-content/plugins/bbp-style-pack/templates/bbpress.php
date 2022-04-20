<?php
/**
 * bbpress file to emulate page.html to display forumns
 *
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

block_template_part('header');

wp_head();

while ( have_posts() ) : the_post();
	?>

	<div class="bbpress-container">
		<?php the_title( '<h1 style="margin-bottom: 6rem; font-size: clamp(2.75rem, 6vw, 3.25rem);" class="alignwide wp-block-query-title">', '</h1>' ); ?>
		
		<div class="bbpress-content">
			<?php the_content(); ?>
		</div>
	</div>

<?php
endwhile;

block_template_part('footer');

wp_footer() ;

?>

