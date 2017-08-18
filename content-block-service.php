<?php

$attachment = get_the_post_thumbnail_url($post);
$content = get_the_content();

?>

<div class="row overlay-border text-center">
    <div class="flex-overlay">
        <?php
            if ($attachment) {
                ?>
                    
                    <img src="<?php echo $attachment; ?>" class="center-block img-responsive img-service" />
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