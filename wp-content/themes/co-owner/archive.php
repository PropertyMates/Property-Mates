<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Please see /external/bootstrap-utilities.php for info on CoOwner::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Co-Owner
 * @autor 		TechXperts
 */
get_header();
$url = get_site_url().'/faq';
wp_redirect($url);
exit;
?>


<?php if ( have_posts() ): ?>

<?php if ( is_day() ) : ?>
	<h1>
		<?php echo __('Archive', 'co_owner'); ?>: <?php echo  get_the_date( 'D M Y' ); ?>
	</h1>
<?php elseif ( is_month() ) : ?>
	<h1>
		<?php echo __('Archive', 'co_owner'); ?>: <?php echo  get_the_date( 'M Y' ); ?>
	</h1>
<?php elseif ( is_year() ) : ?>
	<h1>
		<?php echo __('Archive', 'co_owner'); ?>: <?php echo  get_the_date( 'Y' ); ?>
	</h1>
<?php else : ?>
	<h1>
		<?php echo __('Archive', 'co_owner'); ?>
	</h1>
<?php endif; ?>

<ul class="list-unstyled">
	<?php while ( have_posts() ) : the_post(); ?>
	<li>
		<h2 class="media-heading">
			<a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h2>
		<time datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate>
			<?php the_date(); ?> <?php the_time(); ?>
		</time>
		<?php comments_popup_link(__('Leave a Comment', 'co_owner'), __('1 Comment', 'co_owner'), __('% Comments', 'co_owner')); ?>
		<?php the_content(); ?>
	</li>
	<?php endwhile; ?>
</ul>

<?php else: ?>
	<h1>
		<?php echo __('No posts to display', 'co_owner'); ?>
	</h1>
<?php endif; ?>

<?php get_footer(); ?>
