<?php
session_start();

$prefix = "antours";
$domain = $prefix;

// import custom Error classes
include_once(get_template_directory() . "/system/fields.php");
include_once(get_template_directory() . "/system/AntoursBanners.php");
include_once(get_template_directory() . "/system/translation.php");
// Antours page options class
include_once(get_template_directory() . "/system/AntoursContactPage.php");

$metabox_prefix = $prefix."_mtx_";

$commentLimit = get_option( 'posts_per_page' );
$nonceToLoadComments = 'load-comment-per-post';
$loadingText = $translation['COMMENT_BTN_LOADING'];
$loaderText = $translation['COMMENT_BTN_LOAD'];

$actionNameContactForm = 'send_notification_contact_form';
$nonceToContactForm = 'nonce-contact-form-antours';
$loadingContactForm = $translation['CONTACT_BTN_LOADING'];
$loaderContactForm = $translation['CONTACT_BTN_LOAD'];

$nonceToServices = 'nonce-services-per-taxonomy';
$serviceActionName = "load_services_taxo_posts";

$nonceToReservationPackage = 'nonce-reservation-package';
$reservationActionName = "save_reservation";
$getCommuneByCityIdAction = "get_commune_by_city";

$services = "at_servicios";
$packages = "at_paquetes";
$about = "at_nosotros";
$banners = "at_container_banner";
$home = "at_home";
$serviceContent = "at_services_content";
// namespace category antours
$categoryNamespace = "antours-category";

$serviceImageLabel = "antours_service_background";
$packageFeaturedImage = "package_featured_image";
$bannerPostTypeSize = "banner_post_type_size";
$bannerPostTypeForTablets = "banner_post_type_tablets";
$bannerPostTypeForSmartPhones = "banner_post_type_smartphones";

$quickFormFields = array(
    'fullname' => array(
        'attributes' => array(
            'class' => 'form-control quick-field',
            'name' => 'fullname',
            'placeholder' => $translation['QUICK_FIELD_FULLNAME_PLACEHOLDER'],
            'required' => true,
            'maxlength' => 100,
            'type' => 'text'
        ),
        'js_function' => 'check_name',
        'error' => $translation['QUICK_FIELD_FULLNAME_ERROR_MESSAGE'],
        'schema' => 'customer_fullname'
    ),
    'id_number' => array(
        'attributes' => array(
            'class' => 'form-control quick-field',
            'name' => 'id_number',
            'placeholder' => $translation['QUICK_FIELD_ID_NUMBER_PLACEHOLDER'],
            'required' => true,
            'maxlength' => 20,
            'type' => 'text'
        ),
        'js_function' => 'check_idNumber',
        'error' => $translation['QUICK_FIELD_ID_NUMBER_ERROR_MESSAGE'],
        'schema' => 'customer_rut'
    ),
    'phone' => array(
        'attributes' => array(
            'class' => 'form-control quick-field',
            'name' => 'phone',
            'placeholder' => $translation['QUICK_FIELD_PHONE_PLACEHOLDER'],
            'maxlength' => 15,
            'type' => 'text'
        ),
        'js_function' => 'check_phone',
        'error' => $translation['QUICK_FIELD_PHONE_ERROR_MESSAGE'],
        'schema' => 'customer_phone'
    ),
    'email' => array(
        'attributes' => array(
            'class' => 'form-control quick-field',
            'name' => 'email',
            'placeholder' => $translation['QUICK_FIELD_EMAIL_PLACEHOLDER'],
            'required' => true,
            'maxlength' => 255,
            'type' => 'text'
        ),
        'js_function' => 'check_email',
        'error' => $translation['QUICK_FIELD_EMAIL_ERROR_MESSAGE'],
        'schema' => 'customer_email'
    ),
    'amount_passenger' => array(
        'attributes' => array(
            'class' => 'form-control quick-field',
            'name' => 'amount_passenger',
            'placeholder' => $translation['QUICK_FIELD_PASSENGERS_PLACEHOLDER'],
            'required' => true,
            'maxlength' => 2,
            'type' => 'text'
        ),
        'js_function' => 'check_amount_passenger',
        'error' => $translation['QUICK_FIELD_PASSENGERS_ERROR_MESSAGE'],
        'schema' => 'amount_passenger'
    ),
    'hotel_address' => array(
        'attributes' => array(
            'class' => 'form-control quick-field',
            'name' => 'hotel_address',
            'placeholder' => $translation['QUICK_FIELD_HOTEL_ADDRESS_PLACEHOLDER'],
            'maxlength' => 255,
            'type' => 'text'
        ),
        'js_function' => 'check_address',
        'error' => $translation['QUICK_FIELD_HOTEL_ADDRESS_ERROR_MESSAGE'],
        'schema' => 'hotel_address'
    )
);

$customImageSizes = array(
    $serviceImageLabel => array(
        1024,
        200,
        true
    ),
    $packageFeaturedImage => array(
        512,
        512,
        true
    ),
    $bannerPostTypeSize => array(
        1600,
        700,
        true,
        1101 // this is only for min-width porpuse
    ),
    $bannerPostTypeForTablets => array(
        1100,
        500,
        true,
        801 // this is only for min-width porpuse
    ),
    $bannerPostTypeForSmartPhones => array(
        800,
        500,
        true,
        1 // this is only for min-width porpuse
    )
);

$bannerImagesSizes = array(
    $bannerPostTypeSize,
    $bannerPostTypeForTablets,
    $bannerPostTypeForSmartPhones
);

