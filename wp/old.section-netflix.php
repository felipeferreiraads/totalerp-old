<?php 

    $_terms = get_terms( array('pacotes') ); 


?>

<div class="featured desk">
    <div class="container">
        <div class="content"><?php the_content(); ?></div>


        <div class="controller hidden-md-down">
        <?php foreach ($_terms as $term) :        
            $term_slug = $term->slug;
            $term_name = $term->name;
            $term_id = $term->term_id;
        ?>

            <div class="group-title" data-category="<?php echo $term_slug ?>">
                <?php echo $term_name;?>
            </div>

        <?php endforeach; ?>
        </div>

        <div class="controller hidden-lg-up links-only">
        <?php foreach ($_terms as $term) :        
            $term_slug = $term->slug;
            $term_name = $term->name;
            $term_id = $term->term_id;
        ?>

            <div class="group-title" data-category="<?php echo $term_slug ?>">
                <a href="<?php bloginfo('url'); ?>/pacotes/<?php echo $term_slug ?>"><?php echo $term_name;?></a>
            </div>

        <?php endforeach; ?>
        </div>
    </div>

    <?php 
        $_posts = new WP_Query( array(
            'post_type'         => 'produtos',
            'posts_per_page'    => -1
        ));
    ?>
    
    
    <?php if($_posts->have_posts()): ?>  
    <div class="slides-wrapper hidden-md-down">  
        <div class="container">    
            <div class="slides">
            <?php while($_posts->have_posts()): $_posts->the_post(); 
                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium', false, '' );
                $termos = wp_get_post_terms($post->ID, 'pacotes', array('fields' => 'slugs'));
                $data_id = $post->ID;
            ?>
                
                <div class="flix-slide <?php //recebe as categorias associadas ao produto
                    foreach ($termos as $termo => $i) {
                    echo $i.' ';
                    } 
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