<?php
/**
 * Custom post type for a single listing
 * Also: rewrite rule for listing.
 */
function create_fridx_listing() {
    register_post_type( 'fridx_listing', 
        array(
            'labels' => array(
                'name' => __( 'Listing' ),
                'singluar_name' => ( 'Listing' )
            ),
            'public' => true,
            'exclude_from_search' => true,
            'show_ui' => false,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'idx/listing' ),
        )
    );
}
add_action( 'init', 'create_fridx_listing' );

function add_fridx_listing_rewrite() {
    add_rewrite_rule( '^idx/listing/([^/]*)/?', 'archive-fr_idx.php?FullStreetAddress=$matches[1]', 'top' );
}
add_action( 'init', 'add_fridx_listing_rewrite' );

/**
 * Custom post type for property maps
 */
function create_fridx_maps() {
    register_post_type( 'fridx_maps',
        array(
            'labels' => array(
                'singular_name' => ( 'Property Map' )
            ),
            'public' => true,
            'exclude_from_search' => true,
            'show_ui' => false,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'idx/map' ),
        )
    );
}
add_action( 'init', 'create_fridx_maps' );

/**
 * Custom post type for search (mainly used for search widget)
 * Also: rewrite rule for search
 */
function create_fridx_search() {
    register_post_type( 'fridx_search',
        array(
            'labels' => array(
                'singular_name' => ( 'Search' )
            ),
            'public' => true,
            'exclude_from_search' => true,
            'show_ui' => false,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'idx/search' ),
        )
    );
}
add_action( 'init', 'create_fridx_search' );

/**
 * Custom post type for areas
 */
function create_fridx_areas() {
    register_post_type( 'fridx_areas',
        array(
            'labels' => array(
                'singular_name' => ( 'Area' )
            ),
            'public' => true,
            'exclude_from_search' => true,
            'show_ui' => false,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'area' )
        )
    );
}

?>
