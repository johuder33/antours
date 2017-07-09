<?php

/*
* load_JS_ResourcesAtFrondEnd function will load
* all scripts files only in front end and finally
* we need to push it into the hook wordpress
*
* add_action function it is allowed 3 parameters
* 1: hook name (WP), 2: function name, 3: priority
*/

function load_JS_ResourcesAtFrondEnd() {
    wp_enqueue_script("bootstrap-antours-js", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js", array( 'jquery' ), null, false);
    wp_enqueue_script("jquery-datepicker", loadAssetFromResourceDirectory("scripts/datepicker", "datepicker.min.js"));
    wp_enqueue_script("jquery-timepicker", "https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js");
    wp_enqueue_script("antours-scripts", loadAssetFromResourceDirectory("scripts", "antours.js"), array(), 1.0);
}

add_action("wp_enqueue_scripts", "load_JS_ResourcesAtFrondEnd", 20);

/*
* load_CSS_ResourcesAtFrondEnd function will load
* all scripts files only in front end and finally
* we need to push it into the hook wordpress
*
* add_action function it is allowed 3 parameters
* 1: hook name (WP), 2: function name, 3: priority
*/

function load_CSS_ResourcesAtFrondEnd() {
    wp_enqueue_style("antours-style", get_stylesheet_uri());
    wp_enqueue_style("bootstrap-antours", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css", array(), null, false);
    wp_enqueue_style("jquery-datepicker", loadAssetFromResourceDirectory("scripts/datepicker", "datepicker.min.css"));
    wp_enqueue_style("jquery-timepicker", "https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css");
}

add_action("wp_enqueue_scripts", "load_CSS_ResourcesAtFrondEnd", 25);

/*
* loadAssetFromResourceDirectory function will return the base url
* and the context (directory name) contact with filename
* @return String
*/

function loadAssetFromResourceDirectory($context, $filename) {
    $base_url = get_template_directory_uri();

    if (empty($context) || empty($filename) || gettype($context) !== "string" || gettype($filename) !== "string") {
        return $base_url;
    }

    $base_url .= "/resources/$context/$filename";
    

    return $base_url;
}