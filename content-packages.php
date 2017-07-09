<?php
$packages = array(
    array("image" => "http://img-aws.ehowcdn.com/400x400/ds-img.studiod.com/Cherokee_Pass2_1.jpg", "title" => "Valle Nevado"),
    array("image" => "http://myphotoslike.com/wp-content/uploads/2015/08/MG_0883_1-400x400.jpg", "title" => "Valle Colorado"),
    array("image" => "http://img1.sunset.timeinc.net/sites/default/files/styles/400xvariable/public/image/2016/05/main/secret-sierra-nevada-ca-mcgee-creek-sierra-crest-0513.jpg?itok=kk0TVGA9", "title" => "Viña del Mar"),
    array("image" => "https://www.mammothresorts.com/images/librariesprovider2/default-album/advanced-lesson-400x400.jpg?sfvrsn=1&size=400", "title" => "Papudo"),
    array("image" => "https://www.jacksonhole.com/images/callouts/about-mountain-stats.jpg", "title" => "Isla de Pascua")
);
?>
<div class="row packages">
    <div class="packages-container">
        <div class="button-container text-center">
            <div class="btn-group packages-button">
                <button type="button" class="btn btn-default btn-category active">Promociones</button>
                <button type="button" class="btn btn-default btn-category">Los Más Vendidos</button>
                <button type="button" class="btn btn-default btn-category">Los Más Lujosos</button>
            </div>
        </div>

        <div class="row each-package">
            <?php
                $i = 0;
                foreach($packages as $package) {
                    $i++;
                    ?>
                        <div class="package-detail col-xs-4">
                            <div class="wrapper-package">
                                <figure>
                                    <img src="<?php echo $package["image"]; ?>" class="img-responsive" />
                                </figure>
                                <div class="detail-container">
                                    <div class="detail-note">
                                        <div class="title">
                                            <span>
                                                <?php echo $package["title"]; ?>
                                            </span>
                                        </div>

                                        <div class="action">
                                            <button data-id="i<?php echo $i; ?>" type="button" class="btn btn-default text-uppercase btn-reserve">Reservar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="quick-form" id="i<?php echo $i; ?>">
                                    <div class="quick-container">
                                        <div class="quick-form-container">
                                            <form class="form">
                                                <input class="form-control quick-field" placeholder="Nombre y Apellido" />
                                                <input class="form-control quick-field" placeholder="RUT" />
                                                <input class="form-control quick-field" placeholder="Teléfonos" />
                                                <input class="form-control quick-field" placeholder="Cantidad de pasajeros" />
                                                <input class="form-control quick-field" placeholder="Dirección del Hotel (optional)" />
                                                <input class="form-control quick-field" placeholder="Tipo de Servicio" />
                                            </form>
                                        </div>

                                        <div class="quick-control">
                                            <div class="detail-note">
                                                <div class="title btn-close-quick-form" data-id="i<?php echo $i; ?>">
                                                    <span class="glyphicon glyphicon-remove">
                                                    </span>
                                                    <span>
                                                        <?php echo $package["title"]; ?>
                                                    </span>
                                                </div>

                                                <div class="action">
                                                    <button type="button" class="btn btn-default text-uppercase btn-makeReserve">Enviar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            ?>
        </div>
    </div>
</div>