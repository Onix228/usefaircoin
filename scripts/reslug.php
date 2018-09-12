<?php

require_once( '../web/wp-load.php' );

// get all posts
$posts = get_posts( array (  'numberposts' => -1 ) );

foreach ( $posts as $post )
{
    // check the slug and run an update if necessary 
    $new_slug = sanitize_title( $post->post_title );
    if ( $post->post_name != $new_slug )
    {   
        wp_update_post(
            array (
                'ID'        => $post->ID,
                'post_name' => $new_slug
            )
        );
    }
}

