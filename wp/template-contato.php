<?php 

/**
Template Name: Contato
**/


get_header(); 

global $post;
$get_slug = $post->post_name;
$endereco = get_field('endereco', 38); 
$address = explode( "," , $endereco['address']);

if(have_posts()): while(have_posts()): the_post();
   $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' ); 
?> 
<div class="page <?php echo $get_slug; ?>">  
    <section class="banners" style="background-image: url(<?php echo $thumb[0]; ?>)">
        
    </section>
    <div class="container">
        <article class="text">   
            <div class="row">
                <div class="col-12">
                    <h1 class="post-title"><?php the_title(); ?></h1>                
                    <?php the_content();?>               
                </div>
                <div class="col-md-8">                
                    
                    <?php echo do_shortcode('[contact-form-7 id="136" title="Contato"]');?>              
                </div>
                <div class="col-md-4">
                    <aside class="sidebar">
                        <div class="title">Endereço</div>
                        <i class="fa fa-home"></i><p class="endereco"><?php echo $address[0].', '. $address[1] . $address[2]; ?> <?php the_field('complemento', 38); ?><br>
                            <?php the_field('cidade', 38); ?> - <?php the_field('cep', 38); ?></p>
                        <div class="title">Ligue-nos</div>
                        <p class="telefone"> <i class="fa fa-phone"></i> Comercial:  <strong><?php the_field('telefone_1', 38); ?></strong> </p>
                        <p class="telefone"> <img src="<?php bloginfo('template_url'); ?>/img/icon_suporte.svg" class="style-svg" /> Suporte: <strong><?php the_field('telefone_2', 38); ?></strong> </p>
                        <div class="title">Email</div>
                        <i class="fa fa-envelope"></i><p class="email"><?php the_field('email', 38); ?></p>
                    </aside>
                </div>
            </div>
        </article>
    </div>
    <?php get_template_part('mapa'); ?>
</div>
<?php endwhile; endif; ?>


<?php get_footer(); ?>