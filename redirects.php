<?php

/* Add fr-idx_archive.php & fridx_maps.php to environment */
function do_theme_redirect($url) {
    global $post, $wp_query;
    include( $url );
    die();
}

function fr_idx_redirect() {
    global $wp;
    $plugindir = dirname( __FILE__ );

    if ( isset ( $wp->query_vars[ "post_type" ] ) && $wp->query_vars[ "post_type" ] == 'fridx_listing' ) {
        $templatefilename = 'archive-fr_idx.php';
        if ( file_exists ( TEMPLATEPATH . '/' . $templatefilename ) ) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        do_theme_redirect( $return_template );
    }
}
add_action( 'template_redirect', 'fr_idx_redirect' );

function fridx_maps_redirect() {
    global $wp;
    $plugindir = dirname( __FILE__ );

    if ( isset ( $wp->query_vars[ "post_type" ] ) && $wp->query_vars[ "post_type" ] == 'fridx_maps' ) {
        $templatefilename = 'archive-fridx_maps.php';
        if ( file_exists ( TEMPLATEPATH . '/' . $templatefilename ) ) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        do_theme_redirect( $return_template );
    }
}
add_action( 'template_redirect', 'fridx_maps_redirect' );

function fridx_search_redirect() {
    global $wp;
    $plugindir = dirname( __FILE__ );

    if ( isset ( $wp->query_vars[ "post_type" ] ) && $wp->query_vars[ "post_type" ] == 'fridx_search' ) {
        $templatefilename = 'archive-fridx_search.php';
        if ( file_exists ( TEMPLATEPATH . '/' . $templatefilename ) ) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        do_theme_redirect( $return_template );
    }
}
add_action( 'template_redirect', 'fridx_search_redirect' );

?>
