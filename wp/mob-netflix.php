<?php 

    $_terms = get_terms( array('pacotes') ); 


?>

<div class="featured mob">
    <div class="container">
        <div class="content"><?php the_content(); ?></div>


        <div class="controller" id="accordion">
        <?php foreach ($_terms as $index => $term) :        
            $term_slug = $term->slug;
            $term_name = $term->name;
            $term_id = $term->term_id;
        ?>
        

            <div class="mob-group">
                <span class="mob-group-title "  data-toggle="collapse" data-target="#group-<?php echo $term_slug ?>">
                    <?php echo $term_name;?>
                
                </span>

                <?php 
                    $_posts = new WP_Query( array(
                        'post_type'         => 'produtos',
                        'posts_per_page'    => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'pacotes',
                                'field' => 'slug',
                                'terms'    => $term_slug
                            ),
                        ),
                    ));
                ?>
                
                            
                <?php if($_posts->have_posts()): ?>  
                <div class="slides-wrapper collapse" id="group-<?php echo $term_slug ?>">  
                     
                    <div class="slides">
                    <?php while($_posts->have_posts()): $_posts->the_post(); 
                        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium', false, '' );
                        $data_id = $post->ID;
                    ?>
                        
                        <div class="flix-slide" data-id="<?php echo $data_id; ?>">
                            <div class="round" style="background-image: url(<?php echo $thumb[0]; ?>)">
                                <img class="slide-icon style-svg" src="<?php echo get_field('icone')['url'] ?>" />
                                <div class="slide-title"><?php the_title(); ?></div>
                                <div class="slide-excerpt"><?php the_excerpt(); ?></div>
                                <div class="layer"></div>
                                <div class="slide-title-label"><?php the_title(); ?></div>
                                <a href="<?php bloginfo('url'); ?>/pacotes/<?php echo $term_slug ?>"></a>
                            </div>                
                        </div>
                    <?php endwhile; ?>
                    </div>
                    
                </div>
                
               
                <?php endif; wp_reset_postdata(); ?>
            </div>      
            <?php endforeach; ?>

        
        </div>
    </div>

    
</div>