function getSourcesForBannersSizes($post) {
    global $bannerImagesSizes, $customImageSizes;
    $sources = array("<picture>");
    foreach($bannerImagesSizes as $nameSize) {
        list($max_width, $_, $__, $min_width) = $customImageSizes[$nameSize];
        $image_url = get_the_post_thumbnail_url($post, $nameSize);
        $source = "<source media='(min-width: {$min_width}px) and (max-width: {$max_width}px)' srcset='{$image_url}'>";
        array_push($sources, $source);
    }

    $original_img = get_the_post_thumbnail_url($post);
    $original_img = "<img src='{$original_img}' class='d-block w-100' />";
    array_push($sources, $original_img);
    array_push($sources, "</picture>");
    $sources = implode("", $sources);

    return $sources;
}

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
    global $post, $packages, $nonceToLoadComments, $loadingText, $loaderText, $nonceToContactForm, $loadingContactForm, $loaderContactForm, $actionNameContactForm;
    global $nonceToServices, $serviceActionName, $nonceToReservationPackage, $reservationActionName, $getCommuneByCityIdAction;

    wp_enqueue_script("jquery", "https://code.jquery.com/jquery-3.2.1.slim.min.js", array(), false, true);
    wp_enqueue_script("popper", "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js", array("jquery"), null, true);
    wp_enqueue_script("bootstrap-antours-js", "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js", array("jquery"), null, true);
    wp_enqueue_script("jquery-datepicker", loadAssetFromResourceDirectory("scripts/datepicker", "datepicker.min.js"), array(), null, true);
    wp_enqueue_script("jquery-timepicker", "https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js", array(), null, true);
    wp_enqueue_script("antours-validators", loadAssetFromResourceDirectory("scripts", "validator.js"), array(), 1.0, true);
    wp_enqueue_script("antours-scripts", loadAssetFromResourceDirectory("scripts", "antours.js"), array("antours-validators"), 1.3, true);
    wp_enqueue_script("google-maps", "https://maps.google.com/maps/api/js?key=AIzaSyA9otsw6Uersa3aTB9IMV0gxyeyytOHBtw", array(), null, true);
    wp_enqueue_script("map-frontend", loadAssetFromResourceDirectory("scripts", "map.js"), array(), 1.0, true);

    // set this to make available contact form to send emails
    wp_localize_script( 'antours-scripts', 'contact_form_config', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce($nonceToContactForm),
        'contact_progress_text' => $loadingContactForm,
        'contact_text' => $loaderContactForm,
        'actionName' => $actionNameContactForm
    ));

    wp_localize_script( 'antours-scripts', 'services_config', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce($nonceToServices),
        'actionName' => $serviceActionName
    ));

    wp_localize_script( 'antours-scripts', 'reservation_config', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce($nonceToReservationPackage),
        'actionName' => $reservationActionName,
        'validators' => extractQuickFieldMap(),
        'empty' => 'Debe ingresar su informacion'
    ));

    wp_localize_script( 'antours-scripts', 'booking_service_config', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce($getCommuneByCityIdAction),
        'actionName' => $getCommuneByCityIdAction
    ));

    if ($post->post_type === $packages) {
        wp_enqueue_script('lightbox-js');

        // expose ajax_url only in packages post type
        wp_localize_script( 'antours-scripts', 'comment_config', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'post_id' => $post->ID,
            'nonce' => wp_create_nonce($nonceToLoadComments),
            'loadingCommentText' => $loadingText,
            'loaderCommentText' => $loaderText
        ));
    }

    // always return current  language
    if (function_exists("pll_current_language")) {
        wp_localize_script( 'antours-scripts', 'antours_language', array(
            'language' => pll_current_language(),
        ));
    }
}

add_action("wp_enqueue_scripts", "load_JS_ResourcesAtFrondEnd");

/*
* load_CSS_ResourcesAtFrondEnd function will load
* all scripts files only in front end and finally
* we need to push it into the hook wordpress
*
* add_action function it is allowed 3 parameters
* 1: hook name (WP), 2: function name, 3: priority
*/

function load_CSS_ResourcesAtFrondEnd() {
    global $post, $packages;

    wp_enqueue_style("bootstrap-antours", "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css", array(), 1.1, false);
    wp_enqueue_style("jquery-datepicker", loadAssetFromResourceDirectory("scripts/datepicker", "datepicker.min.css"));
    wp_enqueue_style("jquery-timepicker", "https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css");
    wp_enqueue_style("open-sans-font", "https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800");
    wp_enqueue_style("antours-style", get_stylesheet_uri(), array(), 1.2);
    wp_enqueue_style('font-awesome');

    if ($post->post_type === $packages) {
        wp_enqueue_style('lightbox-css');
    }
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

add_action('admin_enqueue_scripts', 'load_scripts_for_admins');

function load_scripts_for_admins($page) {
    global $post, $packages;

    // load custom css admin panel
    wp_enqueue_style("custom-admin-css", loadAssetFromResourceDirectory("css", "admin-panel.css"));
    // load custom js admin panel
    wp_enqueue_script("custom-admin-js", loadAssetFromResourceDirectory("scripts", "admin.js"), array(), 1.0, true);

    // check if is inside new post or edit post
    if (in_array($page, array('post.php', 'post-new.php'))) {
        if ($post->post_type === $packages) {
            wp_enqueue_script("manager-map-admin");
        }
    }
}

add_action('admin_init', 'register_admin_scripts');

function register_admin_scripts() {
    wp_register_script('numeric-js', loadAssetFromResourceDirectory("scripts", "autonumeric-price.js"));
    wp_register_script('manager-map-admin', loadAssetFromResourceDirectory("scripts", "manager-map.js"), array('numeric-js'));
    registerOptionsFields();
    add_submenu_page(
        "options-general.php",
        "My options",
        "Antours Options",
        "manage_options",
        "antours",
        "render_options_page"
    );
}

function render_options_page() {
    echo "hola";
}

function registerOptionsFields() {
    add_settings_section(  
        'antours_options_section', // Section ID 
        'Redes Sociales de Antours', // Section Title
        'my_section_options_callback', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );

    add_settings_field( // Option 1
        'facebook_field', // Option ID
        'Facebook', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'antours_options_section', // Name of our section
        array( // The $args
            'facebook_field' // Should match Option ID
        )  
    );

    add_settings_field( // Option 1
        'twitter_field', // Option ID
        'Twitter', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'antours_options_section', // Name of our section
        array( // The $args
            'twitter_field' // Should match Option ID
        )  
    );

    add_settings_field( // Option 1
        'instagram_field', // Option ID
        'Instagram', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'antours_options_section', // Name of our section
        array( // The $args
            'twitter_field' // Should match Option ID
        )  
    );

    register_setting('general','facebook_field', 'esc_attr');
    register_setting('general','twitter_field', 'esc_attr');
    register_setting('general','instagram_field', 'esc_attr');
}

function my_section_options_callback() { // Section Callback
    echo '<p>Ingrese la URL de sus redes sociales</p>';
}

function my_textbox_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
}

