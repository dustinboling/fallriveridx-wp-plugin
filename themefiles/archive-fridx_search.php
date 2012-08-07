<?php
get_header();

/* Parse parameters */
$acceptable_params = array( 'city', 'min_price', 'max_price', 'beds', 'baths', 'building_size', 'community', 'mls_number', 'tract', 'zip_code' );
$params = $wp_query->query_vars;
global $user_params;

foreach ( $params as $param => $value ) {
  if ( in_array( $param, $acceptable_params, true ) && $value != "" ) {
        $user_params[ $param ] = $value;
    }
}

/* Construct query */
$jsonurl = "http://fallriveridx.heroku.com/api/properties/index.json?Token=";
$jsonurl .= FALL_RIVER_TOKEN;

foreach ( $user_params as $param => $value ) {
    if ( $value == "" ) {
    } else {
        switch ( $param ) {
        case "city":
            $value = fridx_capitalize_query( $value );
            $jsonurl .= "&City=" . $value;
            break;
        case "zip_code":
            $jsonurl .= "&ZipCode=" . $value;
            break;
        case "min_price":
            $jsonurl .= "&ListPrice=>" . $value;
            break;
        case "max_price":
            $jsonurl .= "&ListPrice=<" . $value;
            break;
        case "beds":
            $jsonurl .= "&BedroomsTotal=" . $value;
            break;
        case "baths":
            $jsonurl .= "&BathsTotal=" . $value;
            break;
        case "building_size":
            $jsonurl .= "&BuildingSize=" . $value;
            break;
        case "community":
            $jsonurl .= "&BuildersTractName=" . $value;
            break;
        case "mls_number":
            $jsonurl .= "&ListingID=" . $value;
            break;
        case "tract":
            $jsonurl .= "&BuildersTractName=" . $value;
            break;
        }
    }
}

/* Execute query */
$json_output = fridx_get_json_object( $jsonurl );
if ( isset ( $json_output[ 'response' ][ 'success' ] ) && $json_output[ 'response' ][ 'success' ] == false ) {
    $fr_error_msg = $json_output[ 'response' ][ 'message' ];
} elseif ( count( $json_output ) < 1 ) {
    $fr_error_msg = "No properties found.";
}

if ( isset ( $fr_error_msg ) ) {
  if ( $fr_error_msg == "No properties found." ) {
    the_widget( 'FridxSearchWidget', array( 'title' =>'NO PROPERTIES FOUND, SEARCH AGAIN', 'cities_field' => '' ) );
  } else {
      echo $fr_error_msg;
  }
} else {
?>
<?php
    $i = 1;
    echo '<div id="show-listing-container" class="fridx-container">';
    echo '<h4>You searched for:</h4>';
    echo '<ul id="fridx-search-params">';
    foreach ( $user_params as $key => $value ) {
        echo '<li>' . $key . ': ' . $value . '</li>';
    }
    echo '</ul>';
    foreach ( $json_output as $listings ) {
        foreach ( $listings as $listing ) {
            $images = $listing[ 'PropertyMedia' ];
            $slug = $listing[ 'ListingID' ] . '-' . fridx_to_slug( $listing[ 'FullStreetAddress' ] );
            echo '<div id="listings-container" class="fridx-container">';
            echo '<div id="listing-' . $i . '" class="fridx-listing">';
            echo '<h3 id="listing-location-' . $i . '" class="fridx-listing-header">';
            echo '<a href="' . get_site_url() . '/idx/listing/' . $slug . '">' . ucwords( strtolower( $listing[ 'FullStreetAddress' ] ) ) . '</a>';
            echo '</h3>';
            echo '<div class="fridx-listing-row1 fridx-row">';
            if ( count( $images ) > 0 ) {
                echo '<a href="' . get_site_url() . '/idx/listing/' . $slug . '">';
                echo '<img src="' . $images[0][ 'PropMediaURL' ] . '" id="image-' . $i . '" class="fridx-listing-index-image fridx-sixcol" />';
                echo '</a>';
            } else {
                echo '<a href="' . get_site_url() . '/idx/listing/' . $slug . '">';
                echo '<img src="' . plugins_url() . '/fallriveridx/images/house-default.png" class="fridx-listing-index-image fridx-sixcol" />';
                echo '</a>';
            }
            echo '<div id="listing-details-' . $i . '" class="fridx-listing-index-details fridx-sixcol">';
            echo '<table class="fridx-listing-table">';
            echo '<tr><td class="fridx-listing-table-col1">Price</td><td>' . $listing[ 'ListPrice' ] . '</td></tr>';
            echo '<tr><td class="fridx-listing-table-col1">Beds</td><td>' . $listing [ 'BedroomsTotal' ] . '</td></tr>';
            echo '<tr><td class="fridx-listing-table-col1">Baths</td><td>' . $listing[ 'BathsTotal' ] . '</td></tr>';
            echo '<tr><td class="fridx-listing-table-col1">Building Size</td><td>' . $listing[ 'BuildingSize' ] . '</td></tr>';
            echo '<tr><td class="fridx-listing-table-col1">Lot Size</td><td>' . $listing[ 'LotSizeSQFT' ] . '</td></tr>';
            echo '<tr><td class="fridx-listing-table-col1">Year Built</td><td>' . $listing[ 'YearBuilt' ] . '</td></tr>';
            echo '<tr><td class="fridx-listing-table-col1">Property Type</td><td>' . $listing[ 'PropertyType' ] . '</td></tr>';
            echo '</table></div>';
            echo '</div>';
            echo '<div id="listing-description-' . $i . '" class="fridx-listing-row2 fridx-row">';
            echo '<p>' . fridx_sentenceize( $listing[ 'PublicRemarks' ] ) . '</p>';
            echo '</div></div>';

            $i = $i + 1;
        }
    }
    echo '</div>';
}

get_footer();
?>
