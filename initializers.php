<?php

/* Initialize widgets */

function registerFridxAreasWidget() {
    return register_widget( 'FridxAreasWidget' );
}
function registerFridxSearchWidget() {
    return register_widget( 'FridxSearchWidget' );
}
function registerFridxTopListingsWidget() {
    return register_widget( 'FridxTopListingsWidget' );
}
add_action( 'widgets_init', 'registerFridxAreasWidget' );
add_action( 'widgets_init', 'registerFridxSearchWidget' );
add_action( 'widgets_init', 'registerFridxTopListingsWidget' );

/* Set up params for fr_idx custom post type */
function parameter_queryvars( $qvars ) {
    $qvars[] = 'ListingID';
    $qvars[] = 'FullStreetAddress';
    $qvars[] = 'city';
    $qvars[] = 'min_price';
    $qvars[] = 'max_price';
    $qvars[] = 'beds';
    $qvars[] = 'baths';
    $qvars[] = 'building_size';
    $qvars[] = 'zip_code';
    $qvars[] = 'community';
    $qvars[] = 'tract';
    $qvars[] = 'mls_number';

    return $qvars;
}
add_filter( 'query_vars', 'parameter_queryvars' );

?>