add_action('init', 'registerPostTypes');

function registerNewSizes() {
    global $customImageSizes;
    foreach($customImageSizes as $namespace => $args) {
        list($width, $height, $cut) = $args;
        add_image_size($namespace, $width, $height, $cut);
    }
}

function registerPostTypes() {
    // register post type roots
    global $services, $serviceContent, $packages, $about, $banners, $home, $domain, $categoryNamespace, $translation;
    // Create custom size
    registerNewSizes();

    $servicePostTypeArgs = array(
        'labels' => array(
            'name' => $translation['CPT_SERVICE_NAME_MENU_LABEL'],
            'singular_name' => $translation['CPT_SERVICE_SINGULAR_MENU_LABEL'],
            'add_new' => $translation['CPT_SERVICE_ADD_NEW_MENU_LABEL'],
            'not_found' => $translation['CPT_SERVICE_NOT_FOUND_MENU_LABEL'],
            'all_items' => $translation['CPT_SERVICE_ALL_ITEMS_MENU_LABEL'],
            'add_new_item' => $translation['CPT_SERVICE_NEW_ITEM_MENU_LABEL'],
            'featured_image' => $translation['CPT_SERVICE_FEATURED_IMAGE_MENU_LABEL'],
            'set_featured_image' => $translation['CPT_SERVICE_SET_FEATURED_IMAGE_MENU_LABEL'],
        ),
        'public' => true,
        'description' => $translation['CPT_SERVICE_DESCRIPTION'],
        'has_archive' => true,
        'show_ui' => true,
        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        ),
        'menu_icon' => 'dashicons-products',
        'taxonomies' => array($categoryNamespace),
    );

    $packagePostTypeArgs = array(
        'labels' => array(
            'name' => $translation['CPT_PACKAGE_NAME_MENU_LABEL'],
            'singular_name' => $translation['CPT_PACKAGE_SINGULAR_MENU_LABEL'],
            'add_new' => $translation['CPT_PACKAGE_ADD_NEW_MENU_LABEL'],
            'not_found' => $translation['CPT_PACKAGE_NOT_FOUND_MENU_LABEL'],
            'all_items' => $translation['CPT_PACKAGE_ALL_ITEMS_MENU_LABEL'],
            'add_new_item' => $translation['CPT_PACKAGE_NEW_ITEM_MENU_LABEL'],
            'featured_image' => $translation['CPT_PACKAGE_FEATURED_IMAGE_MENU_LABEL'],
            'set_featured_image' => $translation['CPT_PACKAGE_SET_FEATURED_IMAGE_MENU_LABEL'],
        ),
        'public' => true,
        'description' => $translation['CPT_PACKAGE_DESCRIPTION'],
        'has_archive' => true,
        'show_ui' => true,
        'supports' => array(
            'title',
            'editor',
            'comments',
            'thumbnail'
        ),
        'taxonomies' => array($categoryNamespace),
    );

    $aboutPostTypeArgs = array(
        'labels' => array(
            'name' => $translation['CPT_ABOUT_NAME_MENU_LABEL'],
            'singular_name' => $translation['CPT_ABOUT_SINGULAR_MENU_LABEL'],
            'add_new' => $translation['CPT_ABOUT_ADD_NEW_MENU_LABEL'],
            'not_found' => $translation['CPT_ABOUT_NOT_FOUND_MENU_LABEL'],
            'all_items' => $translation['CPT_ABOUT_ALL_ITEMS_MENU_LABEL'],
            'add_new_item' => $translation['CPT_ABOUT_NEW_ITEM_MENU_LABEL'],
            'featured_image' => $translation['CPT_ABOUT_FEATURED_IMAGE_MENU_LABEL'],
            'set_featured_image' => $translation['CPT_ABOUT_SET_FEATURED_IMAGE_MENU_LABEL'],
        ),
        'public' => true,
        'description' => $translation['CPT_ABOUT_DESCRIPTION'],
        'has_archive' => true,
        'show_ui' => true,
        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        )
    );

    $homePostTypeArgs = array(
        'labels' => array(
            'name' => $translation['CPT_HOME_NAME_MENU_LABEL'],
            'singular_name' => $translation['CPT_HOME_SINGULAR_MENU_LABEL'],
            'add_new' => $translation['CPT_HOME_ADD_NEW_MENU_LABEL'],
            'not_found' => $translation['CPT_HOME_NOT_FOUND_MENU_LABEL'],
            'all_items' => $translation['CPT_HOME_ALL_ITEMS_MENU_LABEL'],
            'add_new_item' => $translation['CPT_HOME_NEW_ITEM_MENU_LABEL'],
            'featured_image' => $translation['CPT_HOME_FEATURED_IMAGE_MENU_LABEL'],
            'set_featured_image' => $translation['CPT_HOME_SET_FEATURED_IMAGE_MENU_LABEL'],
        ),
        'public' => true,
        'description' => $translation['CPT_HOME_DESCRIPTION'],
        'has_archive' => false,
        'show_ui' => true,
        'supports' => array(
            'title',
            'editor'
        )
    );

    $serviceContentTypeArgs = array(
        'labels' => array(
            'name' => $translation['CPT_SERVICE_CONTENT_NAME_MENU_LABEL'],
            'singular_name' => $translation['CPT_SERVICE_CONTENT_SINGULAR_MENU_LABEL'],
            'add_new' => $translation['CPT_SERVICE_CONTENT_ADD_NEW_MENU_LABEL'],
            'not_found' => $translation['CPT_SERVICE_CONTENT_NOT_FOUND_MENU_LABEL'],
            'all_items' => $translation['CPT_SERVICE_CONTENT_ALL_ITEMS_MENU_LABEL'],
            'add_new_item' => $translation['CPT_SERVICE_CONTENT_NEW_ITEM_MENU_LABEL'],
            'featured_image' => $translation['CPT_SERVICE_CONTENT_FEATURED_IMAGE_MENU_LABEL'],
            'set_featured_image' => $translation['CPT_SERVICE_CONTENT_SET_FEATURED_IMAGE_MENU_LABEL'],
        ),
        'public' => true,
        'description' => $translation['CPT_SERVICE_CONTENT_DESCRIPTION'],
        'has_archive' => false,
        'show_ui' => true,
        'show_in_menu' => 'edit.php?post_type=' . $services,
        'supports' => array(
            'title',
            'editor'
        )
    );

    register_post_type($services, $servicePostTypeArgs);
    register_post_type($packages, $packagePostTypeArgs);
    register_post_type($about, $aboutPostTypeArgs);
    register_post_type($home, $homePostTypeArgs);
    register_post_type($serviceContent, $serviceContentTypeArgs);
    register_post_types_banner();

    register_taxonomy(
        $categoryNamespace,
        array($services, $packages),
        array(
            'label' => $translation['CUSTOM_CATEGORY_NAME'],
            'rewrite' => array( 'slug' => 'category' ),
            'hierarchical' => true,
        )
    );

    // register lightbox to be accesible for package single page
    wp_register_script('lightbox-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js');
    wp_register_style('lightbox-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css');
    // register lightbox to be accesible for package single page

    // register fontwesome only to be used in post type packages
    wp_register_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}

