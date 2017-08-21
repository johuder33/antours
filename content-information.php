<?php

$price = rwmb_meta('antours_trip_price_package');
$time_to_departure = rwmb_meta('antours_time_departure');
$time_to_return = rwmb_meta('antours_time_return');

?>

<div class="price-container">
    <div class="price-wrapper">
        <ul class="list-unstyled options-list">
            <?php
                if($price) {
                    ?>
            <li>
                <div class="info-block">
                    <span class="icon">
                        <i class="glyphicon glyphicon-tags defaultColor"></i>
                    </span>
                    
                    <span>
                       <strong class="icon-label icon-label-space">
                            Precio : 
                       </strong>
                       <span class="tag-value">
                            <?php echo $price; ?>
                       <span>
                    </span>
                </div>
            </li>
                    <?php
                }
            ?>

            <?php
                if($time_to_departure || $time_to_return) {
                    ?>
            <li>
                <div class="info-block">
                    <label>
                        <span class="icon">
                            <i class="glyphicon glyphicon-time defaultColor"></i>
                        </span>

                        <span class="icon-label">
                            Horario
                        </span>

                        <div class="options-block">
                            <?php
                                if($time_to_departure) {
                                    ?>
                            <span class="option">
                                <i class="glyphicon glyphicon-ok defaultColor"></i>
                                <span class="icon-label">
                                    Salida :
                                </span>
                                <span>
                                    <?php echo $time_to_departure; ?>
                                </span>
                            </span>
                                    <?php
                                }
                            ?>

                            <?php
                                if($time_to_departure) {
                                    ?>
                            <span class="option">
                                <i class="glyphicon glyphicon-ok defaultColor"></i>
                                <span class="icon-label">
                                    Regreso :
                                </span>
                                <span>
                                    <?php echo $time_to_return; ?>
                                </span>
                            </span>
                                    <?php
                                }
                            ?>
                        </div>
                    </label>
                </div>
            </li>
                    <?php
                }
            ?>
        </ul>
        <!--<span class="price-label text-center openSans">
            Precio
        </span>

        <span class="price openSans text-center">
            <?php echo $price; ?>
        </span>-->
    </div>

    <div class="btn-request-package">
        <button class="btn btn-default btn-package center-block">
            Lo quiero!
        </button>
    </div>
</div>