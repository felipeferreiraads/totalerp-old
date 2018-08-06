<?php get_header(); ?>

	<?php
		global $query_string;

		$query_args = explode("&", $query_string);
		$search_query = array();

		foreach($query_args as $key => $string) {
			$query_split = explode("=", $string);
			$search_query[$query_split[0]] = urldecode($query_split[1]);
		} // foreach

		$search = new WP_Query($search_query); ?>


	<div class="page">  
	    
	    <section class="banners min clouds">
	        
	    </section>
		
		<div class="main">
			<div class="container">
				 <h1 class="post-title"><?php echo get_the_title(32); ?></h1>
		    	<article class="text">                           
		            <?php 
		            $page = get_page(32);
  					$content = apply_filters('the_content', $page->post_content);
   					echo $content;
		            get_search_form();?>
		        </article>
			<?php if($search->have_posts()): ?>
				<section class="line app-line">
            
		            <div class="line-header">
		                <div class="line-title">
		                    <h2>Resultado da Busca</h2>
		                </div>
		                
		            </div>
		            <!--Related (Search) --> 
		            <div class="related-posts">           
			            <div class="row">
				        	<?php while($search->have_posts()): $search->the_post(); ?>
				        	<div class="col-md-3">
					            <div class="item app-market">
					                <div class="item-content app-content">
					                    <a href="<?php the_permalink(); ?>">
					                         <?php $image = get_field('icone') ?> 
					                            <?php if($image): ?>  
					                            <div class="material-icons">  
					                                <img src="<?php echo $image['url'] ?>" class="style-svg" /> 
					                            </div>     
					                        <?php endif; ?>
					                        <div class="item-text app-text">
					                            <h3><?php the_title(); ?></h3>
					                            <?php the_excerpt(); ?>
					                        </div>                                
					                        <div class="app-pricing">                                   
					                            <h6 class="app-price"><strong>R$ <?php the_field('valor_mensal'); ?></strong> <?php the_field('etiqueta_mensal'); ?></h6> 
					                            <h6 class="app-price"><strong>R$ <?php the_field('valor_anual'); ?></strong> <?php the_field('etiqueta_anual'); ?></h6>                                      
					                        </div>
					                        
					                        <button class="app-button">
					                            Quero Contratar
					                        </button>
					                    </a>
					                </div>
					            </div>
					        </div>
				            <?php endwhile; ?>
			            </div>
			        </div>
		        </section>
			<?php else: ?>

				<div class="empty-content">
					<p>Não houve resultados para a sua pesquisa.</p>
				</div>
				<br>
				<br>
				<br>
				
                <a href="<?php bloginfo('home'); ?>/app-market" class="btn small">
                    
                    voltar

                </a>
	            
	            <br><br><br><br><br>
				
			<?php endif;  wp_reset_postdata(); ?>	
			</div>
		</div>		
	</div>
<?php get_footer(); ?>