function register_post_types_banner() {
    global $services, $about, $home, $translation;
    $postTypes = array(
        'SERVICE' => $services,
        'ABOUT' => $about,
        'HOME' => $home
    );

    foreach($postTypes as $key => $namespace) {
        $CommonPostTypeArgs = array(
            'labels' => array(
                'name' => $translation["BANNER_{$key}_NAME_MENU_LABEL"],
                'singular_name' => $translation["BANNER_{$key}_SINGULAR_MENU_LABEL"],
                'add_new' => $translation["BANNER_{$key}_ADD_NEW_MENU_LABEL"],
                'not_found' => $translation["BANNER_{$key}_NOT_FOUND_MENU_LABEL"],
                'all_items' => $translation["BANNER_{$key}_ALL_ITEMS_MENU_LABEL"],
                'add_new_item' => $translation["BANNER_{$key}_NEW_ITEM_MENU_LABEL"],
                'featured_image' => $translation["BANNER_{$key}_FEATURED_IMAGE_MENU_LABEL"],
                'set_featured_image' => $translation["BANNER_{$key}_SET_FEATURED_IMAGE_MENU_LABEL"],
            ),
            'public' => true,
            'description' => $translation["BANNER_{$key}_DESCRIPTION"],
            'has_archive' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type='.$home,
            'supports' => array(
                'title',
                'thumbnail'
            )
        );
        
        register_post_type($namespace.'_banner', $CommonPostTypeArgs);
    }
}

add_action( 'admin_menu', 'myprefix_adjust_the_wp_menu', 999 );

function myprefix_adjust_the_wp_menu() {
    global $about;

  /*//Get number of posts authored by user
  $args = array('post_type' => $about, 'fields'>'ids');
  $count = count(get_posts($args));

  global $submenu;
  unset($submenu['edit.php?post_type='.$about][10]);

    // Hide link on listing page

  //Conditionally remove link:
  if($count > 0) {
        $page = remove_submenu_page( 'edit.php?post_type='. $about, 'post-new.php?post_type='. $about );
        if (isset($_GET['post_type']) && $_GET['post_type'] == $about) {
            //echo '<style type="text/css"> .page-title-action { display: none!important; } </style>';
        }
  }*/
}

add_filter('rwmb_meta_boxes', 'RegisterMetaboxesInPackage');

