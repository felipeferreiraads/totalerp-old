<?php
 
	$images = get_field('slider_topo');

	if( $images ): ?>
	
	    <div id="pageslider" class="banners">
	        
	        <?php foreach( $images as $image ): ?>	                
	            <div style="background-image:url(<?php echo $image['url']; ?>)">
	            	<section class="lettering">
	            		<div>
		            		<h2><?php the_field('texto_topo'); ?><br>
		            		<?php the_field( 'texto_rotativo_shortcode' ) ?></h2>
		            		<!--<a href="<?php bloginfo('url'); ?>/contato" class="btn">Contrate Agora</a>-->
		            	</div>
	            	</section>
	            </div>
	        <?php endforeach; ?>
	        
	    </div>
	
	<?php endif; ?>

	