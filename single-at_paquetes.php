<?php
    get_header();

    $content = wpautop($post->post_content);
    $args = array(
        'type'         => 'map',
        'width'        => '640px', // Map width, default is 640px. Can be '%' or 'px'
        'height'       => '480px', // Map height, default is 480px. Can be '%' or 'px'
        'zoom'         => 14,  // Map zoom, default is the value set in admin, and if it's omitted - 14
        'marker'       => true, // Display marker? Default is 'true',
    );
    $map = rwmb_meta('at_tour_map', $args);
    /*var_dump(rwmb_meta('antours_trip_price_package'));
    var_dump(rwmb_meta('antours_time_departure'));
    var_dump(rwmb_meta('antours_time_return'));
    var_dump(rwmb_meta('antours_departure_place'));
    //var_dump(rwmb_meta('antours_trip_map'));
    var_dump(rwmb_meta('antours_trip_gallery_group'));*/
    
?>

<?php get_template_part("content", "menu"); ?>
<?php get_template_part("content", "banners"); ?>

<div class="row">
    <div style="max-width: 1024px;" class="center-block">
        <h1>
            <?php the_title(); ?>
        </h1>
        <div class="text-justify">
            <?php echo $content; ?>
        </div>
        <?php
            echo $map;
        ?>
    <div>
</div>

<?php get_footer(); ?>