function RegisterMetaboxesInPackage($meta_boxes) {
    global $prefix, $domain, $services, $packages, $home, $about, $metabox_prefix, $translation;
    $pre = $metabox_prefix;

    $meta_boxes[] = array(
        'id' => $pre . 'trip_price',
		'title'  => $translation['METABOX_TRIP_PRICE_TITLE'],
        'post_types' => array( $packages ),
        'context'    => 'side',
        'priority'   => 'low',
		'fields' => array(
			array(
				'id'   => $pre . 'trip_price_package',
				'name' => $translation['METABOX_TRIP_PRICE'],
				'type' => 'text',
				'class' => array('numeric-price-antours')
			)
		),
	);

    $meta_boxes[] = array(
        'id'         => $pre . 'schedule',
        'title'      => $translation['METABOX_SCHEDULE_TRIP'],
        'post_types' => array( $packages ),
        'context'    => 'side',
        'priority'   => 'low',
        'fields' => array(
            array(
                'name'  => $translation['METABOX_TIME_DEPARTURE'],
                'id'    => $pre . 'time_departure',
                'type'  => 'time',
                'class' => 'time_go'
            ),
            array(
                'name'  => $translation['METABOX_TIME_RETURN'],
                'id'    => $pre . 'time_return',
                'type'  => 'time',
                'class' => 'time_return'
            ),
        )
    );

    $meta_boxes[] = array(
        'id'         => $pre . 'departure',
        'title'      => $translation['METABOX_DEPARTURE_PLACE_TITLE'],
        'post_types' => array( $packages ),
        'context'    => 'side',
        'priority'   => 'low',
        'fields' => array(
            array(
                'name'  => $translation['METABOX_DEPARTURE_PLACE'],
                'desc'  => $translation['METABOX_DEPARTURE_PLACE_DESCRIPTION'],
                'id'    => $pre . 'departure_place',
                'type'  => 'textarea',
                'class' => 'meeting_place',
                'attributes' => array(
                    'maxlength' => 255,
                    'rows' => 5
                ),
                'clone' => true,
                'add_button' => $translation['METABOX_DEPARTURE_PLACE_CLONE_BTN']
            )
        )
    );

    $key_map_address = $pre . 'trip_map_address';

    $meta_boxes[] = array(
		'title'  => $translation['METABOX_TRIP_MAP_TITLE'],
        'post_types' => array( $packages ),
        'context'    => 'normal',
        'priority'   => 'low',
		'fields' => array(
			array(
				'id'   => $key_map_address,
				'name' => $translation['METABOX_TRIP_MAP_ROUTE_NAME'],
				'type' => 'text'
			),
			array(
				'id' => $pre . 'trip_map',
				'name' => $translation['METABOX_TRIP_MAP'],
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
		'title'  => $translation['METABOX_TRIP_GALLERY_TITLE'],
        'post_types' => array( $packages ),
        'context'    => 'normal',
        'priority'   => 'low',
		'fields' => array(
			array(
				'id'   => $pre . 'trip_gallery_group',
				'name' => $translation['METABOX_TRIP_GALLERY'],
				'type' => 'image_advanced'
			)
		),
	);
    
    return $meta_boxes;
}

function createCustomCategories() {
    global $categoryNamespace, $translation, $services, $packages;

    $categories = array(
        'EN' => array(
            'Tours' => array(
                'description' => $translation['EN_CUSTOM_CATEGORY_TOURS_DESC'],
                'slug' => 'tours'
            ),
            'People' => array(
                'description' => $translation['EN_CUSTOM_CATEGORY_PEOPLE_DESC'],
                'slug' => 'people'
            ),
            'Companies' => array(
                'description' => $translation['EN_CUSTOM_CATEGORY_COMPANIES_DESC'],
                'slug' => 'companies'
            ),
            'Privates' => array(
                'description' => $translation['EN_CUSTOM_CATEGORY_PRIVATES_DESC'],
                'slug' => 'privates'
            ),
            'Transfers' => array(
                'description' => $translation['EN_CUSTOM_CATEGORY_TRANSFERS_DESC'],
                'slug' => 'transfers'
            )
        ),
        'ES' => array(
            'Paquetes' => array(
                'description' => $translation['ES_CUSTOM_CATEGORY_PAQUETES_DESC'],
                'slug' => 'tours'
            ),
            'Personas' => array(
                'description' => $translation['ES_CUSTOM_CATEGORY_PERSONAS_DESC'],
                'slug' => 'personas'
            ),
            'Compañias' => array(
                'description' => $translation['ES_CUSTOM_CATEGORY_COMPANIAS_DESC'],
                'slug' => 'compania'
            ),
            'Privados' => array(
                'description' => $translation['ES_CUSTOM_CATEGORY_PRIVADOS_DESC'],
                'slug' => 'privados'
            ),
            'Traslados' => array(
                'description' => $translation['ES_CUSTOM_CATEGORY_TRASLADOS_DESC'],
                'slug' => 'traslados'
            )
        ),
        'PT' => array(
            'Passeios' => array(
                'description' => $translation['PT_CUSTOM_CATEGORY_PASSEIOS_DESC'],
                'slug' => 'passeios'
            ),
            'Pessoas' => array(
                'description' => $translation['PT_CUSTOM_CATEGORY_PESSOAS_DESC'],
                'slug' => 'pessoas'
            ),
            'Empresas' => array(
                'description' => $translation['PT_CUSTOM_CATEGORY_EMPRESAS_DESC'],
                'slug' => 'empresas'
            ),
            'Exclusivo' => array(
                'description' => $translation['PT_CUSTOM_CATEGORY_EXCLUSIVO_DESC'],
                'slug' => 'exclusivo'
            ),
            'Transferências' => array(
                'description' => $translation['PT_CUSTOM_CATEGORY_TRANSFERENCIAS_DESC'],
                'slug' => 'transferencias'
            )
        )
    );

    // register taxonomy category
    register_taxonomy(
        $categoryNamespace,
        array($services, $packages),
        array(
            'label' => $translation['CUSTOM_CATEGORY_NAME'],
            'rewrite' => array( 'slug' => 'category' ),
            'hierarchical' => true,
        )
    );

    foreach($categories as $lang => $category) {
        $langToLower = strtolower($lang);
        foreach($category as $category_name => $attributes) {
            $term = wp_insert_term($category_name, $categoryNamespace, array(
                'description' => $attributes['description'],
                'slug' => $attributes['slug']
            ));
            
            if (isset($term['term_id'])) {
                $category_id = $term['term_id'];

                if(function_exists('pll_set_term_language')) {
                    // with this function we can register a term id to specific lang
                    pll_set_term_language($category_id, $langToLower);
                }
            }
        }
    }
}

function registerDefaultsConfigTheme() {
    global $domain;
    // translate available into custom theme
    load_theme_textdomain( $domain );
    createCustomCategories();
    // register strings to be translated
    registerStringsToTranslate();
}

add_action('after_switch_theme', 'registerDefaultsConfigTheme');

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

add_filter( 'image_size_names_choose', 'custom_image_sizes_choose' );
function custom_image_sizes_choose( $sizes ) {
    global $serviceImageLabel;
    $custom_sizes = array(
        $serviceImageLabel => 'Service Image Category'
    );

    return array_merge( $sizes, $custom_sizes );
}

add_action( 'admin_init', 'alter_footer_admin' );

add_filter( 'wp_handle_upload_prefilter', 'checker_image_size' );

function checker_image_size( $file ) {
    global $translation, $post, $services, $serviceImageLabel, $customImageSizes, $domain;

    if (get_post_type($_REQUEST['post_id']) === $services) {
        $image_sizes   = getimagesize( $file['tmp_name'] );

        list($width, $height) = $customImageSizes[$serviceImageLabel];
        list($currentWidth, $currentHeight) = $image_sizes;

        if ( $currentWidth < $width || $currentHeight < $height ) {
            $message = $translation['CHECKER_IMAGE_SIZE_ERROR_MESSAGE'];
            $message = str_replace(array('$width', '$height', '$currentWidth', '$currentHeight'), array($width, $height, $currentWidth, $currentHeight), $message);
            $file['error'] = $message;
        }
    }

    return $file;
}

add_action( 'wp_ajax_nopriv_' . $serviceActionName, 'load_service_posts' );
add_action( 'wp_ajax_' . $serviceActionName, 'load_service_posts' );

function load_service_posts() {
    global $packages, $wp_query, $categoryNamespace;
    $page = $_POST['page'];
    $taxId = $_POST['taxID'];
    $postsPerPage = get_option('posts_per_page');

    $args = array('tax_query' => array( 
            array( 
                'taxonomy' => $categoryNamespace,
                'field' => 'id', 
                'terms' => array($taxId) 
            )),
            'post_type' =>  $packages,
            'post_status' => 'publish',
            'paged' => $page
            );

    $posts = query_posts($args);

    $total = $wp_query->found_posts;

    $haveMore = ($page + 1) < ceil($total / $postsPerPage) ? true : false;

    if (have_posts()) {
        $result = array();

        while(have_posts()) {
            the_post();
            $grid = load_template_part($post, 'content-template-package.php');
            array_push($result, $grid);
        }
    }

    $response = array(
        'packages' => $result,
        'more' => $haveMore
    );

    wp_send_json_success($response);

    die();
}

add_action( 'wp_ajax_nopriv_' . $actionNameContactForm, 'send_notification_contact_form' );
add_action( 'wp_ajax_' . $actionNameContactForm, 'send_notification_contact_form' );

function send_notification_contact_form() {
    global $domain, $nonceToContactForm, $translation;
    $nonce = $_POST['nonce'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $isCorrectNonce = wp_verify_nonce($nonce, $nonceToContactForm);

    if ($isCorrectNonce) {
        $targetEmail = "johudergb@gmail.com";//get_option("antours_email_receivers");

        if (isset($name) && isset($lastname) && isset($message) && isset($targetEmail)) {
            $to = array($targetEmail, "loor.jehish@gmail.com");
            $content = array("namespace" => "contact", "message" => $message, "name" => $name, "lastname" => $lastname, "subject" => $subject);

            $status = wp_mail($to, $subject, $content);

            $response = array(
                "sent" => $status
            );

            if ($status) {
                wp_send_json_success($response);
            } else {
                wp_send_json_error($response);
            }
        }
    }

    $error = array(
        'error' => $translation['EMAIL_SENDING_ERROR_MESSAGE']
    );

    wp_send_json_error($error);

    die;
}

add_filter( 'wp_mail', 'alter_email_contact' );

function alter_email_contact( $args ) {
    $currentMessage = $args['message'];
    $new_wp_mail = array(
        'to'          => $args['to'],
        'subject'     => $args['subject'],
        'message'     => $args['message'],
        'headers'     => $args['headers'],
        'attachments' => $args['attachments'],
    );

    if (is_array($currentMessage)) {
        if ($currentMessage['namespace'] === 'contact') {
            $subject = $currentMessage['subject'];
            $lastname = $currentMessage['lastname'];
            $name = $currentMessage['name'];
            $message = $currentMessage['message'];

            $template = load_template_part(null, "emails/template-email-contact.php");
            $message = str_replace(array('$subject', '$name', '$lastname', '$message'), array($subject, $name, $lastname, $message), $template);

            $new_wp_mail = array(
                'to' => $args['to'],
                'subject' => $subject,
                'message' => $message,
                'headers' => array('Content-type: text/html')
            );
        }

        if ($currentMessage['namespace'] === 'reservation') {
            $data = $currentMessage['data'];
            $post = $currentMessage['post'];
            $subject = $currentMessage['subject'];
            $customer = $data['customer_fullname'];
            $customer_id = $data['customer_rut'];
            $customer_email = $data['customer_email'];
            $passengers = $data['amount_passenger'];
            $package_name = $post->post_title;

            $template = load_template_part(null, "emails/template-email-reservation.php");
            $message = str_replace(array('$subject', '$customer_name', '$customer_id', '$customer_email', '$passengers', '$package_name'), array($subject, $customer, $customer_id, $customer_email, $passengers, $package_name), $template);

            $new_wp_mail = array(
                'to' => $new_wp_mail['to'],
                'subject' => $subject,
                'message' => $message,
                'headers' => array('Content-type: text/html')
            );
        }

        if ($currentMessage['namespace'] === 'reservation-customer') {
            $data = $currentMessage['data'];
            $post = $currentMessage['post'];
            $price = $currentMessage['price'];
            $thumbnail_url = $currentMessage['thumbnail'];
            $subject = $currentMessage['subject'];

            $customer = $data['customer_fullname'];
            $packageName = $post->post_title;

            $template = load_template_part(null, "emails/template-email-customer-reservation.php");
            $message = str_replace(array('$subject', '$customer', '$package_name', '$price', '$thumbnail_url'), array($subject, $customer, $packageName, $price, $thumbnail_url), $template);

            $new_wp_mail = array(
                'to' => $data['customer_email'],
                'subject' => $subject,
                'message' => $message,
                'headers' => array('Content-type: text/html')
            );
        }
    }
	
	return $new_wp_mail;
}

add_action( 'wp_ajax_nopriv_get_more_comments', 'get_more_comments' );
add_action( 'wp_ajax_get_more_comments', 'get_more_comments' );

function get_more_comments() {
    global $commentLimit, $nonceToLoadComments;
	$postID =  $_POST['post_id'];
    $nonce = $_POST['nonce'];
    $page =  $_POST['page'];
    $perPage = $commentLimit;

    if (isset($nonce) && isset($postID) && isset($page)) {
        $isCorrectNonce = wp_verify_nonce($nonce, $nonceToLoadComments);
        if ($isCorrectNonce) {
            $comments = get_comments(array('number' => $commentLimit, 'offset' => ($page * $perPage), 'order' => 'DESC' ));

            if (count($comments) > 0) {
                $total = get_comments_number($postID);

                $results = array();
                foreach($comments as $comment) {
                    $currentComment = load_template_part($comment, "content-comment.php");
                    array_push($results, $currentComment);
                }

                $haveMore = ($page + 1) < ceil($total / $perPage) ? true : false;

                $response = array(
                    'comments' => $results,
                    'more' => $haveMore,
                    'total' => $total
                );

                wp_send_json_success($response);
            } else {
                $error = WP_Error(404, 'no more comments to this post');
                wp_send_json_error($error);
            }
        }
    }

    $error = WP_Error(400, 'some fields are missing');
    wp_send_json_error($error);

    die();
}

function load_template_part($comment, $templateName) {
    ob_start();
        include($templateName);
        //get_template_part('content', 'comment');
    $template = ob_get_contents();
    ob_end_clean();
    return $template;
}

/* ADD MENU PAGE CAR BOOKING */
/*add_action( 'admin_menu', 'add_car_booking_menu' );

function add_car_booking_menu(){
    global $domain, $translation;
    $hook = add_menu_page(
        $translation['CAR_BOOKING_LIST_TITLE'],
        $translation['CAR_BOOKING_LIST_MENU_NAME'],
        'manage_options',
        'car-booking',
        'Car_Booking_List_View',
        'dashicons-tickets-alt',
        30
    );
    add_action( "load-$hook", 'add_options' );
}*/

function add_options() {
    global $myListTable;

    $myListTable = new Antours_List_Table();
}

function Car_Booking_List_View(){
        global $myListTable;
        echo '</pre><div class="wrap"><h2>My List Table Test</h2>'; 
        $myListTable->prepare_items(); 
    ?>
        <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">
    <?php
        $myListTable->search_box( 'search', 'search_id' );
        $myListTable->display(); 
    echo '</form></div>'; 
}
/* ADD MENU PAGE CAR BOOKING */

function show_wp_paginate($paged, $max_num_pages) {
    $links = paginate_links(array(
        'base' => str_replace('%_%', 1 == $paged ? '' : "?page=%#%", "?page=%#%"),
        'format' => '?page=%#%',
        'total' => $max_num_pages,
        'current' => intval($paged),
        'type' => 'list',
        'mid_size' => 3,
        'show_all' => false,
        'next_text' => '<i class="fa fa-chevron-right"></i>',
        'prev_text' => '<i class="fa fa-chevron-left"></i>'
    ));

    $wrapper = "<div class='wrapper-paginate text-right'>{$links}</div>";

    echo $wrapper;
}

/* HERE TO APPLY FILTER FROM THEME */
add_action( 'pre_get_posts', 'filter_by_antours_category' ); 
function filter_by_antours_category(&$query){
    global $packages, $categoryNamespace;
    if (is_post_type_archive($packages)) {
        $categoryName = $_GET['category'];
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (isset($categoryName) && !empty($categoryName)) {
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => $categoryNamespace,
                    'field' => 'slug',
                    'terms' => $categoryName
                )
            ) );
        }
        $query->set('paged', $page);
    }
}

