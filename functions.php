<?php
session_start();
// import custom Error classes
include_once(get_template_directory() . "/system/fields.php");
include_once(get_template_directory() . "/system/AntoursBanners.php");

$prefix = "antours";
$domain = $prefix;

$metabox_prefix = $prefix."_mtx_";

$commentLimit = get_option( 'posts_per_page' );
$nonceToLoadComments = 'load-comment-per-post';
$loadingText = __('Loading comment text', $domain);
$loaderText = __('Loader comment text', $domain);

$actionNameContactForm = 'send_notification_contact_form';
$nonceToContactForm = 'nonce-contact-form-antours';
$loadingContactForm = __('Loading contact button text', $domain);
$loaderContactForm = __('Loader contact button text', $domain);

$nonceToServices = 'nonce-services-per-taxonomy';
$serviceActionName = "load_services_taxo_posts";

$services = "at_servicios";
$packages = "at_paquetes";
$about = "at_nosotros";
$banners = "at_container_banner";
$home = "at_home";

$serviceImageLabel = "antours_service_background";
$packageFeaturedImage = "package_featured_image";
$bannerPostTypeSize = "banner_post_type_size";
$bannerPostTypeForTablets = "banner_post_type_tablets";
$bannerPostTypeForSmartPhones = "banner_post_type_smartphones";

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
    $original_img = "<img src='{$original_img}' class='img-responsive' />";
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
    global $nonceToServices, $serviceActionName;

    wp_enqueue_script("bootstrap-antours-js", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js", array( 'jquery' ), null, false);
    wp_enqueue_script("jquery-datepicker", loadAssetFromResourceDirectory("scripts/datepicker", "datepicker.min.js"));
    wp_enqueue_script("jquery-timepicker", "https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js");
    wp_enqueue_script("antours-scripts", loadAssetFromResourceDirectory("scripts", "antours.js"), array(), 1.0);
    wp_enqueue_script("google-maps", "https://maps.google.com/maps/api/js?key=AIzaSyA9otsw6Uersa3aTB9IMV0gxyeyytOHBtw");
    wp_enqueue_script("map-frontend", loadAssetFromResourceDirectory("scripts", "map.js"), array(), 1.0);

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

    wp_enqueue_style("bootstrap-antours", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css", array(), null, false);
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
    global $services, $packages, $about, $banners, $home, $domain;

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

    $aboutPostTypeArgs = array(
        'labels' => array(
            'name' => __('About menu label', $domain),
            'singular_name' => __('About menu singular label', $domain),
            'add_new' => __('About menu add new label', $domain),
            'not_found' => __('About menu not found label', $domain),
            'all_items' => __('About menu all items label', $domain),
            'add_new_item' => __('About menu add new item label', $domain),
            'featured_image' => __('About featured image', $domain),
            'set_featured_image' => __('About set featured image', $domain),
        ),
        'public' => true,
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
            'name' => __('Home menu label', $domain),
            'singular_name' => __('Home menu singular label', $domain),
            'add_new' => __('Home menu add new label', $domain),
            'not_found' => __('Home menu not found label', $domain),
            'all_items' => __('Home menu all items label', $domain),
            'add_new_item' => __('Home menu add new item label', $domain),
            'featured_image' => __('Home featured image', $domain),
            'set_featured_image' => __('Home set featured image', $domain),
        ),
        'public' => true,
        'has_archive' => false,
        'show_ui' => true,
        'supports' => array(
            'title',
            'editor'
        )
    );

    // Create custom size
    registerNewSizes();

    register_post_type($services, $servicePostTypeArgs);
    register_post_type($packages, $packagePostTypeArgs);
    register_post_type($about, $aboutPostTypeArgs);
    register_post_type($home, $homePostTypeArgs);
    register_post_types_banner();

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

    // register lightbox to be accesible for package single page
    wp_register_script('lightbox-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js');
    wp_register_style('lightbox-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css');
    // register lightbox to be accesible for package single page

    // register fontwesome only to be used in post type packages
    wp_register_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}

