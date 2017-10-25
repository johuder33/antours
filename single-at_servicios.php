<?php

get_header();

global $packages;
$category = get_the_terms($post->ID, 'antours-category');

?>

<div class="container-fluid">
    <?php get_template_part("content", "menu"); ?>

    <div class="container">
        <div class="row">
        <?php //get_template_part("content", "banners"); ?>
        <?php //get_template_part("content", "reservation"); ?>
        
        <?php

            if (count($category) > 0) {
                $category = array_shift($category);
                //var_dump($wp_query->query['page']);
                // pagination
                $paged = get_query_var('page') ? get_query_var('page') : 1 ;
                // make query
                $args = array('tax_query' => array( 
                        array( 
                            'taxonomy' => 'antours-category',
                            'field' => 'id', 
                            'terms' => array($category->term_id) 
                        )),
                        'post_type' =>  $packages,
                        'post_status' => 'publish',
                        'paged' => $paged,
                        );

                $query = new WP_Query($args);

                if ($query->have_posts()) {
                    while($query->have_posts()) {
                        $query->the_post();
                        get_template_part('content', 'template-package');
                    }

                    // show pagination
                    ?>
                    <div class="col-12">
                        <?php
                            show_wp_paginate($paged, $query->max_num_pages);
                        ?>
                    </div>
                    <?php
                } else {
                    get_template_part("content", "not_found");
                }
            } else {
                get_template_part("content", "not_found");
            }
        ?>

        </div>
    </div>
</div>

<?php get_footer(); ?>