/* SELECT CATEGORY FILTER BUILDER */
function buildFilter($selectCssClasses = array(), $optionCssClasses = array(), $default = "choose an option") {
    global $categoryNamespace;
    $selectNameField = "category";
    $selected = null;

    if (isset($_GET[$selectNameField])) {
        $selected = $_GET[$selectNameField];
    }

    if (is_array($selectCssClasses) && isset($selectCssClasses)) {
        $selectCssClasses = implode(" ", $selectCssClasses);
    }

    if (is_array($optionCssClasses) && isset($optionCssClasses)) {
        $optionCssClasses = implode(" ", $optionCssClasses);
    }

    $categories = get_terms( $categoryNamespace, 'orderby=name&hide_empty=0' );
    $select = array("<select id='filter' name='{$selectNameField}' class='{$selectCssClasses}'>");
    array_push($select, "<option value=''>{$default}</option>");

    foreach($categories as $category) {
        $active = $selected && $selected === $category->slug ? "selected" : "";
        $option = "<option data-id='{$category->term_id}' class='{$optionCssClasses}' value='{$category->slug}' {$active}>{$category->name}</option>";
        array_push($select, $option);
    }

    array_push($select, "</select>");
    $select = implode("", $select);

    return $select;
}
/* SELECT CATEGORY FILTER BUILDER */

