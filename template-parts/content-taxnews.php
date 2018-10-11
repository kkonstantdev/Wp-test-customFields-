<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

 <div class="item">
 	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
 	<div><?php the_excerpt(); ?></div>
 </div>
