<?php get_header(); 


if(have_posts()): 
    
    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' );     
    
?> 
<div class="single">  
    <?php while(have_posts()): the_post(); ?>
    <section class="banners" style="background-image: url(<?php echo $thumb[0]; ?>)">
        <div class="container">                        
            <?php if(get_field('youtube')): ?>
                <div class="link-youtube">
                    <a href="#" data-toggle="modal" data-target="#youtube-player">
                        <i class="fa fa-play-circle-o"></i><br>
                        <span>Ver video</span>
                    </a>
                </div>
            <?php endif; ?>                      
        </div>
    </section>
    <div class="intro">
         <div class="container"> 
            <div class="row"> 
                <div class="col-lg-6">    
                    <h1 class="page-title">
                        <?php the_title(); ?>
                    </h1>      
                    <article class="text">                     
                        <?php the_content(); ?>  
                        
                        <form>
                            <div class="custom-controls-stacked">
                                <label class="custom-control custom-radio">
                                    <input id="#" name="radio-stacked" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">R$ <strong><?php the_field('valor_mensal'); ?></strong> - <?php the_field('etiqueta_mensal'); ?></span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input id="#" name="radio-stacked" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">R$ <strong><?php the_field('valor_anual'); ?></strong> - <?php the_field('etiqueta_anual'); ?></span>
                                </label>
                                <a href="<?php bloginfo('url'); ?>/contato" class="btn">Quero Contratar</a>
                            </div>
                        </form> 
                                    
                    </article>           
                </div>
                <?php $image = get_field('imagem_da_descricao') ?> 
                <?php if($image): ?>    
                <div class="col-lg-6 image-wrap"> 
                    <img src="<?php echo $image['url'] ?>" />
                </div>        
                <?php endif; ?>
            </div>   
         </div>
    </div>
    <div class="details">
        <div class="container">
            <div class="row"> 
                <article class="text col-12">   
                    <?php the_field('texto') ?>
                    <a href="<?php bloginfo('url'); ?>/contato" class="btn">Quero Contratar</a>
                </article>       
            </div>             
        </div>
    </div>  
    <?php endwhile; endif; ?>
    <?php $related = get_field('posts_relacionados'); 

    if($related): ?>
    <div class="related-posts">
         <div class="container"> 
            <div class="post-title">Conheça também</div>
            <div class="row">
                <?php                 

                foreach($related as $r_post):
                    
                    ?>
                    <div class="col-12 col-md-6 col-sm-6 col-lg-4 col-xl-3">
                        <div class="item app-market">
                            <div class="item-content app-content">
                                <a href="<?php the_permalink($r_post->ID); ?>">
                                     <?php $image = get_field('icone', $r_post->ID) ?> 
                                        <?php if($image): ?>  
                                        <div class="material-icons">  
                                            <img src="<?php echo $image['url'] ?>" class="style-svg" /> 
                                        </div>     
                                    <?php endif; ?>
                                    <div class="item-text app-text">
                                        <h3><?php echo get_the_title($r_post->ID); ?></h3>
                                        <p><?php echo get_the_excerpt($r_post->ID); ?></p>
                                    </div>                                
                                    <div class="app-pricing">                                   
                                        <h6 class="app-price"><strong>R$ <?php the_field('valor_mensal', $r_post->ID); ?></strong> <?php the_field('etiqueta_mensal', $r_post->ID); ?></h6> 
                                        <h6 class="app-price"><strong>R$ <?php the_field('valor_anual', $r_post->ID); ?></strong> <?php the_field('etiqueta_anual', $r_post->ID); ?></h6>                                      
                                    </div>
                                    
                                    <button class="app-button">
                                        Quero Contratar
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach;  wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="youtube-player" tabindex="-1" role="dialog">
  
  <div class="modal-dialog">
    <span class="closeMe" data-dismiss="modal"></span>
    <div class="modal-content">
      
      <div class="modal-body">
        <?php echo get_field('youtube'); ?>
      </div>
     
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; 

get_footer(); ?>