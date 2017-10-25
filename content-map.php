<?php
    
    global $metabox_prefix;

    $args = array(
        'type'         => 'map',
        'height'       => '300px',
        'js_options'   => array(
            'mapTypeId'   => 'ROADMAP',
            'zoomControl' => true
        )
    );

    $map = rwmb_meta($metabox_prefix.'trip_map', $args);

?>

<div class="map-container">
    <div class="map-wrapper">
        <h1 class="page-header page-header-package">Mapa</h1>
        <?php
            echo $map;
        ?>
    </div>
</div>