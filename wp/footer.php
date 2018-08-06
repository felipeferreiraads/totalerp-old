<?php

$file = get_field('logo_rodape', 38);
$map = get_field('endereco', 38);
$address = explode( "," , $map['address']);

?>
</div> <!-- .main -->
<footer>
    <section class="contact">
        <div class="container">
            <div class="about">
                <div class="logo-footer">
                    <?php echo file_get_contents($file); ?>
                </div>
                <div class="dados">
                    <p class="endereco"><?php echo $address[0].', '. $address[1] . $address[2]; ?> <?php the_field('complemento', 38); ?></p>
                    <p class="email"><?php the_field('email', 38); ?></p>
                    <p class="telefone">Ligue <strong><?php the_field('telefone_cab', 38); ?></strong> ou <strong><?php the_field('celular', 38); ?></strong></p>
                </div>
            </div>
            <div class="social-links">
                <a href="<?php echo get_field('facebook', 38); ?>" target="_blank"><i class="fa fa-facebook-square"></i></a>
                <a href="<?php echo get_field('linkedin', 38); ?>" target="_blank"><i class="fa fa-linkedin-square"></i></a>
                <a href="<?php echo get_field('twitter', 38); ?>" target="_blank"><i class="fa fa-twitter-square"></i></a>
                <a href="<?php echo get_field('youtube', 38); ?>" target="_blank"><i class="fa fa-youtube-play"></i></a>
				<a href="<?php echo get_field('instagram', 38); ?>" target="_blank"><i class="fa fa-instagram"></i></a>
            </div>
        </div>
    </section>
	<section class="footer-bottom">
		<div class="container">

                <?php

                require_once('wp_bootstrap_navwalker.php');

                wp_nav_menu(
                    array(
                        'menu'              => 'Top Menu',
                        'depth'             => 2,
                        'theme_location'    => 'top-menu' ,
                        'menu_class'        => 'nav navbar-nav mr-auto mt-2 mt-md-0 ',
                        'container_id'      => 'collapse',
                        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                        'walker'            => new bs4Navwalker()
                    )
                );
                ?>

            <div class="siteby">
                <p><a href="http://www.wolsdesign.com" target="_blank">Criação de Site</a> por <a href="http://www.wolsdesign.com" target="_blank"><img src="<?php bloginfo('template_url') ?>/img/wols.png" /></a></p>
            </div>
        </div>
	</section>

</footer>
<div style="display: none" data-degustation-modal  class="tw-fixed contato tw-pin-t tw-pin-l tw-w-full tw-h-full tw-bg-black-30 tw-z-50  tw-items-center tw-justify-center">
    <div class="tw-bg-white tw-w-1/3 tw-px-4 tw-py-6 tw-relative">
        <a href="javascript://" data-close-modal class="tw-text-white tw-uppercase tw-font-bold tw-text-14 tw-absolute tw-pin-r tw-pin-t tw--mt-6">Fechar</a>
        <?php echo do_shortcode('[contact-form-7 id="3701" title="Experimente - Modal"]') ?>
    </div>
</div>


<?php if (is_page(3663)): ?>
<script src="<?php echo get_template_directory_uri() . "/js/application.min.js" ?>"></script>
<?php endif ?>
<?php wp_footer(); ?>
<script src="<?php echo get_template_directory_uri() . "/js/bundle.min.js" ?>"></script>
</body>
</html>
