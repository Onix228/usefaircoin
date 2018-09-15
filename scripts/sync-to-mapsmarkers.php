<?php

require_once( __DIR__.'/../site/wp/wp-load.php' );

// get all posts
$posts = get_posts( array (  'numberposts' => -1,
                             'post_type' => 'job_listing' )
);

foreach ( $posts as $post )
{
    //print "* Updating post: ". $post->post_title;
    listable_sync_to_mapsmarkers($post);
}

