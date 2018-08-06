<?php $packages = get_terms(['pacotes']); ?>

    <section class="packages featured">
        <div class="content">
            <?php the_content(); ?>
        </div>

        <nav class="nav-packages">
            <div class="container">
                <div class="grid">
                    <?php
                        foreach($packages as $k => $p):
                            $selected = $k === 0 ? 'selected' : '';
                            echo '<a href="#'.$p->slug.'" class="'.$selected.'">'.$p->name.'</a>';
                        endforeach;
                    ?>
                </div>
            </div>
        </nav>

        <div class="packages-select">
            <select>
                <?php
                    foreach($packages as $k => $p):
                        echo '<option value="#'.$p->slug.'">'.$p->name.'</option>';
                    endforeach;
                ?>
            </select>
        </div>

        <?php foreach($packages as $k => $p): ?>
        <div class="package" id="<?php echo $p->slug;?>" style="background-image: url(<?php the_field('imagem_topo', $p); ?>">
            <div class="overlay-package">
                <div class="container">
                    <h2><?php echo $p->name; ?></h2>
                    <div class="package-value">
                        <div class="left">
                            <span class="type">Mensal</span>
                            <span class="value">R$ <i><?php the_field('valor_mensal', 'pacotes_'.$p->term_id); ?></i></span>
                        </div>
                        <div class="right">
                            <span class="type">Fidelidade (10% OFF)*</span>
                            <span class="value">R$ <i><?php the_field('valor_anual', 'pacotes_'.$p->term_id); ?></i></span>
                        </div>
                    </div>
                    <?php
                        echo apply_filters( 'the_content', $p->description );
                        $modules = new WP_Query([
                            'post_type' => 'produtos',
                            'tax_query' => [
                                [
                                    'taxonomy' => 'pacotes',
                                    'field' => 'slug',
                                    'terms' => $p->slug
                                ]
                            ]
                        ]);
                    ?>
                    <h4>Módulos disponíveis no pacote:</h4>
                    <ul class="module-list">
                    <?php while($modules->have_posts()) : $modules->the_post();?>
                        <li>
                            <img src="<?php echo get_field('icone')['url'] ?>" alt="<?php echo get_the_title(); ?>">
                            <?php the_title('<h5>', '</h5>'); ?>
                            <div class="tooltip">
                                Pariatur ullamco tempor reprehenderit eu veniam officia officia ipsum sint. Id nisi labore labore occaecat exercitation.
                            </div>
                        </li>
                    <?php endwhile; ?>
                    </ul>
                </div>
                <div class="buttons">
                    <a href="<?php echo site_url('/pacotes/'.$p->slug);?>">Quero contratar</a>
                    <a href="<?php echo site_url('/pacotes/'.$p->slug);?>">Saiba mais</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </section>