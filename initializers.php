<?php

/* Initialize widgets */
add_action( 'widgets_init', function() {
    return register_widget( 'FridxTopListingsWidget' );
});
add_action( 'widgets_init', function() {
    return register_widget( 'FridxSearchWidget' );
});
add_action( 'widgets_init', function() {
    return register_widget( 'FridxAreasWidget' );
});

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
