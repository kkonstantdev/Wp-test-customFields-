<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
 <h1><?php the_title(); ?></h1>
 <div><?php the_content(); ?></div>
 <div class="meta-content"> 
 		<?php  $meta_content = get_post_meta($post->ID, 'info_news', true);
 			// var_dump($meta_content);
 		echo $meta_content[0];
 		?>
 	</div>
</article><!-- #post-## -->
