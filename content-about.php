<?php

the_post();

$content = get_the_content();
$content = wpautop($content);

?>

<div class="row">
    <div class="col-xs-12">
        <div class="about-container center-block">
            <?php echo renderTitle(get_the_title(), "Transladandolo hacia sus sueños", array("about-title", "openSans", "fontLight")); ?>
            <div class="about-content-container">
                <div class="container-with-tables">
                    <?php echo $content; ?>
                </div>
            </div>

            <?php echo renderTitle("Paquetes destacados", "Conoce los destinos preferidos en Chile", array("normal-title", "openSans", "fontLight")); ?>
        </div>
    </div>
</div>