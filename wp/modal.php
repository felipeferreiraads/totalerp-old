<div class="modal fade" id="youtube-player" tabindex="-1" role="dialog">
  
  <div class="modal-dialog">
  	<span class="closeMe" data-dismiss="modal"></span>
    <div class="modal-content">
      
      <div class="modal-body">
        <?php
    	// get iframe HTML
		$iframe = get_field('video_modulares', 104);


		// use preg_match to find iframe src
		preg_match('/src="(.+?)"/', $iframe, $matches);
		$src = $matches[1];

		// use preg_match to find iframe ID
		preg_match('/d\/(\w+)\?feature=\D+"/', $iframe, $youtubeID);
		$vidID = $youtubeID[1];

		

		// add extra params to iframe src
		$params = array(
		    'controls'    => 1,
		    'hd'        => 1,
		    'autohide'    => 1,
		    'autoplay' => 0,
		    'showinfo' => 0,
		    'loop' => 0,
		    'playlist' => $vidID,
		    'enablejsapi' => 1,
		    'rel' => 0
		);

		$new_src = add_query_arg($params, $src);

		$iframe = str_replace($src, $new_src, $iframe);


		// add extra attributes to iframe html
		$attributes = 'frameborder="0" id="video-modulares"';

		$iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);


		// echo $iframe
		echo $iframe;

		$images = get_field('slider_topo');

		$image = $images[0];
		?>
      </div>
     
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


