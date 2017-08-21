<?php

global $domain;
$departure_places = rwmb_meta('antours_departure_place');

if (count($departure_places) <= 0) {
    return;
}

?>

<div class="container-schedule">
    <div class="schedule-wrapper">
        <h1 class="page-header page-header-package">
            <?php
                echo __('Meeting points', $domain);
            ?>
        </h1>

        <div>
            <?php
                foreach($departure_places as $place) {
                    ?>
                            <div>
                                <p>
                                    <i class="glyphicon glyphicon-map-marker"></i>
                                    <?php echo $place; ?>
                                </p>
                            </div>
                    <?php
                }
            ?>
        </div>
    </div>
</div>