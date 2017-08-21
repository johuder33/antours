<?php

global $languages, $about, $services, $packages;
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
                    <a href="<?php echo bloginfo('url') ?>/<?php echo $about; ?>" class="menu-link" data-href="about">
                        Nosotros
                    </a>
                </li>
                <li class="text-uppercase">
                    <a href="#packages" class="menu-link" data-scrollable="true">
                        Paquetes
                    </a>
                </li>
                <li class="text-uppercase">
                    <a href="<?php echo bloginfo('url') ?>/<?php echo $services; ?>" class="menu-link" data-href="service">
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
            <ul class="list-unstyled list-inline">
                <?php pll_the_languages(array('hide_current' => 1, 'show_flags' => 1)); ?>
            </ul>
        </div>
    </nav>
</header>