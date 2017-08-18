<div class="service-container">

    <?php echo renderTitle("Nuestros Servicios", "Amoldados a sus necesidades", array("normal-title", "openSans", "fontLight")); ?>

    <div class="col-xs-12">
        <?php
            if (have_posts()){
                while(have_posts()){
                    the_post();
                    // render the block-service template
                    get_template_part("content", "block-service");
                }
            }
        ?>
    </div>

    <?php echo renderTitle("Paquetes Destacados", "Conoce los destinos preferidos en Chile", array("normal-title", "openSans", "fontLight")); ?>

</div>