<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<h1><?php single_term_title(); ?></h1>
		 <?php
		 		$args = array(
		 			'post_type' => 'news',
		 		);
		 		$query = new WP_Query( $args );

		 		while ( $query->have_posts()){
		 				$query->the_post();
		 				get_template_part( 'template-parts/content', 'taxnews' );
		 		} wp_reset_postdata;
		 ?>

	</main><!-- .site-main -->


</div><!-- .content-area -->

<?php get_footer(); ?>
 
