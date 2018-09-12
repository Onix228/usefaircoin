<?php

require_once( '../site/wp/wp-load.php' );

function get_geolocation($location) {
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


// get all posts
$posts = get_posts( array (  'numberposts' => -1, 
                             'post_type' => 'job_listing' )
);

foreach ( $posts as $post )
{
    if(!$post->geolocation_lat || !$post->geolocation_long) {
        if($post->_job_location != "") {
                //printf("%s / %s / %s ( lat: %s / lon: %s )\n", $post->ID, $post->post_title, $post->_job_location, $post->geolocation_lat, $post->geolocation_long);

                $geolocation = get_geolocation($post->_job_location);
                $decoded = json_decode($geolocation);
                $hits = count($decoded);
                $lat = $decoded->hits[0]->_geoloc->lat;
                $lon = $decoded->hits[0]->_geoloc->lng;
                printf("id,found,name,address,lat,lon\n");
                printf("%s, %s,\"%s\",\"%s\",%s,%s\n", $post->ID, $hits, $post->post_title, $post->_job_location, $lat, $lon);
                if($lat && $lon) {
                        update_post_meta( $post->ID, 'geolocation_lat', $lat );
                        update_post_meta( $post->ID, 'geolocation_long', $lon );
                }   
                #print_r($decoded->hits[0]);
        }   
    }   
}
