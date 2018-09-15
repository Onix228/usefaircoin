<?php
/**
 * Listable Child functions and definitions
 *
 * Bellow you will find several ways to tackle the enqueue of static resources/files
 * It depends on the amount of customization you want to do
 * If you either wish to simply overwrite/add some CSS rules or JS code
 * Or if you want to replace certain files from the parent with your own (like style.css or main.js)
 *
 * @package ListableChild
 */




/**
 * Setup Listable Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
function listable_child_theme_setup() {
	load_child_theme_textdomain( 'listable-child-theme', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'listable_child_theme_setup' );





/**
 *
 * 1. Add a Child Theme "style.css" file
 * ----------------------------------------------------------------------------
 *
 * If you want to add static resources files from the child theme, use the
 * example function written below.
 *
 */

function listable_child_enqueue_styles() {
	$theme = wp_get_theme();
	// use the parent version for cachebusting
	$parent = $theme->parent();

	if ( is_rtl() ) {
		wp_enqueue_style( 'listable-rtl-style', get_template_directory_uri() . '/rtl.css', array(), $parent->get( 'Version' ) );
	} else {
		wp_enqueue_style( 'listable-main-style', get_template_directory_uri() . '/style.css', array(), $parent->get( 'Version' ) );
	}

	// Here we are adding the child style.css while still retaining
	// all of the parents assets (style.css, JS files, etc)
	wp_enqueue_style( 'listable-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('listable-style') //make sure the the child's style.css comes after the parents so you can overwrite rules
	);
}

add_action( 'wp_enqueue_scripts', 'listable_child_enqueue_styles' );





/**
 *
 * 2. Overwrite Static Resources (eg. style.css or main.js)
 * ----------------------------------------------------------------------------
 *
 * If you want to overwrite static resources files from the parent theme
 * and use only the ones from the Child Theme, this is the way to do it.
 *
 */


/*

function listable_child_overwrite_files() {

	// 1. The "main.js" file
	//
	// Let's assume you want to completely overwrite the "main.js" file from the parent

	// First you will have to make sure the parent's file is not loaded
	// See the parent's function.php -> the listable_scripts_styles() function
	// for details like resources names

		wp_dequeue_script( 'listable-scripts' );


	// We will add the main.js from the child theme (located in assets/js/main.js)
	// with the same dependecies as the main.js in the parent
	// This is not required, but I assume you are not modifying that much :)

		wp_enqueue_script( 'listable-child-scripts',
			get_stylesheet_directory_uri() . '/assets/js/main.js',
			array( 'jquery' ),
			'1.0.0', true );



	// 2. The "style.css" file
	//
	// First, remove the parent style files
	// see the parent's function.php -> the hive_scripts_styles() function for details like resources names

		wp_dequeue_style( 'listable-style' );


	// Now you can add your own, modified version of the "style.css" file

		wp_enqueue_style( 'listable-child-style',
			get_stylesheet_directory_uri() . '/style.css'
		);
}

// Load the files from the function mentioned above:

	add_action( 'wp_enqueue_scripts', 'listable_child_overwrite_files', 11 );

// Notes:
// The 11 priority parameter is need so we do this after the function in the parent so there is something to dequeue
// The default priority of any action is 10

*/

function listable_child_overwrite_files() {
        $google_api_key = get_option('gaaf_field_api_key');
	wp_deregister_script('gaaf-custom');
	wp_enqueue_script('gaaf-custom', get_stylesheet_directory_uri().'/assets/js/gaaf-custom.js',array('jquery-core','jquery'),'',true);
	wp_deregister_script('google-maps');
	wp_enqueue_script('google-maps','https://maps.googleapis.com/maps/api/js?key='.(!empty($google_api_key) ? $google_api_key : 'AIzaSyB16sGmIekuGIvYOfNoW9T44377IU2d2Es').'&libraries=places&language=en',array('jquery-core','jquery','gaaf-custom'),'',true);
}
add_action( 'wp_enqueue_scripts', 'listable_child_overwrite_files', 11 );

//add_action( 'wp_enqueue_scripts', 'gaaf_google_enqueue_scripts2', 999 );
function gaaf_google_enqueue_scripts2() {
        $google_api_key = get_option('gaaf_field_api_key');
	wp_enqueue_script('gaaf-custom2', get_stylesheet_directory_uri().'/assets/js/gaaf-custom.js',array('jquery-core','jquery'),'',true);
	wp_deregister_script('google-maps');
        wp_enqueue_script('google-maps','https://maps.googleapis.com/maps/api/js?key='.(!empty($google_api_key) ? $google_api_key : 'AIzaSyB16sGmIekuGIvYOfNoW9T44377IU2d2Es').'&libraries=places',array('jquery-core','jquery','gaaf-custom'),'',true);

}


function admin_google_maps() {
	//wp_enqueue_script('google-maps2','https://maps.googleapis.com/maps/api/js?key='.(!empty($google_api_key) ? $google_api_key : 'AIzaSyB16sGmIekuGIvYOfNoW9T44377IU2d2Es').'&libraries=places&language=en',array('jquery-core','jquery','gaaf-custom'),'',true);
	listable_child_overwrite_files();
}
add_action( 'admin_enqueue_scripts', 'admin_google_maps', 11 );


// Adds login buttons to the wp-login.php pages
function add_wc_social_login_buttons_wplogin() {

	// Displays login buttons to non-logged in users + redirect back to login
	if(function_exists("woocommerce_social_login_buttons")) {
		woocommerce_social_login_buttons();
	}

}
add_action( 'login_form', 'add_wc_social_login_buttons_wplogin' );
add_action( 'register_form', 'add_wc_social_login_buttons_wplogin' );

