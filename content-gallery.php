<?php

$gallery = rwmb_meta('antours_trip_gallery_group');

if (count($gallery) <= 0 || empty($gallery)) {
    return;
}

?>

<div>
    <div>
        <h1 class="page-header page-header-package">
            Galeria
        </h1>
    </div>

    <div class="gallery-container text-center center-block">
        <div class="gallery-wrapper">
            <?php
                foreach($gallery as $id => $image) {
                    $thumbnail = $image['url'];
                    $full = $image['full_url'];

                    printf('<a class="gallery-thumbnail" data-fancybox="gallery" href="%s"><img src="%s"></a>', $full, $thumbnail);
                }
            ?>
        </div>
    </div>
</div>