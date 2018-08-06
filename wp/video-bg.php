<div class="video-background">
    <div class="video-foreground" id="tv">
    	
    	<?php
    	// get iframe HTML
		$iframe = get_field('video');


		// use preg_match to find iframe src
		preg_match('/src="(.+?)"/', $iframe, $matches);
		$src = $matches[1];

		// use preg_match to find iframe ID
		preg_match('/d\/(\w+)\?feature=\D+"/', $iframe, $youtubeID);
		$vidID = $youtubeID[1];

		

		// add extra params to iframe src
		$params = array(
		    'controls'    => 0,
		    'hd'        => 1,
		    'autohide'    => 1,
		    'autoplay' => 1,
		    'showinfo' => 0,
		    'loop' => 1,
		    'playlist' => $vidID,
		    'enablejsapi' => 1,
		    'mute' => 1,
		    'rel' => 0
		);

		$new_src = add_query_arg($params, $src);

		$iframe = str_replace($src, $new_src, $iframe);


		// add extra attributes to iframe html
		$attributes = 'frameborder="0" id="player"';

		$iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);


		// echo $iframe
		echo $iframe;

		$images = get_field('slider_topo');

		$image = $images[0];
		?>
    	
    </div>

    <div id="loader" class="loader" style="background-image:url(<?php echo $image['sizes']['large']; ?>)">
    	<img src="<?php bloginfo('template_url'); ?>/img/rolling.svg" />
    	<?php /*<span id="playVideo" class="playframe">
    		<i  class="fa fa-play-circle-o"></i>
    	</span>*/ ?>
    	<section class="lettering">
    		<div>
        		<h2><?php the_field('texto_topo'); ?><br>
        		<?php the_field( 'texto_rotativo_shortcode' ) ?></h2>
        		<!--<a href="<?php bloginfo('url'); ?>/contato" class="btn">Contrate Agora</a>-->
        	</div>
    	</section>
    </div>
</div>