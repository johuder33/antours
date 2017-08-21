<?php
    get_header();

    the_post();

    $content = wpautop(get_the_content());
?>

<?php get_template_part("content", "menu"); ?>
<?php get_template_part("content", "banners"); ?>

<div class="row">
    <div style="max-width: 1024px;" class="center-block">
        <div class="content-container">
            <h1 class="page-header page-header-package">
                <?php the_title(); ?>
            </h1>
            <div class="text-justify content">
                <?php get_template_part('content', 'information'); ?>
                <?php echo $content; ?>
            </div>
            <?php get_template_part('content', 'places'); ?>
        </div>

        <?php
            get_template_part('content', 'gallery');
        ?>

        <?php
            get_template_part('content', 'map');
        ?>
    <div>
    <?php
        // show comments if exists or can post one
        if( comments_open() || get_comments_number()) {
            comments_template();
        }
    ?>
</div>

<?php get_footer(); ?>