function registerStringsToTranslate() {
    if(function_exists('icl_register_string')) {
        // with this function we can register string to be translated
        icl_register_string("antours", "Titulo de Servicio", "Service Title");
        icl_register_string("antours", "Contenido del Servicio", "Service Content");

        icl_register_string("antours", "Titulo de Paquete", "Package Title");
        icl_register_string("antours", "Contenido de Paquete", "Package Content");

        icl_register_string("antours", "Texto por defecto en select de ciudad", "Placeholder select city");
        icl_register_string("antours", "Texto por defecto en select de comuna", "Placeholder select commune");
    }
}

function t($context, $name, $string) {
    if (function_exists('icl_t')) {
        $content = trim(icl_t($context, $name, $string));
        if (!empty($content)) {
            return $content;
        }
    }

    return false;
}


add_action( 'wp_ajax_nopriv_' . $reservationActionName, $reservationActionName );
add_action( 'wp_ajax_' . $reservationActionName, $reservationActionName );

function save_reservation() {
    global $nonceToReservationPackage, $wpdb, $domain, $translation, $metabox_prefix;

    try {
        $nonce = $_POST['nonce'];

        if (!class_exists('Reservation_Booking')) {
            throw new Exception($translation['CLASS_RESERVATION_UNDEFINED_ERROR_MESSAGE']);
        }

        if (!isset($nonce)) {
            throw new Exception($translation['RESERVATION_NONCE_UNDEFINED_ERROR_MESSAGE']);
        }

        $isCorrectNonce = wp_verify_nonce($nonce, $nonceToReservationPackage);

        if (!$isCorrectNonce) {
            throw new Exception($translation['RESERVATION_NONCE_INCORRECT_ERROR_MESSAGE']);
        }

        $fullname = $_POST['fullname'];
        $rut = $_POST['id_number'];
        $email = $_POST['email'];
        $amount_passenger = $_POST['amount_passenger'];
        $packageId = $_POST['postId'];

        if (!isset($packageId) || !isset($fullname) || !isset($rut) || !isset($email) || !isset($amount_passenger)) {
            throw new Exception($translation['RESERVATION_SOME_FIELDS_MISSING_ERROR_MESSAGE']);
        }

        if (!is_numeric($rut)) {
            throw new Exception($translation['RESERVATION_CUSTOMER_ID_ERROR_MESSAGE']);
        }

        if (!is_numeric($amount_passenger)) {
            throw new Exception($translation['RESERVATION_PASSENGERS_ERROR_MESSAGE']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception($translation['RESERVATION_CUSTOMER_EMAIL_ERROR_MESSAGE']);
        }

        if (!is_numeric($packageId)) {
            throw new Exception($translation['RESERVATION_PACKAGE_ID_ERROR_MESSAGE']);
        }

        $post = get_post($packageId);

        if (is_null($post)) {
            throw new Exception($translation['RESERVATION_PACKAGE_NOT_FOUND_ERROR_MESSAGE']);
        }

        $price = rwmb_meta($metabox_prefix.'trip_price_package', null, $post->ID);
        $thumbnail_url = get_the_post_thumbnail_url($post->ID);

        $packageId = intval($packageId);

        $ReservationManager = new Reservation_Booking($wpdb);

        $paramsGenerated = validateParams($_POST);
        $paramsGenerated['id_package'] = $packageId;

        $isSaved = $ReservationManager->save($paramsGenerated);

        if (!$isSaved) {
            throw new Exception($translation['RESERVATION_FAILS_ERROR_MESSAGE']);
        }

        // get the admin email
        $to = "johudergb@gmail.com";//get_option("antours_email_receivers");
        $subject = $translation['RESERVATION_SUBJECT_EMAIL_ADMIN'];
        $subjectCustomer = $translation['RESERVATION_SUBJECT_EMAIL_CUSTOMER'];
        $content = array(
            'namespace' => 'reservation',
            'data' => $paramsGenerated,
            'post' => $post
        );
        $contentCustomer = array(
            'namespace' => 'reservation-customer',
            'data' => $paramsGenerated,
            'post' => $post,
            'price' => $price,
            'thumbnail' => $thumbnail_url
        );
        // notify by email
        $status = wp_mail($to, $subject, $content);
        $statusCustomer = wp_mail($to, $subjectCustomer, $contentCustomer);

        $response = array(
            'sent' => $status,
            'sentToCustomer' => $statusCustomer
        );

        wp_send_json_success($response);
    } catch (Exception $error) {
        status_header(400);
        wp_send_json_error($error->getMessage());
    }

    wp_die();
}

function renderQuickFields($dataname, $data_post_id) {
    global $quickFormFields;
    
    if (!isset($dataname) || !isset($data_post_id)) {
        return;
    }

    $fields = array();
    foreach($quickFormFields as $key => $field) {
        $attributes = $field['attributes'];
        $attributesString = array_reduce(array_keys($field['attributes']), function($last, $attribute) use ($attributes){
            $value = $attributes[$attribute];
            $last .= $attribute === "required" ? "{$attribute} " : "{$attribute}='{$value}' ";

            return  $last;
        }, "");
        $field = "<div class='form-group wrapper-field'><input {$dataname}='{$data_post_id}' {$attributesString} /> <span class='notifier d-block help-block alert-danger'></span> </div>";
        array_push($fields, $field);
    }

    $render = implode("", $fields);

    return $render;
}

function validateParams($params) {
    global $quickFormFields;

    $values = array();

    foreach($params as $key => $value) {
        $field = $quickFormFields[$key];
        $keyname = $field['schema'];
        if(!is_null($keyname)) {
            $val = is_numeric($value) ? intval($value) : $value;
            $values[$keyname] = $val;
        }
    }

    $values['status'] = 1;

    return $values;
}

function extractQuickFieldMap() {
    global $quickFormFields;

    $validators = array();
    foreach($quickFormFields as $key => $field) {
        $js_function = $field['js_function'];
        $name = $field['attributes']['name'];

        if (isset($js_function) && isset($name)) {
            $validators[$name] = array(
                'js_func' => $js_function,
                'attributes' => $field['attributes'],
                'error' => $field['error']
            );
        }
    }

    return $validators;
}

// add new filter for telephone

add_filter("getTelephoneNumber", "renderAsTelephoneNumber", 10);

function formatNumber($number) {
    $numberFormatted = $number;
    if (preg_match('/^[0-9]{11}$/', $numberFormatted)) {
        $code = substr($numberFormatted, 0 ,2);
        $code = '(+' . $code . ")";
        $numbers = substr($numberFormatted, 2);
        $numberFormatted = $code . " " . $numbers;
    }

    return $numberFormatted;
}

function renderAsTelephoneNumber($number) {
    $numberForRender = formatNumber($number);
    $telAsHTML = "<a href='tel:%s' class='link-telephone'>%s</a>";
    return sprintf($telAsHTML, $number, $numberForRender);
}

// add new filter for email

add_filter("getEmail", "renderAsEmail", 10);

function renderAsEmail($email) {
    $emailAsHTML = "<a href='mailto:%s' class='link-email'>%s</a>";
    return sprintf($emailAsHTML, $email, $email);
}

function call_booking_method($method) {
    if (class_exists("Antours_CarBooking_API")) {
        $args = func_get_args();
        $args = array_slice($args, 1);

        $results = call_user_func_array(array(Antours_CarBooking_API, $method), $args);
        
        if (is_array($results) && count($results) > 0) {
            return $results;
        }
    }

    return false;
}

function generateSelectByMethod($method, $id_name, $name, $attrs) {
    $placeholder = isset($attrs['placeholder']) ? $attrs['placeholder'] : null;
    unset($attrs['placeholder']);

    $attributes = array_map(function($key, $value) {
        return $key . "=" . "'{$value}'";
    }, array_keys($attrs), $attrs);

    $attributes = join(' ', $attributes);

    $select = "<select class='form-control form-control-sm form-control-border t-input-control' id='{$method}' {$attributes}>";
    $selectInput = array($select);

    $default = $placeholder ? "<option selected value=''>{$placeholder}</option>" : '';
    array_push($selectInput, $default);

    if (class_exists("Antours_CarBooking_API")) {
        $args = func_get_args();
        $args = array_slice($args, 4);

        $results = call_booking_method($method, $args);
        
        if (is_array($results)) {
            foreach($results as $index => $item) {
                $option = "<option value='{$item->{$id_name}}'>{$item->{$name}}</option>";
                array_push($selectInput, $option);
            }
        }
    }

    array_push($selectInput, "</select>");

    $select = join("", $selectInput);

    return $select;
}

add_action( 'wp_ajax_nopriv_' . $getCommuneByCityIdAction, $getCommuneByCityIdAction );
add_action( 'wp_ajax_' . $getCommuneByCityIdAction, $getCommuneByCityIdAction );

function get_commune_by_city() {
    $nonce = $_POST['nonce'];
    $cityId = $_POST['cityId'];
    global $getCommuneByCityIdAction;

    try {
        $isCorrectNonce = wp_verify_nonce($nonce, $getCommuneByCityIdAction);

        if (!$isCorrectNonce) {
            throw new Exception("Bad nonce code" . $isCorrectNonce);
        }

        $result = call_booking_method('getCommuneByCityId', $cityId);

        if (!$result) {
            throw new Exception("not results found");
        }

        wp_send_json_success($result);

    } catch (Exception $error) {
        status_header(400);
        wp_send_json_error($error->getMessage());
    }

    wp_die();
}

//call_booking_method('getServicesByCommuneId', 115);