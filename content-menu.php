<?php

global $languages;
$logo_url = loadAssetFromResourceDirectory("images", "antours-logo.png");
$site_url = get_site_url();

?>

<header class="container">
    <nav class="row menu">
        <div class="menu-column-boundary">
            <a href="<?php echo $site_url; ?>">
                <img src="<?php echo $logo_url; ?>"/>
            </a>
        </div>
        <div class="menu-column-middle">
            <ul class="list-unstyled antours-menu-list">
                <li class="text-uppercase">
                    <a href="<?php echo bloginfo('url') ?>/<?php echo $aboutUsURL; ?>" class="menu-link" data-href="about">
                        Nosotros
                    </a>
                </li>
                <li class="text-uppercase">
                    <a href="#packages" class="menu-link" data-scrollable="true">
                        Paquetes
                    </a>
                </li>
                <li class="text-uppercase">
                    <a href="<?php echo bloginfo('url') ?>/<?php echo $servicesUsURL; ?>" class="menu-link" data-href="service">
                        Servicios
                    </a>
                </li>
                <li class="text-uppercase">
                    <a href="#contact" class="menu-link" data-scrollable="true">
                        Contacto
                    </a>
                </li>
            </ul>
        </div>
        <div class="menu-column-boundary">
            <?php pll_the_languages(); ?>
            <?php //var_dump(pll_the_languages(array('dropdown' => 1))); ?>
            <?php
            /*    $languages = pll_the_languages(array('raw' => 1, 'show_names' => 1));
                foreach($languages as $lang => $args) {
                    var_dump(pll_get_term_translations(44));
                    //var_dump($args);
                    ?>
                    <a href="<?php echo $args['url'] ?>">
                        <?php echo $args['name']; ?>
                    </a>
                    <?php
                }
            */?>
        </div>
    </nav>
</header>