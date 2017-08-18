<?php get_header(); ?>

    <div class="row">
        <?php get_template_part("content", "menu"); ?>

        <div class="col-xs-12">
            <?php get_template_part("content", "banners"); ?>
            <?php get_template_part("content", "reservation"); ?>
            <div class="row" style="margin: 30px 0;">
                <?php echo renderTitle("Paquetes destacados", "Conoce los destinos preferidos en Chile", array("normal-title", "openSans", "fontLight")); ?>
            </div>
            <?php get_template_part("content", "packages"); ?>
        </div>
    </div>

<?php get_footer(); ?>