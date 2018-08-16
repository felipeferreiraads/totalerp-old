<header class="tw-absolute tw-w-full tw-z-50 tw-subpixel-antialiased header-mobile">
    <div class="tw-w-full">
        <div class="container">
            <ul class="tw-list-reset tw-text-12 tw-flex tw-items-center tw-justify-end tw-h-10">

                <?php foreach (get_field('top_links', 'options') as $key => $item): ?>
                    <li class="tw-flex tw-items-center <?php echo count(get_field('top_links', 'options')) == ($key + 1) ? '' : 'tw-mr-10' ?>">
                    <?php if ($item['is_button']): ?>
                        <a data-trigger-modal href="javascript://" class="tw-px-2 tw-py-1 tw-bg-blue-darker tw-rounded-lg tw-block tw-font-bold tw-tracking-wide">
                            <span class="tw-text-white "><?php echo $item['link']['title'] ?></span>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>
<div class="container">
    <div class="tw-flex tw-justify-between tw-items-center">
        <a href="<?php echo home_url() ?>">
            <img class="tw-w-48" src="http://totalerp.com.br/sitew2/wp-content/uploads/2017/09/cropped-logo-1.png">
        </a>

        <div class="open-menu">
            <a href="#" class="tw-text-white">
                <i class="fa fa-bars fa-2x"></i>
            </a>
        </div>
    </div>
    <nav class="tw-bg-blue-darker tw-mt-4 tw--mx-4 tw-p-4 menu-mobile">
        <?php foreach (wp_get_nav_menu_items(16) as $menu): ?>
            <?php if ($menu->menu_item_parent == 0): ?>
                <div class="tw-my-4  group">
                    <a href="<?php echo $menu->url ?>" <?php if (get_field('has_children', $menu)) echo 'class="has-submenu"';?>>
                        <span class="tw-text-white tw-font-bold tw-tracking-wide tw-text-16"><?php echo $menu->title ?></span>
                        <?php if (get_field('has_children', $menu)): ?>
                            <i class="fa fa-chevron-down"></i>
                        <?php endif ?>
                    </a>
                    <?php if (get_field('has_children', $menu)): ?>

                        <div class="tw-pt-4 submenu">
                            <div class="tw-p-4 tw-bg-grey-lightest tw-rounded-lg">
                                <?php foreach (wp_get_nav_menu_items(16) as $submenu): ?>
                                    <?php if ($submenu->menu_item_parent == $menu->db_id): ?>

                                        <a href="<?php echo $submenu->url ?>" class="tw-text-black tw-py-3 tw-block hover:tw-text-blue-light">
                                            <strong class="tw-text-16 tw-block tw-font-extrabold"><?php echo $submenu->title ?></strong>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        <?php endforeach; ?>
        <div class="tw-flex tw-items-center">
            <a href="" class="tw-bg-blue-darker tw-flex tw-items-center tw-px-4 tw-py-3 tw-rounded-tl-full  tw-rounded-bl-full ">
                <i class="fa tw-text-white tw-text-18 fa-shopping-cart"></i>
            </a>
            <a href="" class="tw-bg-blue tw-text-12 tw-flex tw-items-center tw-px-4 tw-py-3 tw-rounded-tr-full  tw-rounded-br-full ">
                <i class="fa tw-text-white tw-text-18 fa-shopping-cart"></i>
                <span class="tw-text-white tw-ml-2">ENTRAR</span>
            </a>
        </div>
    </nav>
</div>
</header>
