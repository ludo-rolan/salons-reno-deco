<?php
/**
 * Template Name: page-no-sidebar
 *
 *
 * @package WordPress
 * @subpackage reworldmedia
 * @since reworldmedia-network
 */

get_header();
$page_no_sidebar_cls = apply_filters('page_no_sidebar_cls', 'site-content page-no-sidebar');
?>
<div id="content" class="<?php echo $page_no_sidebar_cls; ?>" role="main">
	<?php the_content(); ?>
</div>

<?php get_footer(); ?>