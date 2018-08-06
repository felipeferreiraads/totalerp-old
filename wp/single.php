<?php get_header(); 

global $post;
$get_slug = $post->post_name;

if(have_posts()): while(have_posts()): the_post(); ?> 

<div class="page <?php echo $get_slug; ?>">  
    <section class="banners min clouds">
        
    </section>
    <div class="container">   

        <h1 class="post-title" ><?php the_title(); ?></h1>
        <article class="text">             
            <?php the_content();?>
            <br>
            <br>
            <a href="<?php echo bloginfo('url'); ?>/blog" class="btn small">Voltar para o Blog</a>
        </article> 

    </div> 
</div>

<?php endwhile; endif; 

get_footer(); ?>