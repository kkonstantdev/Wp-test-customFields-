<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentysixteen' ),
				'next_text'          => __( 'Next page', 'twentysixteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		<?php

				// $terms = get_terms( 'taxnews' );

				// if( $terms && ! is_wp_error($terms) ){
				// 	echo "<ul>";
				// 	foreach( $terms as $term ){
				// 		echo "<li>". $term->name ."</li>";

				// 	}
				// 	echo "</ul>";
				// }

				$args = array( 'hide_empty=0' );
					$terms = get_terms('taxnews', $args);

					// собираем их и выводим
					if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
						$count = count($terms);
						$i=0;
						$term_list = '<p class="taxnews-archive">';
						foreach ($terms as $term) {
							$i++;
							$term_list .= '<a href="' . get_term_link( $term ) . '" title="' . sprintf(__('View all post filed under %s', 'my_localization_domain'), $term->name) . '">' . $term->name . '</a>';
							if ($count != $i) {
								$term_list .= ' &middot; ';
							}
							else {
								$term_list .= '</p>';
							}
						}
						echo $term_list;
					}
			?>

			<h4>Произвольные посты по термам таксономий: "Горячие новости", "Funk)"!</h4>

			<?php

			 $params = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'taxnews',
							'field' => 'slug',
							'terms' => 'hot-news'
						),
						array(
							'taxonomy' => 'taxnews_music',
							'field' => 'slug',
							'terms' => 'funk'
						)
					)
				);
				$q = new WP_Query( $params );

				if($q->have_posts()) {
					 
					while($q->have_posts()){ $q->the_post();
						echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>
						<p>' . get_the_content() . '</p>';
					}
				}
				 
			?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
