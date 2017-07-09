<?php
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
                    <a href="#">
                        Nosotros
                    </a>
                </li>
                <li class="text-uppercase">
                    <a href="#">
                        Paquetes
                    </a>
                </li>
                <li class="text-uppercase">
                    <a href="#">
                        Servicios
                    </a>
                </li>
                <li class="text-uppercase">
                    <a href="#">
                        Contacto
                    </a>
                </li>
            </ul>
        </div>
        <div class="menu-column-boundary">
        </div>
    </nav>
</header>