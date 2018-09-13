<?php

require_once( '../site/wp/wp-load.php' );

$post_id = $argv[1];

$post = get_post($post_id);

if($post_id) {
	printf("Address: %s\n", $post->_job_location);
	ufc_update_post($post);
	printf("Lat: %s / Lon: %s\n", $post->geolocation_lat, $post->geolocation_long);
} else {
	// get all posts
	$posts = get_posts( array (  'numberposts' => -1,
	                             'post_type' => 'job_listing' )
	);

	foreach ( $posts as $post )
	{
		ufc_update_post($post);
	}
}
