<?php

require_once( '../site/wp/wp-load.php' );

// get all posts
$posts = get_posts( array (  'numberposts' => -1, 
                             'order' => 'ASC',
                             'orderby' => 'title',
                             'post_type' => 'job_listing' )
);

printf("id,name,address\n");
foreach ( $posts as $post )
{
        if($post->_job_location != "") {

                printf("%s, \"%s\",\"%s\"\n", $post->ID, $post->post_title, $post->_job_location);
        }   
}
