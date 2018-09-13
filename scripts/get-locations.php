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

                $post_url      = get_site_url().'/?p='.$post->ID;
                $post_edit_url = admin_url( 'post.php?post='.$post->ID.'&action=edit&lang=en' );
                # for librecalc
                #printf("%s, \"%s\",\"%s\",\"=HIPERVÍNCULO(\"\"%s\"\";\"\"edit\"\")\"\n", $post->ID, $post->post_title, $post->_job_location, $post_edit_url);

                # for ethercalc
                printf("%s, \"%s\",\"%s\",\" \"edit\"<%s>\"\n", $post->ID, $post->post_title, $post->_job_location, $post_edit_url);
        }
}
