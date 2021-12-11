<?php

/**
 * Template part for displaying posts thumbnails
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package xqcow
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(array('col-12')); ?>>

	<div class="d-md-flex flex-md-row xqcow-post-thumb">
		<div class="d-md-flex flex-md-column  col-md-4 xqcow-thumb-img"><?php xqcow_post_thumbnail(); ?></div>
		<div class="d-md-flex flex-md-column col-md-8 xqcow-thumb-conteiner">
			<?php the_title('<p class="xqcow-thumb-title">', '</p>'); ?>
			<div class="xqcow-thumb-meta">
				<?php
				xqcow_posted_on();
				//xqcow_posted_by();
				?>
			</div>
			<div class="xqcow-thumb-content">
				<?php the_excerpt(); ?>
				<a class="xqcow-thumb-btn" href="<?php echo esc_url(get_permalink()) ?>">Ver mais</a>
			</div>
		</div>

	</div>

</article><!-- #post-<?php the_ID(); ?> -->