<?php 

$banners = new WP_query(
	array(
		'post_type' => 'depoimentos',
		'order' => 'DSC',
		
		)
	);
?>

<?php if($banners->have_posts()): ?> 
<section id="depoimentos" class="depoimentos" data-ride="carousel">	
	<div class="container">
		<div class="title">O que estão dizendo?</div>
		<div class="testimonials">
	    <?php 
	    
	    while($banners->have_posts()): $banners->the_post(); 

	    	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' ); 
	    	
	    ?>	    
	    	<div class="slide-dep">	    	
			    	
		      	<img src="<?php echo $thumb[0]; ?>" />			      	
				<h6 class="name"><?php the_title(); ?></h6>
				<div class="text">						
					<?php the_content(); ?>
				</div>	    	
		    		      	    	
		 	</div>
			
	    <?php endwhile; ?>	 	
		</div>
	</div>
</section>
<?php endif; ?>
