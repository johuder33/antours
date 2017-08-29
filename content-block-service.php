<?php
global $serviceImageLabel;
$attachment = get_the_post_thumbnail_url($post, $serviceImageLabel);
$content = get_the_content();
$thumbnailID = get_post_thumbnail_id();

$sizes = array();
foreach(get_intermediate_image_sizes() as $size) {
    $size = wp_get_attachment_image_src($thumbnailID, $size);
    array_push($sizes, $size);
}

//var_dump($sizes);

?>

<div class="row overlay-border text-center">
    <div class="flex-overlay">
        <?php
            if ($attachment) {
                ?>
                    <picture>
                        <source media="(max-width: 385px)" srcset="<?php echo $sizes[4][0]; ?>">

                        <source media="(max-width: 420px)" srcset="<?php echo $sizes[0][0]; ?>">

                        <img src="<?php echo $attachment; ?>" class="center-block img-responsive img-service" />
                    </picture>
                <?php
            }
        ?>
        <a class="flex-overlay-container"  href="<?php the_permalink();?>">
            <div class="orange-overlay"></div>
            <h1 class="overlay-title openSans">
                <?php
                    echo $post->post_title;
                ?>
            </h1>
        </a>
    </div>

    <div class="content-service">
        <?php echo $content; ?>
    </div>
</div>