<?php 

/** 

Template Name: Soluções

**/


get_header(); ?>


<div class="page solutions">  
    <?php if(have_posts()): while(have_posts()): the_post(); ?> 
    <section class="banners min clouds">
        
    </section>
    <div class="container">                 
            <h1 class="post-title" ><?php the_title(); ?></h1>
            <article class="text">                           
                <?php the_content();?>
            </article>

        </div>
    <?php endwhile; endif; ?>
    <div class="container">
        <?php 

        $_terms = get_terms( array('pacotes') ); 

        foreach ($_terms as $term) :

        $term_slug = $term->slug;
        $term_id = $term->term_id;
        $_posts = new WP_Query( array(
                    'post_type'         => 'produtos',
                    'posts_per_page'    => 20, 
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'pacotes',
                            'field'    => 'slug',
                            'terms'    => $term_slug,
                        ),
                    ),
                ));



        if($_posts->have_posts()): ?>

        <section class="line">
            
            <div class="line-header">
                <div class="line-title">
                    <h2><?php echo $term->name ?></h2>
                </div>
                
                <div class="line-pricing">
                    
                    <div class="pricing">
                        <p><?php the_field('etiqueta_mensal', 'pacotes_'.$term_id); ?></p>
                        <h6>R$ 
                            <strong class="blue-pricing"><?php the_field('valor_mensal', 'pacotes_'.$term_id); ?>
                            </strong>
                        </h6>
                    </div> 
                    <div class="pricing">
                        <p><?php the_field('etiqueta_anual', 'pacotes_'.$term_id); ?></p>
                        <h6>R$ 
                            <strong class="blue-pricing"><?php the_field('valor_anual', 'pacotes_'.$term_id); ?>
                            </strong>
                        </h6>
                    </div> 
                                       
                </div>
                
            </div>
            <!--Slick -->            
            <div class="slider-slick"> 
                               
                <div class="slick product-slider" data-slick='{"slidesToShow": 4, "slidesToScroll": 4}'>
                    <?php while($_posts->have_posts()): $_posts->the_post(); ?>
                    <div class="item">
                        <div class="item-content">
                             <?php $image = get_field('icone') ?> 
                                <?php if($image): ?>  
                                <div class="material-icons">  
                                    <img src="<?php echo $image['url'] ?>" class="style-svg" /> 
                                </div>     
                            <?php endif; ?>
                            <div class="item-text">
                                <h3><?php the_title(); ?></h3>
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <a class="slider-hire" href="<?php bloginfo('url')?>/pacotes/<?php echo $term_slug; ?>">
                    <div class="slider-bottom">
                        <p>Quero Contratar </p>
                    </div>
                </a>
            </div>
        </section>
        <?php endif; wp_reset_postdata(); endforeach; ?>       
    </div>    
</div>
<div class="overlay"></div>


<?php get_footer(); ?>