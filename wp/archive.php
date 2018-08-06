<?php get_header(); 

$post_type = get_queried_object()->name;
$archive_title = get_queried_object()->label;


?>

<section class="banners min clouds">

		
	</section>

	


<div class="blog" id="blog-list">

	<div class="container">
		<h1 class="post-title"><?php echo $post_type; ?></h1>

		<?php if(have_posts()):  ?>

		<div class="row">

		
			<?php while(have_posts()): the_post(); 

				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' ); 	

			?>

			<div class="col-md-4 col-sm-2">

				<article class="item">	

					<a href="<?php the_permalink(); ?>">		

						<div class="image" 
						<?php if($thumb) echo 'style="background-image:url('.$thumb[0].')"' ?>></div>   	

					  	<div class="box">

					  		<h3 class="title"><?php the_title(); ?></h3>
					  		<div class="text"><?php the_excerpt(); ?></div>

					  	</div>

					</a>

				</article>	

				</div>

			<?php  endwhile;  ?>	

		</div>

		<div class="pagination">

			<?php wp_pagenavi(); ?>

		</div>			

	</div>

</div>

<?php  else: ?> 

<div class="noticias" id="blog-list">

	<div class="container">

		<div class="row">

			<div class="col-xs-12">	

				<p class="title">Não há conteúdo publicado. </p>

			</div>

		</div>

	</div>

</div>



<?php endif;  ?>


<?php get_footer(); ?>