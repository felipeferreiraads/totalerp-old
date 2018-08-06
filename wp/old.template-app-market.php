<?php 

/** 

Template Name: App Market

**/


get_header(); ?>


<div class="page">  
    <?php if(have_posts()): while(have_posts()): the_post();  ?> 
    <section class="banners min clouds">
        
    </section>
    
    <div class="container">                 
        <h1 class="post-title" ><?php the_title(); ?></h1>
    	<article class="text">                           
            <?php the_content();
            get_search_form();?>
        </article>

    </div>
    
    <?php endwhile; endif; ?>
    <div class="container">
        <?php 

        $_terms = get_terms( array('categorias-do-produto') ); 

        foreach ($_terms as $term) :

        $term_slug = $term->slug;
        $term_id = $term->term_id;
        $_posts = new WP_Query( array(
                    'post_type'         => 'produtos',
                    'posts_per_page'    => 20, 
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'categorias-do-produto',
                            'field'    => 'slug',
                            'terms'    => $term_slug,
                        ),
                    ),
                ));



        if($_posts->have_posts()): ?>

        <section class="line app-line">
            
            <div class="line-header">
                <div class="line-title">
                    <h2><?php echo $term->name ?></h2>
                </div>
                
            </div>
            <!--Slick -->            
            <div class="slider-slick"> 
                
                
                <div class="slick product-slider" id="solutions-slider" >
                    <?php while($_posts->have_posts()): $_posts->the_post(); ?>
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
                    <?php endwhile; ?>
                </div>
                
                
            </div>
        </section>
        <?php endif; wp_reset_postdata(); endforeach; ?>       
    </div>    
</div>
<div class="overlay"></div>


<?php get_footer(); ?>