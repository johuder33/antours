<?php
    global $about, $services, $home;
    $namespace = null;
    $banners = array();

    if (is_post_type_archive($about)) {
        $namespace = $about;
    }

    if (is_post_type_archive($services)) {
        $namespace = $services;
    }

    if (is_home()) {
        $namespace = $home;
    }

    if (!$namespace) {
        return;
    }

    if (!empty($namespace)) {
        $namespace = $namespace."_banner";
    }

    $args = array(
        'post_type' => $namespace,
        'posts_per_page' => -1
    );

    $query = query_posts($args);

    if (!have_posts()) {
        return;
    }

    if (have_posts()) {
        while(have_posts()) {
            the_post();
            if (has_post_thumbnail()) {
                $picture = getSourcesForBannersSizes($post);
                array_push($banners, $picture);
            }
        }
    }

    if (count($banners) <= 0) {
        return;
    }

    $bannerConstructor = new AntoursBanners($banners, null);
    
    wp_reset_query();
?>


<div class="row">
    <?php
        $bannerConstructor->render(true, true);
    ?>
</div>