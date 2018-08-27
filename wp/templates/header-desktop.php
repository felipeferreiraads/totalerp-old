<header class="tw-absolute tw-w-full tw-z-50 tw-subpixel-antialiased header-desktop">
    <div class="tw-w-full tw-bg-header">
        <div class="container">
            <ul class="tw-list-reset tw-text-14 tw-flex tw-items-center tw-justify-end tw-h-10">
                <?php foreach (get_field('top_links', 'options') as $key => $item): ?>
                    <li class="tw-flex tw-items-center <?php echo count(get_field('top_links', 'options')) == ($key + 1) ? '' : 'tw-mr-10' ?>">
                        <?php if ($item['is_button']): ?>
                            <a data-trigger-modal href="javascript://" class="tw-px-4 tw-py-1 tw-header tw-rounded-lg tw-block tw-font-bold tw-tracking-wide tw-bg-blue">
                               <span class="tw-text-white "><?php echo $item['link']['title'] ?></span>
                           </a>
                        <?php else: ?>
                        <span class="fa <?php echo $item['icon'] ?> tw-text-20 tw-text-white"></span>
                        <a href="<?php echo $item['link']['url'] ?>" class="tw-text-white tw-ml-2">
                            <?php if ($item['title']): ?>
                                <span class="tw-text-grey-white tw-font-normal tw-mr-2"><?php echo $item['title'] ?></span>
                            <?php endif ?>
                            <strong class="tw-text-white tw-font-extrabold"><?php echo $item['link']['title'] ?></strong>
                        </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="tw-flex tw--mx-4">
            <a class="tw-p-4" href="<?php echo home_url() ?>">
                <img src="http://totalerp.com.br/sitew2/wp-content/uploads/2017/09/cropped-logo-1.png">
            </a>
            <nav class="tw-flex tw-justify-end tw-p-4 tw-ml-auto tw-w-3/4">
                <?php foreach (wp_get_nav_menu_items(16) as $menu): ?>
                    <?php if ($menu->menu_item_parent == 0): ?>

                        <div class="tw-mr-8 menu-item <?php echo get_field('has_children', $menu) ? 'group' : '' ?>">
                            <a href="<?php echo $menu->url ?>">
                                <span class="tw-text-white tw-font-bold tw-tracking-wide tw-text-16"><?php echo $menu->title ?></span>
                                <?php if (get_field('has_children', $menu)): ?>
                                    <i class="fa fa-chevron-down"></i>
                                <?php endif ?>
                            </a>
                            <?php if (get_field('has_children', $menu)): ?>
                                <div class="tw-absolute   tw-hidden tw-pt-4 group-hover:tw-block">
                                    <div class="tw-p-4 tw-bg-grey-lightest tw-rounded-lg" style="<?php  echo get_field('columns', $menu) == 2  ? 'width: 525px' : '' ?>">
                                        <nav class="tw-list-reset tw-flex tw-flex-wrap tw--mx-4">
                                            <?php foreach (wp_get_nav_menu_items(16) as $submenu): ?>
                                                <?php if ($submenu->menu_item_parent == $menu->db_id): ?>
                                                    <a href="<?php echo $submenu->url ?>" class="tw-text-black tw-p-4 tw-flex tw-items-center <?php echo get_field('columns', $menu) == 2 ? 'tw-w-1/2' : 'tw-w-full' ?> hover:tw-text-blue-light">
                                                        <img class="tw-w-12" src="<?php echo get_field('icon', $submenu) ?>">
                                                        <span class="tw-leading-none tw-ml-4">
                                                            <strong class="tw-text-16 tw-block tw-font-extrabold"><?php echo $submenu->title ?></strong>
                                                            <span class="tw-text-12"><?php echo get_field('descricao_menu', $submenu) ?></span>
                                                        </span>
                                                    </a>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </nav>
                                    </div>
                                </div>
                            <?php endif ?>

                        </div>
                    <?php endif ?>
                <?php endforeach ?>

                <div class="tw-flex tw-items-center">
                    <a href="http://app.totalerp.com.br/" class="tw-bg-blue tw-text-12 tw-flex tw-items-center tw-px-4 tw-py-3 tw-rounded-full  tw-rounded-br-full ">
                        <img src="<?php echo get_template_directory_uri();?>/img/logo.svg" alt="Entrar" style="height: 18px">
                        <span class="tw-text-white tw-ml-2">ENTRAR</span>
                    </a>
                </div>
            </nav>

        </div>
    </div>
</header>
