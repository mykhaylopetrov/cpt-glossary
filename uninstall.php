<?php

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

/**
 * https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/
 * https://www.webroomtech.com/how-to-deregister-custom-post-type-or-custom-taxonomy/
 */ 
add_action( 'init', function() {
    unregister_post_type( 'glossary' );
    unregister_taxonomy( 'glossarycat' );
});