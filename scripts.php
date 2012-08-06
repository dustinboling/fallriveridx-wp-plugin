<?php

/* Add plugin stylesheets */
function add_fr_idx_stylesheets() {
    wp_register_style( 'fr_idx-style', plugins_url('stylesheets/style.css', __FILE__) );
    wp_enqueue_style( 'fr_idx-style' );
    wp_register_style( '1140', plugins_url('stylesheets/1140.css', __FILE__) );
    wp_enqueue_style( '1140' );
    wp_register_style( '1140-ie', plugins_url('stylesheets/ie.css', __FILE__) );
    wp_enqueue_style( '1140-ie' );
    wp_register_style( '1140-adds' , plugins_url('stylesheets/1140-adds.css', __FILE__) );
    wp_enqueue_style( '1140-adds' );
    wp_register_style( 'jquery-ui', plugins_url('stylesheets/jquery-ui.css', __FILE__) );
    wp_enqueue_style( 'jquery-ui' );
}
add_action( 'wp_enqueue_scripts', 'add_fr_idx_stylesheets' );

/* Add plugin javascripts */
function add_fr_idx_javascripts() {
    wp_enqueue_script( 'jquery' );
    wp_register_script( 'css3-mediaqueries', plugins_url('/js/css3-mediaqueries.js', __FILE__) );
    wp_enqueue_script( 'css3-mediaqueries' );
    wp_register_script( 'tabs', plugins_url('js/tabs.js', __FILE__) );
    wp_enqueue_script( 'tabs' );
    wp_register_script( 'fridx-maps', plugins_url('js/fridx-maps.js', __FILE__) );
    wp_register_script( 'fridx-fluster', plugins_url('js/Fluster2.packed.js', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'add_fr_idx_javascripts');

/* Add admin javascripts */
function add_fr_idx_admin_javascripts() {
    wp_register_script( 'chosen', plugins_url('js/chosen.jquery.min.js', __FILE__) );
    wp_enqueue_script( 'chosen' );
    wp_register_script( 'fridx-widgets', plugins_url('js/fridx-widgets.js', __FILE__) );
    wp_enqueue_script( 'fridx-widgets' );
}
add_action( 'admin_enqueue_scripts', 'add_fr_idx_admin_javascripts' );

/* Add admin stylesheets */
function add_fr_idx_admin_stylesheets() {
    wp_register_style( 'chosen-css', plugins_url('/css/chosen.css', __FILE__) );
    wp_enqueue_style( 'chosen-css' );
}
add_action( 'admin_enqueue_scripts', 'add_fr_idx_admin_stylesheets' );

// enqueue token script for activation menu
function add_fridx_token_ajax() {
    wp_register_script( 'fridx-token-ajax', plugins_url( '/js/fridx-token-ajax.js', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'add_fridx_token_ajax' );

// add javascript initialize onload of fridx_maps post type only
// TODO: remove this or remove the <script> from map template, and add it above
function add_body_onload($c) { 
    global $wp;

    if ( isset ( $wp->query_vars[ "post_type" ] ) && $wp->query_vars[ "post_type" ] == 'fridx_maps' ) {
        $c[] = '" onload="initialize();"';
        return $c;
    }
}
add_filter('body_class', 'add_body_onload');

?>
