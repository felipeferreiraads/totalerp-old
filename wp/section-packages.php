<?php $packages = get_terms(['pacotes']); ?>

    <section class="packages featured">
        <div class="content">
            <?php the_content(); ?>
        </div>

        <div class="container">
            <nav class="nav-packages">
                <div class="grid">
                    <?php
                        foreach($packages as $k => $p):
                            $selected = $k === 0 ? 'selected' : '';
                            echo '<a href="#'.$p->slug.'" class="'.$selected.'">'.$p->name.'</a>';
                        endforeach;
                    ?>
                </div>
            </nav>
        </div>

        <div class="container">
            <div class="packages-select">
                <select>
                    <?php
                        foreach($packages as $k => $p):
                            echo '<option value="#'.$p->slug.'">'.$p->name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
        </div>

        <?php foreach($packages as $k => $p): ?>
        <div class="package" id="<?php echo $p->slug;?>" style="background-image: url(<?php the_field('imagem_topo', $p); ?>">
            <div class="container">
                <div class="grid">
                    <div class="info">
                        <h2><?php echo $p->name; ?></h2>
                        <?php
                            echo apply_filters( 'the_content', $p->description );
                            $modules = new WP_Query([
                                'post_type' => 'produtos',
                                'post__in'   => [197, 201, 204, 208, 226, 228, 2518, 2526, 2528, 2545, 188, 2713, 230, 2514, 2520, 2522, 2524],
                                'orderby'   => 'post__in',
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
                                    <?php the_field('tooltip_home'); ?>
                                </div>
                            </li>
                        <?php endwhile; ?>
                        </ul>
                    </div>
                    <div class="image">
                        <img src="<?php the_field('imagem_home', 'pacotes_'.$p->term_id);?>" alt="Totalerp">
                    </div>
                </div>
                <div class="buttons">
                    <a data-trigger-modal href="javascript://">Quero testar</a>
                    <a href="<?php echo home_url('/pacotes/'.$p->slug);?>">Saiba mais</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </section>