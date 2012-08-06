<?php
/*
Plugin Name: Fall River IDX
Plugin URI:
Description: Gets information from the Fall River IDX api. To setup: 1) Activate plugin, 2) <a href="admin.php?page=fridx-activation-menu">Activate using your API key</a> 3) Go to <a href="options-permalink.php">permalinks settings</a> and click 'save changes' to refresh your permalinks.
Version: 0.3
Author: Dustin Boling Associates
Author URI: http://www.dustinboling.com
License: 
 */

// high priority
/* TODO: add default image for properties, probably just add it to the plugin dir */
/* TODO: set title on single property page, wp_title filter */
/* TODO: do not show empty fields in summary data */
/* TODO: add delay on request of ~200 ms for map_idle callbacks */
// TODO: search page: style fridx-search-params (maybe something like bootstrap's flash messages)
// TODO: a search that returns 0 results should not log a fail.

// Ideas for options panel
// Search Page:
//  - change title of 'search again' widget
//  - disable 'search again' widget
//  - disable query synopsis 

// for later
/* TODO: clean up on an uninstall */
/* TODO: add tract to search widget */
/* TODO: search page: handle state=UNK cases if it is still a problem with final data */
// TODO: convert all widget requests to javascript (for concurrency)

include 'widgets/idx_top_listings_widget.php';
include 'widgets/idx_cities_widget.php';
include 'widgets/idx_search_widget.php';
include 'property_index_shortcode.php';
include 'single_property_shortcode.php';
include 'custom_post_types.php';
include 'admin_menus.php';
include 'initializers.php';
include 'scripts.php';
include 'redirects.php';
include 'helpers.php';

define("FALL_RIVER_TOKEN", get_option( 'fridx_token' ) );

function fridx_render_single_listing_title() {
    global $wp;
    if ( isset ( $wp->query_vars[ "post_type" ] ) ) {
        if ( $wp->query_vars[ "post_type" ] == "fridx_listing" ) {
            if ( isset ( $wp->query_vars[ 'name' ] ) ) {
                $title = fridx_titleize_from_slug( $wp->query_vars[ 'name' ] ); 
                $title .= " - ";
                return $title;
            } else {
                $title = "Single Property Listing - ";
                return $title;
            }
        }
    }
}
add_filter( 'wp_title', 'fridx_render_single_listing_title', 10, 3 );

/* Global counters */
function update_listing_counter() {
    global $listing_counter;
    $listing_counter++;
}
$listing_counter = 1;

/* HTTP "referer" hack: see above for alternative */
/* also: gets json objects */
function fridx_get_json_object( $jsonurl ) {
    global $wp;

    $referer_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
    $referer_ary = explode('/', $referer_url);
    $referer_slice = array_slice( $referer_ary, 0, 4 );
    $url = implode('/', $referer_slice);
    $opts = array( 
        'http' => array( 
            'header' => array( "Referer: $url\r\n" )
        )
    );
    $context = stream_context_create( $opts );
    // TODO: add @ before file_get_contents to suppress error messages
    $json = file_get_contents($jsonurl, false, $context);

    if ( $json === FALSE ) {
        echo 'Failed to retrieve json object.';
    } else { 
        $json_output = json_decode($json, true);
        return $json_output;
    }
}

/* Photo Gallery */
// TODO: add support for images > 16 (style gets messed up, add pager?) 
// TODO: api needs to have thumbnails added to it
// TODO: add loading screen for thumbnails
// TODO: don't send images w/o the proper mime type over the wire - at all.
// Loads the nivo-slider photo gallery if there are enough images
// Loads a placeholder photo if there are no images
function fridx_photo_gallery( $img_ary ) {
    if ( count( $img_ary ) > 0 ) {
        $gallery = '<div class="slider-wrapper theme-default controlnav-thumbs fridx-row">';
        $gallery .= '<div class="ribbon"></div>';
        $gallery .= '<div id="fridx-slider" class="nivo-slider fridx-sixcol">';
        $i = 0;
        foreach( $img_ary as $img ) {
            if ( $img['PropMimeType'] == "IMAGE_BMP" ||
                 $img['PropMimeType'] == "IMAGE_GIF" ||
                 $img['PropMimeType'] == "IMAGE_PNG" ||
                 $img['PropMimeType'] == "IMAGE_JPEG" ||
                 $img['PropMimeType'] == "IMAGE_TIFF" ) {
                if ( $img['PropMediaURL'] == "" ) {
                    $gallery .= '<img src="' . plugins_url() . '/fallriveridx/images/house-default.png" />';
                } else {
                    if ( $i == 0 ) {
                        $gallery .= '<img src="' . $img["PropMediaURL"] . '" data-thumb="' . $img['PropMediaURL'] . '" alt="" />';
                    } else if ( $i > 15 ) {
                        // skip images > 16
                    } else {
                        $gallery .= '<img src="' . $img["PropMediaURL"] . '" data-thumb="' . $img['PropMediaURL'] . '" alt="" style="display: none;"/>';
                    }
                    $i  = $i + 1;
                }
            } else {
                // do nothing if prop_media is not an image 
            }
        }
        $gallery .= '</div></div>';

        return $gallery;
    } else {
        $gallery = '<div id="fridx-photo-gallery">';
        $gallery .= '<div id="fridx-primary-photo">';
        $gallery .= '<img src="' . plugins_url() . '/fallriveridx/images/house-default.png" />';
        $gallery .= '</div></div>';

        return $gallery;
    }
}

?>
