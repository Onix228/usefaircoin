<?php

require_once( '../web/wp-load.php' );

// get all posts
$posts = get_posts( array (  'numberposts' => -1,
                             'post_type' => 'job_listing' )
);

foreach ( $posts as $post )
{
    if($post->geolocation_lat || $post->geolocation_long) {
    {   

        $category = get_the_terms( get_the_ID(), 'job_listing_category' );

        $icon_url = listable_get_term_icon_url( $category[0]->name );
        $attachment_id = listable_get_term_icon_id( $category[0]->term_id );
        $image_url = get_attached_file( $attachment_id );
        $image_filename = basename($image_url);
        if(!file_exists($image_url)) {
                printf("* URL : %s\n", $image_url);
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
                'address' => $post->geolocation_formatted_address,
                ));
        }
    }
}

