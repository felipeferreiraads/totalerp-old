<?php get_header(); 

global $post;
$get_slug = $post->post_name;

if(have_posts()): while(have_posts()): the_post();
    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' ); 
?> 
<div class="page <?php echo $get_slug; ?>">  
<?php if(has_post_thumbnail()): ?>
    <section class="banners" style="background-image: url(<?php echo $thumb[0]; ?>)">
        
    </section>
<?php else: ?>
	<section class="banners min clouds">
        
    </section>
<?php endif; ?>
    <div class="container">   

        <h1 class="post-title" ><?php the_title(); ?></h1>
        <article class="text">             
            <?php the_content();?>
        </article>       
    </div> 
</div>
<div class="overlay"></div>
<?php endwhile; endif; 

get_footer(); ?>