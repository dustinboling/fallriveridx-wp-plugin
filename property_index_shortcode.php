<?php
/**
 * Property index shortcode interface. 
 * Turns shortcodes into HTML for community & city pages.
 */

function check_search_attr_keys( $atts ) {
    $acceptable_atts = array("ListPrice", 
                            "PriceRange",
                            "City", 
                            "ZipCode", 
                            "BedroomsTotal", 
                            "BathsTotal", 
                            "BuildingSize", 
                            "ListAgentAgentID", 
                            "SaleAgentAgentID"
                        );

    foreach ( $atts as $key => $value ) {
        if ( in_array ( $key, $acceptable_atts ) ) {
            } else {
                echo "Invalid parameter passed:" . $key;
                break 1;
            }
        }
    }

function capitalize_atts( $atts ) {
    $clean_atts = array();
    foreach ( $atts as $key => $value ) {
        switch ( $key ) {
            case "listprice":
                $clean_atts[ 'ListPrice' ] = html_entity_decode($value);
                break;
            case "pricerange":
                $clean_atts[ 'PriceRange' ] = $value;
                break;
            case "zipcode":
                $clean_atts[ 'ZipCode' ] = $value;
                break;
            case "bedroomstotal":
                $clean_atts[ 'BedroomsTotal' ] = $value;
                break;
            case "bathstotal":
                $clean_atts[ 'BathsTotal' ] = $value;
                break;
            case "buildingsize":
                $clean_atts[ 'BuildingSize' ] = $value;
                break;
            case "listagentagentid":
                $clean_atts[ 'ListAgentAgentID' ] = $value;
                break;
            case "saleagentagentid":
                $clean_atts[ 'SaleAgentAgentID' ] = $value;
                break;
            case "city":
                $clean_atts[ 'City' ] = $value;
                break;
            }
        }
        $attrs = $clean_atts;
        return $attrs;
    }

/* [property_search PriceRange="" ListPrice="" City="" ZipCode="" BedroomsTotal="" BathsTotal="" BuildingSize="" ListAgentAgentID="" SaleAgentAgentID=""] */
function property_search_shortcode( $atts ) {
    $acceptable_atts = array("ListPrice", "City", "ZipCode", "BedroomsTotal", "BathsTotal", "BuildingSize", "ListAgentAgentID", "SaleAgentAgentID");
    $attrs = capitalize_atts( $atts );
    check_search_attr_keys( $attrs );

    $query = "http://fallriveridx.heroku.com/api/properties/index.json?Token=";
    $query .= FALL_RIVER_TOKEN;

    foreach ( $attrs as $key => $value ) {
        $query .= "&" . $key . "=" . $value ;
    }
    $query = str_replace(" ", "%20", $query);

    $json_output = fridx_get_json_object( $query );

    // Check if we got an error, else proceed
    if ( isset ( $json_output[ 'response' ][ 'success' ] ) ) {
        $response = $json_output[ 'response' ][ 'message' ];
        echo $response;
    } else {
        $i = 1;
        $output = '<div id="listings-container" class="fridx-container">';
            foreach ( $json_output as $listings ) {
                foreach ( $listings as $listing ) {
                    $images = $listing[ 'PropertyMedia' ];
                    $slug = $listing[ 'ListingID' ] . '-' . fridx_to_slug( $listing[ 'FullStreetAddress' ] );

                    $output .= '<div id="listing-' . $i . '" class="fridx-listing">';
                        $output .= '<h3 id="listing-location-' . $i . '" class="fridx-listing-header">' . '<a href="' . get_site_url() . '/idx/listing/' . $slug . '">' . ucwords( strtolower( $listing[ 'FullStreetAddress' ] ) ) . '</a></h3>';
                        $output .= '<div class="fridx-listing-row1 fridx-row">';
                    if ( count( $images ) > 0 ) {
                        $output .= '<a href="' . get_site_url() . '/idx/listing/' . $slug . '">';
                        $output .= '<img src="' . $images[0][ 'PropMediaURL' ] . '" id="image-' . $i . '" class="fridx-listing-index-image fridx-sixcol" />';
                        $output .= '</a>';
                    } else {
                        $output .= '<a href="' . get_site_url() . '/idx/listing/' . $slug . '">';
                        $output .= '<img src="' . plugins_url() . '/fallriveridx/images/house-default.png" class="fridx-listing-index-image fridx-sixcol" />';
                        $output .= '</a>';
                    }
                    $output .= '<div id="listing-details-' . $i . '" class="fridx-listing-index-details fridx-sixcol">';
                    $output .= '<table class="fridx-listing-table">';
                    $output .= '<tr><td class="fridx-listing-table-col1">Price</td><td>' . $listing[ 'ListPrice' ] . '</td></tr>';
                    $output .= '<tr><td class="fridx-listing-table-col1">Beds</td><td>' . $listing [ 'BedroomsTotal' ] . '</td></tr>';
                    $output .= '<tr><td class="fridx-listing-table-col1">Baths</td><td>' . $listing[ 'BathsTotal' ] . '</td></tr>';
                    $output .= '<tr><td class="fridx-listing-table-col1">Building Size</td><td>' . $listing[ 'BuildingSize' ] . '</td></tr>';
                    $output .= '<tr><td class="fridx-listing-table-col1">Lot Size</td><td>' . $listing[ 'LotSizeSQFT' ] . '</td></tr>';
                    $output .= '<tr><td class="fridx-listing-table-col1">Year Built</td><td>' . $listing[ 'YearBuilt' ] . '</td></tr>';
                    $output .= '<tr><td class="fridx-listing-table-col1">Property Type</td><td>' . $listing[ 'PropertyType' ] . '</td></tr>';
                    $output .= '</table></div>';
                    $output .= '</div></div>';

                    $i = $i + 1;
                }
            }
            $output .= '</div>';

        /* TODO: switch this so it just returns the message from the api */
        if ( isset ( $json_output['response' ] ) && $json_output[ 'response'][ 'message' ] == "Could not parse ListPrice." ) {
            return "Could not parse list price. Make sure there is a comparison operator.";
        } else {
            return $output;
        }
    }
}
add_shortcode( 'property_search', 'property_search_shortcode' );

?>
