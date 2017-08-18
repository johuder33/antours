<?php get_header(); ?>

    <div class="row">
        <?php get_template_part("content", "menu"); ?>

        <div class="col-xs-12">
            <?php get_template_part("content", "banners"); ?>
            <?php get_template_part("content", "reservation"); ?>
            <?php get_template_part("content", "service"); ?>
            <?php get_template_part("content", "packages"); ?>
        </div>
    </div>

<?php get_footer(); ?>