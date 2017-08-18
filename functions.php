<?php
session_start();
// import custom Error classes
include_once(get_template_directory() . "/AntoursError.php");
include_once(get_template_directory() . "/system/fields.php");

$languages;

$prefix = "antours";

$domain = $prefix;

$services = "at_servicios";
$packages = "at_paquetes";

$serviceImageLabel = "antours_service_background";
$packageFeaturedImage = "package_featured_image";

$customImageSizes = array(
    $serviceImageLabel => array(
        1024,
        200,
        true
    ),
    $packageFeaturedImage = array(
        512,
        512,
        true
    )
);

// add support for post thumbnail
add_theme_support( 'post-thumbnails' ); 

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

add_action("wp_enqueue_scripts", "load_JS_ResourcesAtFrondEnd");

function register_handle_map($google_map_url) {
    global $post, $packages;

    if ($post->post_type === $packages) {
        wp_enqueue_script("manager-map-RW", loadAssetFromResourceDirectory("scripts", "manager-map.js"), array(), 1.0, true);
        wp_enqueue_script("numeric-antours-pricer", loadAssetFromResourceDirectory("scripts", "autonumeric-price.js"), array(), 1.0, true);
    }

    return $google_map_url;
}

add_filter('rwmb_google_maps_url', 'register_handle_map');

/*
* load_CSS_ResourcesAtFrondEnd function will load
* all scripts files only in front end and finally
* we need to push it into the hook wordpress
*
* add_action function it is allowed 3 parameters
* 1: hook name (WP), 2: function name, 3: priority
*/

function load_CSS_ResourcesAtFrondEnd() {
    wp_enqueue_style("bootstrap-antours", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css", array(), null, false);
    wp_enqueue_style("jquery-datepicker", loadAssetFromResourceDirectory("scripts/datepicker", "datepicker.min.css"));
    wp_enqueue_style("jquery-timepicker", "https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css");
    wp_enqueue_style("open-sans-font", "https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800");
    wp_enqueue_style("antours-style", get_stylesheet_uri(), array(), 1.2);
}

add_action("wp_enqueue_scripts", "load_CSS_ResourcesAtFrondEnd");

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

add_action('init', registerPostTypes, 1);

function registerNewSizes() {
    global $customImageSizes;
    foreach($customImageSizes as $namespace => $args) {
        list($width, $height, $cut) = $args;
        add_image_size($namespace, $width, $height, $cut);
    }
}

function registerPostTypes() {
    $servicePostTypeArgs = array(
        'labels' => array(
            'name' => __('Service menu label', $domain),
            'singular_name' => __('Service menu singular label', $domain),
            'add_new' => __('Service menu add new label', $domain),
            'not_found' => __('Service menu not found label', $domain),
            'all_items' => __('Service menu all items label', $domain),
            'add_new_item' => __('Service menu add new item label', $domain),
            'featured_image' => __('Service featured image', $domain),
            'set_featured_image' => __('Service set featured image', $domain),
        ),
        'public' => true,
        'description' => 'Antours servicios section',
        'has_archive' => true,
        'show_ui' => true,
        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        ),
        'menu_icon' => 'dashicons-products',
        'taxonomies' => array('antours-category'),
    );

    $packagePostTypeArgs = array(
        'labels' => array(
            'name' => __('Package menu label', $domain),
            'singular_name' => __('Package menu singular label', $domain),
            'add_new' => __('Package menu add new label', $domain),
            'not_found' => __('Package menu not found label', $domain),
            'all_items' => __('Package menu all items label', $domain),
            'add_new_item' => __('Package menu add new item label', $domain),
            'featured_image' => __('Package featured image', $domain),
            'set_featured_image' => __('Package set featured image', $domain),
        ),
        'public' => true,
        'description' => 'Antours packages section',
        'has_archive' => true,
        'show_ui' => true,
        'supports' => array(
            'title',
            'editor',
            'comments',
            'thumbnail'
        ),
        'taxonomies' => array('antours-category'),
    );

    // Create custom size
    registerNewSizes();

    // register post type roots
    global $services;
    global $packages;

    register_post_type($services, $servicePostTypeArgs);
    register_post_type($packages, $packagePostTypeArgs);

    // register taxonomy category
    register_taxonomy(
        'antours-category',
        array($services, $packages),
        array(
            'label' => __( 'Categorias', $domain ),
            'rewrite' => array( 'slug' => 'categoria' ),
            'hierarchical' => true,
        )
    );
}

add_filter('rwmb_meta_boxes', 'RegisterMetaboxesInPackage');

