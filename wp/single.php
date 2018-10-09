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
            <div class="grid-search">
                <?php
                $prev_post = get_previous_post();
                if (!empty( $prev_post )): ?>
                <a href="<?php echo $prev_post->guid ?>" class="btn small">Artigo anterior</a>
                <?php endif;
                $next_post = get_next_post();
                if (!empty( $next_post )): ?>
                <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="btn small">Próximo artigo</a>
                <?php endif; ?>
            </div>
        </article>

    </div>
</div>

<?php endwhile; endif;

get_footer(); ?>