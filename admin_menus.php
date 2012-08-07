<?php

/* Activation menu: It activates a site with a fall river idx key. */

// Activate main menu
function activate_fridx_menu() {
    add_menu_page( 'Fall River IDX', 'Fall River IDX', 'manage-options', 'fridx-menu', 'fridx_menu' );
}
add_action( 'admin_menu', 'activate_fridx_menu' );

// Activate activation menu
function activate_fridx_activation_menu() {
    add_submenu_page( 'fridx-menu', 'Fall River Activation', 'Activation', 'manage_options', 'fridx-activation-menu', 'fridx_activation_menu' );
}
add_action( 'admin_menu', 'activate_fridx_activation_menu' );

// Activate maps menu
function activate_fridx_maps_menu() {
    add_submenu_page( 'fridx-menu', 'Fall River Maps', 'Maps', 'manage_options', 'fridx-maps-menu', 'fridx_maps_menu' );
}
add_action( 'admin_menu', 'activate_fridx_maps_menu' );

// Register settings
function register_fridx_settings() {
    register_setting( 'baw-settings-group', 'fridx_token' );
    register_setting( 'maps-settings-group', 'fridx_maps_center' );
    register_setting( 'maps-settings-group', 'fridx_maps_center_geocode' );
}
add_action( 'admin_init', 'register_fridx_settings' );

// Include menus
function fridx_activation_menu() {
    include dirname(__FILE__).'/fridx_activation_menu.php';
}
function fridx_maps_menu() {
    include dirname(__FILE__).'/fridx_maps_menu.php';
}
function fridx_menu() {
    include dirname(__FILE__).'/fridx_menu.php';
}

?>
