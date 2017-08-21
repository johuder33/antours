<?php

global $packageFeaturedImage, $post;

// custom id for UI
$postID = "package-".$post->ID;
// post title
$post_title = $post->post_title;
// link
$permalink = get_the_permalink($post);
//thumbnail url
$image_url = has_post_thumbnail() ? get_the_post_thumbnail_url($post->ID, $packageFeaturedImage) : "http://www.visituganda.com/uploads/noimage.png";

?>

<div class="package-detail col-xs-4">
    <div class="wrapper-package">
        <figure>
            <a href="<?php echo $permalink; ?>">
                <img src="<?php echo $image_url; ?>" class="img-responsive" />
            </a>
        </figure>
        <div class="detail-container">
            <div class="detail-note">
                <div class="title">
                    <span>
                        <?php echo $post_title; ?>
                    </span>
                </div>

                <div class="action">
                    <button data-id="<?php echo $postID; ?>" type="button" class="btn btn-default text-uppercase btn-reserve">Reservar</button>
                </div>
            </div>
        </div>
        <?php
            get_template_part('content', 'quick-form');
        ?>
    </div>
</div>