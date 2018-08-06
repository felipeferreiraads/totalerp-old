<?php 

$banners = new WP_query(
	array(
		'post_type' => 'slides',
		'order' => 'DSC',
		
		)
	);
?>

<?php if($banners->have_posts()): ?> 
<section id="banners" class="banners carousel " data-ride="carousel">	
	  
    <?php 
    $i = 0;
    while($banners->have_posts()): $banners->the_post(); 

    	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' ); 
    	$i++;
    ?>	    
    	<div class="item <?php echo ($i == 1) ?  'active' :  ''; ?>" style="background-image: url(<?php echo $thumb[0]; ?>)">
	    	<a href="<?php echo get_field('link'); ?>">
		    	
		      	<div class="container">				      	
					<h2 class="caption">
						<?php the_content(); ?>
					</h2>						
				</div>
		    	
	    	</a>		      	    	
	 	</div>
		
    <?php endwhile; ?>	 	
	
</section>
<?php endif; ?>