function register_post_types_banner() {
    global $services, $about, $home;
    $postTypes = array(
        'Service' => $services,
        'About' => $about,
        'Home' => $home
    );

    foreach($postTypes as $key => $namespace) {
        $CommonPostTypeArgs = array(
            'labels' => array(
                'name' => __('Banner '. $key .' menu label', $domain),
                'singular_name' => __('Banner '. $key .' menu singular label', $domain),
                'add_new' => __('Banner '. $key .' menu add new label', $domain),
                'not_found' => __('Banner '. $key .' menu not found label', $domain),
                'all_items' => __('Banner '. $key .' menu all items label', $domain),
                'add_new_item' => __('Banner '. $key .' menu add new item label', $domain),
                'featured_image' => __('Banner '. $key .' featured image', $domain),
                'set_featured_image' => __('Banner '. $key .' set featured image', $domain),
            ),
            'public' => true,
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

  //Get number of posts authored by user
  $args = array('post_type' => $about, 'fields'>'ids');
  $count = count(get_posts($args));

  global $submenu;
  unset($submenu['edit.php?post_type='.$about][10]);

    // Hide link on listing page
    /**/

  //Conditionally remove link:
  if($count > 0) {
        $page = remove_submenu_page( 'edit.php?post_type='. $about, 'post-new.php?post_type='. $about );
        if (isset($_GET['post_type']) && $_GET['post_type'] == $about) {
            echo '<style type="text/css"> .page-title-action { display: none!important; } </style>';
        }
  }
}

add_filter('rwmb_meta_boxes', 'RegisterMetaboxesInPackage');

function RegisterMetaboxesInPackage($meta_boxes) {
    global $prefix, $domain, $services, $packages, $home, $about, $metabox_prefix;
    $pre = $metabox_prefix;

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

    /*$meta_boxes[] = array(
        'id' => $pre . 'banners_services',
		'title'  => __( 'Banner Service', $domain ),
        'post_types' => array( $services.'_banner' ),
        'context'    => 'normal',
        'priority'   => 'high',
		'fields' => array(
			array(
				'id'   => $pre . $services,
				'name' => __( 'Banners Service', $domain ),
				'type' => 'image_advanced'
			)
		),
	);

    $meta_boxes[] = array(
        'id' => $pre . 'banners_about',
		'title'  => __( 'Banner About', $domain ),
        'post_types' => array( $about.'_banner' ),
        'context'    => 'normal',
        'priority'   => 'high',
		'fields' => array(
			array(
				'id'   => $pre . $about,
				'name' => __( 'Banners About', $domain ),
				'type' => 'image_advanced'
			)
		),
	);

    $meta_boxes[] = array(
        'id' => $pre . 'banners',
		'title'  => __( 'Banner Home', $domain ),
        'post_types' => array( $home.'_banner' ),
        'context'    => 'normal',
        'priority'   => 'high',
		'fields' => array(
			array(
				'id'   => $pre . $home,
				'name' => __( 'Banners home', $domain ),
				'type' => 'image_advanced'
			)
		),
	);*/

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

add_filter( 'wp_handle_upload_prefilter', 'checker_image_size' );

function checker_image_size( $file ) {
    global $post, $services, $_wp_additional_image_sizes, $serviceImageLabel, $customImageSizes, $domain;

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

add_action( 'wp_ajax_nopriv_' . $serviceActionName, 'load_service_posts' );
add_action( 'wp_ajax_' . $serviceActionName, 'load_service_posts' );

function load_service_posts() {
    global $packages, $wp_query;
    $page = $_POST['page'];
    $taxId = $_POST['taxID'];
    $postsPerPage = get_option('posts_per_page');

    $args = array('tax_query' => array( 
            array( 
                'taxonomy' => 'antours-category',
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
    global $domain, $nonceToContactForm;
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
        'error' => __("Not sending email error", $domain)
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
add_action( 'admin_menu', 'add_car_booking_menu' );

function add_car_booking_menu(){
    global $domain;
    $hook = add_menu_page( __('Car Booking List', $domain), __('Car Booking Menu Link', $domain), 'manage_options', 'car-booking', 'Car_Booking_List_View', 'dashicons-tickets-alt', 30 );
    add_action( "load-$hook", 'add_options' );
}

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