function RegisterMetaboxesInPackage($meta_boxes) {
    global $prefix, $domain, $services, $packages;
    $pre = $prefix."_";

    $meta_boxes[] = array(
        'id' => $pre . 'trip_price',
		'title'  => __( 'Trip price', $domain ),
        'post_types' => array( $packages ),
        'context'    => 'side',
        'priority'   => 'low',
		'fields' => array(
			array(
				'id'   => $pre . 'trip_price_package',
				'name' => __( 'Package`s price', $domain ),
				'type' => 'text',
				'class' => array('numeric-price-antours')
			)
		),
	);

    $meta_boxes[] = array(
        'id'         => $pre . 'schedule',
        'title'      => __( 'Schedule label', $domain ),
        'post_types' => array( $packages ),
        'context'    => 'side',
        'priority'   => 'low',
        'fields' => array(
            array(
                'name'  => __( 'Time to departure', $domain ),
                'id'    => $pre . 'time_departure',
                'type'  => 'time',
                'class' => 'time_go'
            ),
            array(
                'name'  => __( 'Time to return', $domain ),
                'id'    => $pre . 'time_return',
                'type'  => 'time',
                'class' => 'time_return'
            ),
        )
    );

    $meta_boxes[] = array(
        'id'         => $pre . 'departure',
        'title'      => __( 'Departure place', $domain ),
        'post_types' => array( $packages ),
        'context'    => 'side',
        'priority'   => 'low',
        'fields' => array(
            array(
                'name'  => __( 'Departure place', $domain ),
                'desc'  => __( 'Departure place desc', $domain ),
                'id'    => $pre . 'departure_place',
                'type'  => 'textarea',
                'class' => 'meeting_place',
                'attributes' => array(
                    'maxlength' => 255,
                    'rows' => 5
                ),
                'clone' => true,
                'add_button' => __( 'Add departure place button', $domain )
            )
        )
    );

    $key_map_address = $pre . 'trip_map_address';

    $meta_boxes[] = array(
		'title'  => __( 'Trip map', $domain ),
        'post_types' => array( $packages ),
        'context'    => 'normal',
        'priority'   => 'low',
		'fields' => array(
			array(
				'id'   => $key_map_address,
				'name' => __( 'Route name map', $domain ),
				'type' => 'text'
			),
			array(
				'id' => $pre . 'trip_map',
				'name' => __( 'Map Tour Location', $domain ),
				'type' => 'map',
				// Default location: 'latitude,longitude[,zoom]' (zoom is optional)
				'std' => '-33.4394037,-70.7108879,10',
				'address_field' => $key_map_address,
				'api_key' => 'AIzaSyA9otsw6Uersa3aTB9IMV0gxyeyytOHBtw',
			),
		),
	);

    $meta_boxes[] = array(
        'id' => $pre . 'trip_gallery',
		'title'  => __( 'Trip gallery', $domain ),
        'post_types' => array( $packages ),
        'context'    => 'normal',
        'priority'   => 'low',
		'fields' => array(
			array(
				'id'   => $pre . 'trip_gallery_group',
				'name' => __( 'Trip gallery', $domain ),
				'type' => 'image_advanced'
			)
		),
	);

    return $meta_boxes;
}

/*function registerDefaultsCategories() {
    load_theme_textdomain( 'antours', get_template_directory() . '/languages' );

    $categories = array(
        'Tours' => array(
            'description' => __('Categoria para los servicios de tipo Tours', 'antours_term_tours'),
            'slug' => 'tours'
        ),
        'Personas' => array(
            'description' => __('Categoria para los servicios de tipo Personas', 'antours_term_personas'),
            'slug' => 'personas'
        ),
        'Empresas' => array(
            'description' => __('Categoria para los servicios de tipo Empresas', 'antours_term_empresas'),
            'slug' => 'empresas'
        ),
        'Privados' => array(
            'description' => __('Categoria para los servicios de tipo Privados', 'antours_term_privados'),
            'slug' => 'privados'
        ),
        'Translados' => array(
            'description' => __('Categoria para los servicios de tipo Translados', 'antours_term_translados'),
            'slug' => 'translados'
        ),
    );

    foreach($categories as $category => $attributes) {
        wp_insert_term($category, 'category', array(
            'description' => $attributes['description'],
            'slug' => $attributes['slug']
        ));
    }
}

add_action('after_setup_theme', 'registerDefaultsCategories');*/

function renderTitle($title, $slogan, $classes = array()) {
    $classesJoined = join(" ", $classes);

    include (get_template_directory() . "/content-title.php");
}

function alter_footer_admin() {
    add_filter( 'admin_footer_text', 'wpse_edit_text', 11 );
}

function wpse_edit_text($content) {
    //return "New Footer Text";
    return $content;
}

add_action( 'admin_init', 'alter_footer_admin' );

if (class_exists('WPPaginate')) {
	$wp_paginate = new WPPaginate();
}

function wp_paginates($args = false) {
	global $wp_paginate;
	$wp_paginate->type = 'package';
	return $wp_paginate->paginate($args);
}

add_filter( 'wp_handle_upload_prefilter', 'checker_image_size' );

function checker_image_size( $file ) {
    global $services, $_wp_additional_image_sizes, $serviceImageLabel, $customImageSizes, $domain;

    if (get_post_type($_REQUEST['post_id']) === $services) {
        $image_sizes   = getimagesize( $file['tmp_name'] );

        list($width, $height) = $customImageSizes[$serviceImageLabel];
        list($currentWidth, $currentHeight) = $image_sizes;

        if ( $currentWidth < $width || $currentHeight < $height ) {
            $message = __( "not size for %width%, %height%, current %currentWidth%, %currentHeight%", $domain );
            $message = str_replace(array("%width%", "%height%", "%currentWidth%", "%currentHeight%"), array($width, $height, $currentWidth, $currentHeight), $message);
            $file['error'] = $message;
        }
    }

    return $file;
}

add_filter( 'pll_the_languages', 'my_dropdown', 10, 2 );
 
function my_dropdown( $output, $args ) {
    if ( ! $args['dropdown'] ) {
        return $output;
    }
    foreach ( array( 'en_US', 'fr_FR', 'de_DE' ) as $lang ) {
        $file = POLYLANG_DIR . ”/flags/$lang.png”;
        $value = reset( explode( '_', get_locale() ) );
        $output = str_replace( "value='" . $value . "'", "value='" . $lang . "' title='" . $file . "'", $output );
    }
    return $output;
}

/*add_filter( 'pll_the_languages_args', 'my_language_switcher_args' );
 
function my_language_switcher_args( $args ) {
    $args['display_names_as'] = 'slug';
    return $args;
}*/