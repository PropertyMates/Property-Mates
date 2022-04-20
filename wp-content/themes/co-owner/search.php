<?php
/**
 * Search results page
 *
 * Please see /external/bootstrap-utilities.php for info on CoOwner::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Co-Owner
 * @autor 		TechXperts
 */
get_header();
?>

<?php if ( have_posts() ): ?>
	<div class="content">
		<h1><?php echo __('Search Results for', 'co_owner'); ?> '<?php echo get_search_query(); ?>'</h1>
		<ul class="list-unstyled">
			<?php while ( have_posts() ) : the_post(); ?>
			<li class="media">
				<div class="media-body">
					<h2>
					   <a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title(); ?>" rel="bookmark">
						   <?php the_title(); ?>
					   </a>
					</h2>
					<time datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate>
						<?php the_date(); ?> <?php the_time(); ?>
					</time>
					<?php comments_popup_link(__('Leave a Comment', 'co_owner'), __('1 Comment', 'co_owner'), __('% Comments', 'co_owner')); ?>
					<?php the_content(); ?>
				</div>
			</li>
			<?php endwhile; ?>
		</ul>
	</div>
<?php else: ?>
	<h1>
		<?php echo __('No results found for', 'co_owner'); ?> '<?php echo get_search_query(); ?>'
	</h1>
<?php endif; ?>

<?php get_footer(); ?>
