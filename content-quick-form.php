<?php

// custom id for UI
$postID = "package-".$post->ID;
// post title
$post_title = $post->post_title;

?>

<div class="quick-form" id="<?php echo $postID; ?>">
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
                <div class="title btn-close-quick-form" data-id="<?php echo $postID; ?>">
                    <span class="glyphicon glyphicon-remove">
                    </span>
                    <span>
                        <?php echo $post_title; ?>
                    </span>
                </div>

                <div class="action">
                    <button type="button" class="btn btn-default text-uppercase btn-makeReserve">Enviar</button>
                </div>
            </div>
        </div>
    </div>
</div>