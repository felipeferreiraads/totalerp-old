<?php get_header(); ?>
<div class="page <?php echo $get_slug; ?>">  
    <section class="banners">        
        <div class="container">
            <div class="page-title">
                <h1>Erro 404</h1>  
            </div>
        </div>
    </section>
    <div class="container">
    	<div class="row">
    		<div class="col-xs-12">
				<article class="text">  
	        		<h2 class="title">Conteúdo indisponível</h2>
	        		<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fa fa-angle-left"></i> Voltar para home</a></p>
        		</article>
        	</div>	
        </div>        
    </div>    
</div>
<?php get_footer(); ?>