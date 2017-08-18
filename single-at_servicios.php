<?php
get_header();

$category = get_the_terms($post->ID, 'antours-category');
$hasCategory = count($category) > 0 ? true : false;

var_dump(rwmb_meta('antours_departure_place', $post->ID));

?>

<div class="row">
    <?php get_template_part("content", "menu"); ?>

    <div class="col-xs-12">
        <?php get_template_part("content", "banners"); ?>
        <?php get_template_part("content", "reservation"); ?>
        
        <?php

            if ($hasCategory) {
                $paged = get_query_var('paged') ? get_query_var('paged') : 1 ;
                $args = array( 'tax_query' => array( 
                        array( 
                            'taxonomy' => 'antours-category', //or tag or custom taxonomy
                            'field' => 'id', 
                            'terms' => array($category[0]->term_id) 
                        ) 
                    ), 'post_type' =>  'at_paquetes', 'post_status' => 'publish', 'paged' => $paged);
                $posts = query_posts($args);

                if (have_posts()) {
                    get_template_part('content', 'packages-open');
                    while(have_posts()) {
                        the_post();
                        get_template_part('content', 'template-package');
                    }
                    get_template_part('content', 'packages-close');
                } else {
                    get_template_part("content", "not_found");
                }
            } else {
                get_template_part("content", "not_found");
            }

            //wp_paginate();
        ?>

    </div>
</div>

<?php get_footer(); ?>