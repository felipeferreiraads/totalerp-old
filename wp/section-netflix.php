<?php 

$_terms = get_terms( array('pacotes') ); 


?>

<div class="featured desk">
    <div class="container">
        <div class="content"><?php the_content(); ?></div>


        <div class="controller hidden-md-down">
            <?php foreach ($_terms as $index => $term) :        
            $term_slug = $term->slug;
            $term_name = $term->name;
            $term_id = $term->term_id;
            ?>

            <div class="group-title <?php echo $index === 0 ? 'active' : '' ?>" data-termid="<?php echo $term_id?>" data-category="<?php echo $term_slug ?>">
                <?php echo $term_name;?>
            </div>
            
        <?php endforeach; ?>
    </div>

    <div class="controller hidden-lg-up links-only">
        <?php foreach ($_terms as $index => $term) :        
        $term_slug = $term->slug;
        $term_name = $term->name;
        $term_id = $term->term_id;
        ?>

        <div class="group-title <?php echo $index === 0 ? 'active' : '' ?>" data-termid="<?php echo $term_id?>" data-category="<?php echo $term_slug ?>">
            <a   href="<?php bloginfo('url'); ?>/pacotes/<?php echo $term_slug ?>"><?php echo $term_name;?></a>
        </div>

    <?php endforeach; ?>
</div>
<?php foreach ($_terms as $index => $term) :        
$term_slug = $term->slug;
$term_name = $term->name;
$term_id = $term->term_id;
?>
<div data-content-category="<?php echo $term_slug ?>" style="display: none">
    <div class="tw-w-full tw-flex tw-items-center tw-justify-between">
        
     <div>
        <h4 style="color: #34384B"><?php the_field('frase_netflix', $term) ?></h4>
        <div class="tw-mt-2">
            <a class="btn reverse tw-px-4 tw-h-10 tw-inline-flex tw-leading-0 tw-items-center tw-text-18 tw-justify-center tw-w-auto tw-mr-2" href="<?php echo get_term_link($term) ?>">Contratar Agora</a>
            <a class="btn tw-px-4 tw-h-10 tw-inline-flex tw-leading-0 tw-items-center tw-text-18 tw-justify-center tw-w-auto" href="<?php echo get_the_permalinK(3663) ?>">Planos & Preços</a>
        </div>
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
</div>
<?php endforeach; ?>
</div>

<?php 
$_posts = new WP_Query( array(
    'post_type'         => 'produtos',
    'posts_per_page'    => -1
));
?>


<?php if($_posts->have_posts()): ?> 

    <input type="hidden" id="selectedTermID" value="0"> 
    <div class="slides-wrapper hidden-md-down">  
        <div class="container">    
            <div class="slides">

                <?php while($_posts->have_posts()): $_posts->the_post(); 
                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium', false, '' );
                $termos = wp_get_post_terms($post->ID, 'pacotes', array('fields' => "slugs")); 
                $data_id = $post->ID;
                ?>
                
                <div class="flix-slide <?php //recebe as categorias associadas ao produto
                foreach ($termos as $termo => $i) {echo $i.' ';} 
                ?>" 
                data-id="<?php echo $data_id; ?>">
                <div class="round" style="background-image: url(<?php echo $thumb[0]; ?>)">
                    <img class="slide-icon style-svg" src="<?php echo get_field('icone')['url'] ?>" />
                    <div class="slide-title"><?php the_title(); ?></div>
                    <div class="slide-excerpt"><?php the_excerpt(); ?></div>
                    <i class="fa fa-angle-down"></i>                   
                    <div class="layer"></div>
                    <div class="slide-title-label"><?php the_title(); ?></div>

                </div>                
            </div>
        <?php endwhile; ?>
    </div>
</div>
</div>


<?php endif; wp_reset_postdata(); ?>

<div class="container-fluid">
    <div class="panel" id="blue-panel"></div>
    <img src="<?php bloginfo('template_url'); ?>/img/rolling.svg" class="loader" />
</div>
</div>
