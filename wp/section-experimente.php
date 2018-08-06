<?php 
    $_posts = new WP_Query( array(
        'post_type'         => 'page',
        'page_id'    => 126
    ));
?>

<?php if($_posts->have_posts()): while($_posts->have_posts()): $_posts->the_post();?> 
<section class="experimente" id="experimente">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h4><?php the_title(); ?></h4>
				<div class="text">
					<?php the_content(); ?>
				</div>
			</div>			
		</div>
	</div>
</section>
<?php endwhile; endif; wp_reset_postdata(); ?>