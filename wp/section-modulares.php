<?php 
    $_posts = new WP_Query( array(
        'post_type'         => 'page',
        'page_id'    => 93
    ));
?>

 <?php if($_posts->have_posts()): while($_posts->have_posts()): $_posts->the_post();
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' ); 
    ?> 
<section class="modulares" id="solucoes-modulares">
	<div class="container">
		<div class="row">
			<div class="col-lg-7">
				<img src="<?php echo $thumb[0] ?>" />
			</div>
			<div class="col-lg-5">
				<strong class="title">
					<?php the_title(); ?>
				</strong>
				<article class="text">
					<?php the_content(); ?>
				</article>
			</div>
		</div>
	</div>
</section>
<?php endwhile; endif; wp_reset_postdata(); ?>