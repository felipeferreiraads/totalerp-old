<?php 

/**
Template Name: Contador
**/

get_header(); 


if(have_posts()): 
    
    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' );     
    
?> 
<div class="single contador">  
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
                        <a href="<?php bloginfo('url') ?>/contato" class="btn">Solicitar Contato Comercial</a>             
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
                    <a href="<?php bloginfo('url') ?>/contato" class="btn">Solicitar Contato Comercial</a>
                    <a href="http://54.232.181.173/integradorContabil/" class="btn reverse" target="_blank">Acessar o Integrador Contábil</a>
                </article>       
            </div>   
        </div>
    </div>  
    <?php endwhile; ?>
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