// Changes the login text from what's set in our WooCommerce settings so we're not talking about checkout while logging in.
function change_social_login_text_option( $login_text ) {

	global $pagenow;

	// Only modify the text from this option if we're on the wp-login page
	if( 'wp-login.php' === $pagenow ) {
		// Adjust the login text as desired
		$login_text = esc_html__( 'You can also create an account with a social network.', 'woocommerce-social-login' );
	}

 	return $login_text;
}
add_filter( 'pre_option_wc_social_login_text', 'change_social_login_text_option' );

function listable_sync_to_mapsmarkers($post) {
	if(!$post instanceof WP_Post)
		$post = get_post( $post );
	if( 'job_listing' != get_post_type( $post ) ) {
		return;
	}

	if(!($post->geolocation_lat && $post->geolocation_long))
		return;


	global $wpdb;

        $category = get_the_terms( get_the_ID(), 'job_listing_category' );

        $icon_url = listable_get_term_icon_url( $category[0]->name );
        $attachment_id = listable_get_term_icon_id( $category[0]->term_id );
        $image_url = get_attached_file( $attachment_id );
        printf("* IMAGE: %s\n", $image_url);

        if(!file_exists($image_url)) return;

        $image_filename = basename($image_url);
        if(!file_exists($image_url)) {
                printf("* Marker image not found : %s\n", $image_url);
        }

	$wpdb->insert(
	'upleafletmapsmarker_markers',
		array(
		'markername' => $post->post_title,
		'basemap' => 'osm_mapnik',
		'layer' => '1',
		'lat' => $post->geolocation_lat,
		'lon' => $post->geolocation_long,
		'icon' => $image_filename,
		'popuptext' => '<strong><a href=\"'.get_post_permalink($post).'/\">'.$post->post_title.'</a></strong>',
		'zoom' => 11,
		'mapwidth' => 640,
		'mapwidthunit' => 'px',
		'mapheight' => 480,
		'panel' => 1,
		'createdby' => 'Admin',
		'controlbox' => 1,
		'address' => $post->geolocation_street,
		)
	);

}
add_action('pending_to_publish', 'listable_sync_to_mapsmarkers');
add_action('pending_payment_to_publish', 'listable_sync_to_mapsmarkers');

function algolia_autocomplete($hook) {
    if ( 'post.php' != $hook) {
        return;
    }

    wp_enqueue_script( 'algolia_places', "https://cdn.jsdelivr.net/npm/places.js@1.9.1");
    wp_enqueue_script( 'algolia_geocoding', get_stylesheet_directory_uri() . '/assets/js/geocoding-autocomplete.js', null, null, true );
}
//add_action( 'admin_enqueue_scripts', 'algolia_autocomplete', 'in_footer');

function algolia_geocode( $post_id ) {
    apply_filters( 'job_manager_geolocation_enabled', false );
    $post = get_post($post);
    ufc_update_post($post);
    listable_sync_to_mapsmarkers($post);
}
//add_action('job_manager_save_job_listing', 'algolia_geocode', 20, 2 );

function set_location_coords( $post_id ) {
        $post = get_post($post_id);
	update_post_meta( $post->ID, 'geolocation_street', $post->_job_location );
	if(!empty($_COOKIE['job_location_lat'])) {
		update_post_meta( $post->ID, 'geolocation_lat', $_COOKIE['job_location_lat'] );
		unset($_COOKIE['job_location_lat']);
	}
	
	if(!empty($_COOKIE['job_location_lon'])) {
		update_post_meta( $post->ID, 'geolocation_long', $_COOKIE['job_location_lon'] );
		unset($_COOKIE['job_location_lon']);
	}
}
add_action('job_manager_job_submitted', 'set_location_coords', 20, 2 );
add_action('job_manager_save_job_listing', 'set_location_coords', 20, 2 );
add_action('job_manager_save_job_listing', 'listable_sync_to_mapsmarkers', 20, 2);

function algolia_get_geolocation($location) {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        $post_data = array('query' => $location);
        $data_string = json_encode($post_data);

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://places-dsn.algolia.net/1/places/query',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        return $resp;
}

function ufc_update_post($post) {
    if(!$post instanceof WP_Post) {
    }

        if($post->_job_location != "") {

                $geolocation = ufc_get_geolocation($post->_job_location);
                $decoded = json_decode($geolocation);
                $hits = count($decoded);
                $lat = $decoded->hits[0]->_geoloc->lat;
                $lon = $decoded->hits[0]->_geoloc->lng;
                #printf("id,found,name,address,lat,lon\n");
                #printf("%s, %s,\"%s\",\"%s\",%s,%s\n", $post->ID, $hits, $post->post_title, $post->_job_location, $lat, $lon);
                if($lat && $lon) {
                        update_post_meta( $post->ID, 'geolocation_lat', $lat );
                        update_post_meta( $post->ID, 'geolocation_long', $lon );
                }
        } 
}


/*
Display the full address (set from post->_job_location to geolocation_street meta value)
https://pixelgrade.com/docs/faq/modify-listing-address-format/
*/
function custom_listing_address($formats) {
	$formats = array(
		'default' => '<div itemprop="streetAddress">
					<span class="address__street">{geolocation_street}</span>
				</div>
				<span class="address__country-short" itemprop="addressCountry">{geolocation_country_short}</span>');
	return $formats;
}
add_filter('listable_localisation_address_formats', 'custom_listing_address', 15);
