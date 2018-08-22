<?php get_header(); 


$object = get_queried_object();
$title = $object->name;
$term_id = $object->term_id; 
$description_1 = get_field('descricao_1', 'pacotes_' . $term_id); 
$description_2 = get_field('descricao_2', 'pacotes_' . $term_id);       
$thumb = get_field('imagem_topo', 'pacotes_' . $term_id);   
$video = get_field('youtube', 'pacotes_' . $term_id);
$image = get_field('imagem_descricao', 'pacotes_' . $term_id);  
    
?> 

<div class="single">     
    <section class="banners" style="background-image: url(<?php echo $thumb['url']; ?>)">
        <div class="container">                        
            <?php if($video): ?>
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
                        <?php echo $title; ?>
                    </h1>      
                    <article class="text">                     
                        <?php echo $description_1; ?>   
                        
                        <form action="<?php bloginfo('url'); ?>/carrinho/add" method="post" id="ctc"> 
                        	<input type="hidden" name="produto" value="<?=$term_id?>">
                        	<input type="hidden" name="t" value="1">                          
                            <div class="custom-controls-stacked">
                                <label class="custom-control custom-radio">
                                    <input id="#" name="radio-stacked" type="radio" value="1" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">R$ <strong><?php the_field('valor_mensal', 'pacotes_'.$term_id); ?></strong> - <?php the_field('etiqueta_mensal', 'pacotes_'.$term_id); ?></span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input id="#" name="radio-stacked" type="radio" value="2" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">R$ <strong><?php the_field('valor_anual', 'pacotes_'.$term_id); ?></strong> - <?php the_field('etiqueta_anual', 'pacotes_'.$term_id); ?></span>
                                </label>
                                <input type="submit" class="btn" value="Quero Contratar">
                            </div>
                        </form> 
                                    
                    </article>           
                </div>
                 
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
                    <?php echo $description_2; ?> 
                </article> 
             </div>   
            <?php if(have_posts()): ?>
            <section id="tax-products" class="products-list hidden-md-down">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-12">
                                <?php 
                                while(have_posts()): the_post();
                                    if($post->post_type != 'plan'):
                                        $i++;
                                        $class = ($i == 1) ? 'active' : '';
                                ?>
                                    
                                        <?php $icon = get_field('icone'); ?>
                                        <li class="card <?php echo $class; ?>" data-title="#<?php echo $post->post_name; ?>">
                                            <img src="<?php echo $icon['url'] ?>" class="style-svg" />
                                            <h4><?php the_title(); ?></h4>
                                        </li>
                                    
                                <?php 
                                    endif;
                                endwhile; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <?php 
                        while(have_posts()): the_post(); 
                            if($post->post_type != 'plan'):
                                $j++;
                                $active = ($j == 1) ? 'active' : '';
                        ?>
                            <article class="white <?php echo $active; ?>" id="<?php echo $post->post_name; ?>">
                                <h1 class="post-title"><?php the_title(); ?></h1>
                                <div class="text">
                                    <?php the_field('texto') ?>
                                </div>
                            </article>
                        <?php 
                            endif;
                        endwhile; ?>
                        <a href="#" class="btn" onclick="$('#ctc').submit();return false;">Quero Contratar</a>
                    </div>
                </div>
            </section>

            <?php /**** MOBILE ***/ ?>
            <div class="featured hidden-lg-up">
                <section class="controller" id="accordion">
                    <?php while(have_posts()): the_post(); ?>
                        <div class="mob-group">                        
                            <span class="mob-group-title"  data-toggle="collapse" data-target="#group-<?php echo $post->post_name ?>">
                                <?php the_title(); ?>            
                            </span>
                            <div id="group-<?php echo $post->post_name ?>" class="content-wrapper collapse">
                                <article class="white <?php echo $active; ?>" id="<?php echo $post->post_name; ?>">
                                    
                                    <div class="text">
                                        <?php the_field('texto') ?>
                                    </div>
                                </article>
                            </div>                        
                        </div> 
                    <?php endwhile?>
                </section>
            </div>      
            <?php endif; ?>              
        </div>
    </div>  
    
</div>
<div class="modal fade" id="youtube-player" tabindex="-1" role="dialog">
  
  <div class="modal-dialog">
    <span class="closeMe" data-dismiss="modal"></span>
    <div class="modal-content">
      
      <div class="modal-body">
        <?php echo $video; ?>
      </div>
     
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php get_footer(); ?>