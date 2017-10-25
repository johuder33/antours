<?php

global $domain, $wp_query;
$paged = get_query_var('page') ? get_query_var('page') : 1 ;
// build select tag
$selectTagFilter = buildFilter(array("filter-by-category", "form-control"), null, __('Filter by category', $domain));

?>

<div class="container">
    <div class="row packages-grid center-block">
        <section class="col justify-content-md-end">
            <form class="form-horizontal row justify-content-end" id="filter-form">
                <div class="container-filter-form row">
                    <div class="col">
                    
                    <div class="form-group">
                        <?php echo $selectTagFilter; ?> 
                    </div>

                    </div>

                    <div class="col-auto">
                        <div class="form-group">
                            <button type="submit" class="btn btn-default btn-filter">
                                <?php _e("Apply filter", $domain); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        <div class="row">
            <?php
                while(have_posts()) {
                    the_post();
                    get_template_part("content", "template-package");
                }
            ?>
        </div>

        <?php
            // show pagination
            show_wp_paginate($paged, $wp_query->max_num_pages);
        ?>
    </div>
</div>