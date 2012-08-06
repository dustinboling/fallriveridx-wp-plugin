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
// include 'helpers.php';

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

/* String manipulation helpers */
function fridx_titleize( $title ) {
    $title_lower = strtolower( $title );
    $titleized_title = ucwords( $title_lower );

    return $titleized_title;
}

// TODO: this does not work in all cases...
function fridx_sentenceize( $str ) {
    $str_lower = strtolower( $str );
    $str_ary = explode( '.', $str_lower );

    $upper_str_ary = array();
    foreach ( $str_ary as $sentence ) {
        $upper_sentence = ucfirst( $sentence );
        $upper_str_ary[] = $upper_sentence;
    }

    $str_sentenceized = implode( '.', $upper_str_ary );
    return $str_sentenceized;
}

function fridx_capitalize_query( $str ) {
    global $str_upcase;

    $str_ary = explode( ' ', $str );
    $ary_upcase = array();

    foreach ( $str_ary as $slice ) {
        $upslice = ucfirst( $slice );
        $ary_upcase[] = $upslice;
    }

    $str_upcase = implode( '%20', $ary_upcase );
    return $str_upcase;
} 

function fridx_to_slug( $str ) {
    global $str_slugged;

    $str_ary = explode( ' ', $str );
    $str_slugged = implode( '-', $str_ary );
    return $str_slugged;
}

function fridx_from_slug( $str ) {
    global $str_deslugged;

    $str_ary = explode( '-', $str );
    $str_deslugged = implode( '%20', $str_ary );
    return $str_deslugged;
}

function fridx_parse_listing_id( $str ) {
    global $listing_id;

    $str_ary = explode( '-', $str );
    $listing_id = $str_ary[0];
    return $listing_id;
}

function fridx_titleize_from_slug( $str ) {
    global $str_deslugged;

    $str_ary = explode( '-', $str );
    $str_deslugged = implode( ' ', $str_ary );
    return $str_deslugged;
}

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

/* Makes a select box using a name and an options key=>value array */
function fridx_select_widget( $name, $options_ary, $fname, $fid, $instance ) {
    $select = '<select name="' . $fname . '" id="' . $fid . '" style="width:100%;">';
    foreach ( $options_ary as $key => $value ) {
        if ( isset ( $instance[ $name ] ) && $instance[ $name ] == $value ) {
            $select .= '<option value="' . $value . '" selected="selected"' . '>' . $key . '</option>';
        } else {
            $select .= '<option value="' . $value . '">' . $key . '</option>';
        }
    }
    $select .= '</select>';
    return $select;
}

/* Displays a disclaimer based on a feed */
function fridx_disclaimer() {
    $disclaimer = '<p>';
    // TODO: different disclaimers for different RETS
    $disclaimer .= "The information being provided by CARETS (CLAW, CRISNet MLS, DAMLS, CRMLS, i-Tech MLS, and/or VCRDS) is for the visitor's personal, non-commercial use and may not be used for any purpose other than to identify prospective properties visitor may be interested in purchasing.
        Any information relating to a property referenced on this web site comes from the Internet Data Exchange (IDX) program of CARETS. This web site may reference real estate listing(s) held by a brokerage firm other than the broker and/or agent who owns this web site.
        The accuracy of all information, regardless of source, including but not limited to square footages and lot sizes, is deemed reliable but not guaranteed and should be personally verified through personal inspection by and/or with the appropriate professionals. The data contained herein is copyrighted by CARETS, CLAW, CRISNet MLS, DAMLS, CRMLS, i-Tech MLS and/or VCRDS and is protected by all applicable copyright laws. Any dissemination of this information is in violation of copyright laws and is strictly prohibited.
        CARETS, California Real Estate Technology Services, is a consolidated MLS property listing data feed comprised of CLAW (Combined LA/Westside MLS), CRISNet MLS (Southland Regional AOR), DAMLS (Desert Area MLS), CRMLS (California Regional MLS), i-Tech MLS (Glendale AOR/Pasadena Foothills AOR) and VCRDS (Ventura County Regional Data Share)."; 
    $disclaimer .= '</p>';

    return $disclaimer;
}
?>
