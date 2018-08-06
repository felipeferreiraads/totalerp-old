<?php if (!wp_is_mobile() ) : ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.10&appId=194657723936406";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<aside class="sidebar" id="sidebar">
	<div class="fb-page" data-href="https://www.facebook.com/madeiraeconstrucao/" data-tabs="timeline" data-width="270" data-height="500" data-small-header="false" data-adapt-container-width="true" data-hi<de-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/madeiraeconstrucao/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/madeiraeconstrucao/">Madeira e Construção</a></blockquote></div>

	<?php get_template_part('sidebar-leads'); ?>
</aside>
<?php else: ?>

<aside class="sidebar" id="sidebar">	
	<?php get_template_part('sidebar-leads'); ?>
</aside>

<?php endif; ?>