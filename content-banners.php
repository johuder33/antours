<?php
    /*global $about, $services, $banners, $packages;
    $src = "http://efy.global/img/santiago.jpg";
    $carousel = null;
    $metaboxSlug = "antours_banners_";

    if(!is_singular($packages)) {
        if (is_post_type_archive($services)) {
            $metaboxSlug .= "services";
        }

        if (is_post_type_archive($about)) {
            $metaboxSlug .= "about";
        }

        if (is_home()) {
            $metaboxSlug .= "home";
        }

        $re = query_posts(array(
            'post_type' => $banners,
            'posts_per_page' => 1,
            'order' => 'ASC'
        ));

        if (have_posts()) {
            while(have_posts()) {
                $carousel = rwmb_meta($metaboxSlug, $post->ID);
            }
        }

        if (!$carousel) {
            return;
        }

        $bannerConstructor = new AntoursBanners($carousel, null, array('img-responsive'));
    }

    if (is_singular($packages)) {
        $image = get_the_post_thumbnail_url($post, 'full');
    }*/
?>

<div class="row">
    <?php
        /*if ($bannerConstructor) {
            $bannerConstructor->render(true, true);
        } else {
            if ($image) {
                ?>
                    <img src="<?php echo $image; ?>" class="center-block img-resposive" />
                <?php
            }
        }
    */?>
</div>