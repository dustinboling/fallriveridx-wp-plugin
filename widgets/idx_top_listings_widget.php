<?php
/** 
 * The listings widget grabs a list of properties based
 * on the criteria the user puts into the form.
 */
class FridxTopListingsWidget extends WP_Widget {
    /* Register widget */
    function __construct() {
        parent::__construct(
            'idx_listings', // Base ID
            'IDX Listings', // NAME
            array( 'description' => __( 'Get a list of properties', 'text_domain' ), ) // Args
        );
    }

    /* Actual widget */
    public function widget($args, $instance) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $before_widget;
        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;

        $jsonurl = "http://fallriveridx.heroku.com/api/properties/index.json?Token=" . FALL_RIVER_TOKEN;
        foreach ($instance as $key => $param) {
            if ($param == "" || $key == "title") {
            } else if ( $key == "ListPriceLow" || $key == "ListPriceHigh"  ) {
            } else if  ( $key == "SortBy" ) {
                $jsonurl .= "&" . $key . "=" . $param;
            } else if ( $key == "City" ) {
                $jsonurl .= "&" . $key . "=" . fridx_capitalize_query( $param );
            } else {
                $jsonurl .= "&" . $key . "=" . str_replace(' ' , '%20', $param);
            }
        }
        // set list price range if BOTH limits are set
        if ( isset ( $instance['ListPriceLow'] ) && isset ( $instance['ListPriceHigh'] )  && $instance['ListPriceLow'] != "" && $instance['ListPriceHigh'] != "" ) {
            $jsonurl .= "&PriceRange=" . $instance['ListPriceLow'] . "-" . $instance['ListPriceHigh']; 
        }
        // set list price range if only HIGH is set
        else if ( empty( $instance['ListPriceLow'] ) && isset ( $instance['ListPriceHigh'] ) ) {
            $jsonurl .= "&PriceRange=0-" . $instance['ListPriceHigh'];
        }
        // set list rice  range if only LOW is set
        else if ( empty( $instance['ListPriceHigh'] ) && isset ( $instance['ListPriceLow'] ) ) {
            $jsonurl .= "&PriceRange=" . $instance['ListPriceLow'] . "-" . "999999999";
        }

        $json_output = fridx_get_json_object( $jsonurl );

        // set error message
        if ( isset ( $json_output[ 'response' ][ 'message' ] ) ) {
            $response = $json_output[ 'response' ][ 'message' ];
        }
?>
      <ul id="fridx-listings">
<?php
        /** TODO: add id to span (need some kind of a global counter for when
         *  enduser uses more than one of these.
         */
        if ( isset ( $response ) ) {
            echo $response;
        } else {
            foreach ( $json_output as $listings ) {
                foreach ( $listings as $listing ) {
                    $slug = $listing[ 'ListingID' ] . '-' . fridx_to_slug( $listing[ 'FullStreetAddress' ] );
                    $listing_item = '';
                    $listing_item .= '<a href="' .get_site_url() . '/idx/listing/';
                    $listing_item .= $slug . '">';
                    $listing_item .= strtoupper( $listing[ 'FullStreetAddress' ] );
                    $listing_item .= '<span>' . $listing[ 'ListPrice' ] . '</span>';
                    $listing_item .= '</a>';

                    echo '<li id="fridx-listing-' . $GLOBALS[ 'listing_counter' ] . '">' . $listing_item . '</li>';

                    update_listing_counter();
                }
            }
        }
?>
      </ul>
<?php
        echo $after_widget;
    }

    /* Sanitize values as they are saved. */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'Limit' ] = strip_tags( $new_instance[ 'Limit' ] );
        $instance[ 'City' ] = strip_tags( $new_instance[ 'City' ] );
        $instance[ 'ZipCode' ] = strip_tags( $new_instance[ 'ZipCode' ] );
        $instance[ 'ListPriceLow' ] = strip_tags( $new_instance[ 'ListPriceLow' ] );
        $instance[ 'ListPriceHigh' ] = strip_tags( $new_instance[ 'ListPriceHigh' ] );
        $instance[ 'BedroomsTotal' ] = strip_tags( $new_instance[ 'BedroomsTotal' ] );
        $instance[ 'BathsTotal' ] = strip_tags( $new_instance[ 'BathsTotal' ] );
        $instance[ 'BuildingSize' ] = strip_tags( $new_instance[ 'BuildingSize' ] );;
        $instance[ 'SortBy' ] = strip_tags( $new_instance[ 'SortBy' ] );

        return $instance;
    }

    /* Widget Form */
    // TODO: city/zip on same line separated by -or-
    // TODO: Limit listing to a specific agent/office
    // TODO: Sort order (see below)
    // TODO: different modes for different types of searches
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'Top Listings', 'text_domain' );
        }
        if ( isset( $instance[ 'Limit' ] ) ) {
            $Limit = $instance[ 'Limit' ];
        } else {
            $Limit = 10;
        }
        if ( isset( $instance[ 'City' ] ) ) {
            $City = $instance[ 'City' ];
        }
        else {
            $City = "";
        }
        if ( isset( $instance[ 'ZipCode' ] ) ) {
            $ZipCode = $instance[ 'ZipCode' ];
        }
        else {
            $ZipCode = "";
        }
        if ( isset( $instance[ 'ListPriceLow' ] ) ) {
            $ListPriceLow = $instance[ 'ListPriceLow' ];
        }
        else {
            $ListPriceLow = "";
        }
        if ( isset( $instance[ 'ListPriceHigh' ] ) ) {
            $ListPriceHigh = $instance[ 'ListPriceHigh' ];
        }
        else {
            $ListPriceHigh = "";
        }
        if ( isset( $instance[ 'BedroomsTotal' ] ) ) {
            $BedroomsTotal = $instance[ 'BedroomsTotal' ];
        }
        else {
            $BedroomsTotal = "1";
        }
        if ( isset( $instance[ 'BathsTotal' ] ) ) {
            $BathsTotal = $instance[ 'BathsTotal' ];
        }
        else {
            $BathsTotal = "1";
        }
        if ( isset( $instance[ 'BuildingSize' ] ) ) {
            $BuildingSize = $instance[ 'BuildingSize' ];
        }
        else {
            $BuildingSize = ""; 
        }
        if ( isset ( $instance[ 'SortBy' ] ) ) {
            $SortBy = $instance[ 'SortBy' ];
        }
        else {
            $SortBy = 'ListPrice|DESC';
        }

        // Set options arrays for <select> elements
        // TODO: do we even need beds/baths on these?
        $bed_options = array( 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9 );
        $bath_options = $bed_options;
?>
<h3>Sort By:</h3>
    <p>
        <select id="<?php echo $this->get_field_id( 'SortBy' ); ?>" name="<?php echo $this->get_field_name( 'SortBy' ); ?>">
            <option value="ListPrice|DESC" <?php if ( $instance[ 'SortBy' ] == 'ListPrice|DESC' ) echo 'selected="selected"'; ?>>Highest Price</option>
            <option value="ListPrice|ASC"<?php if ( $instance[ 'SortBy' ] == 'ListPrice|ASC' ) echo 'selected="selected"'; ?>>Lowest Price</option>
            <option value="ListingDate|DESC"<?php if ( $instance[ 'SortBy' ] == 'ListingDate|DESC' ) echo 'selected="selected"'; ?>>Newest Homes</option>
            <option value="BuildingSize|DESC"<?php if ( $instance[ 'SortBy' ] == 'BuildingSize|DESC' ) echo 'selected="selected"'; ?>>Largest Homes</option>
            <option value="LotSizeSQFT|DESC"<?php if ( $instance[ 'SortBy' ] == 'LotSizeSQFT|DESC' ) echo 'selected="selected"'; ?>>Largest Lots</option>
            <option value="">Best Deals (Largest Price Drop)</option>
        </select>
    </p>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    <label for="<?php echo $this->get_field_id( 'Limit'  ); ?>"><?php _e( '# of Listings to show:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'Limit' ); ?>" name="<?php echo $this->get_field_name( 'Limit' ); ?>" type="text" value="<?php echo esc_attr( $Limit ); ?>" />
    <label for="<?php echo $this->get_field_id( 'City' ); ?>"><?php _e( 'City:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'City' ); ?>" name="<?php echo $this->get_field_name( 'City' ); ?>" type="text" value="<?php echo esc_attr( $City ); ?>" />
    <label for="<?php echo $this->get_field_id( 'ZipCode' ); ?>"><?php _e( 'Zip:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'ZipCode' ); ?>" name="<?php echo $this->get_field_name( 'ZipCode' ); ?>" type="text" value="<?php echo esc_attr( $ZipCode ); ?>" />
    <label for="<?php echo $this->get_field_id( 'ListPriceLow' ); ?>"><?php _e( 'List Price (low):' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'ListPriceLow' ); ?>" name="<?php echo $this->get_field_name( 'ListPriceLow' ); ?>" type="text" value="<?php echo esc_attr( $ListPriceLow ); ?>" />
    <label for="<?php echo $this->get_field_id( 'ListPriceHigh' ); ?>"><?php _e( 'List Price (high):' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'ListPriceHigh' ); ?>" name="<?php echo $this->get_field_name( 'ListPriceHigh' ); ?>" type="text" value="<?php echo esc_attr( $ListPriceHigh ); ?>" />
    <label for="<?php echo $this->get_field_id( 'BedroomsTotal' ); ?>"><?php _e( 'Bedrooms:' ); ?></label>
    <?php $bed_fname = $this->get_field_name( 'BedroomsTotal' ); ?> 
    <?php $bed_fid = $this->get_field_id( 'BedroomsTotal' ); ?>
    <?php echo fridx_select_widget( 'BedroomsTotal', $bed_options, $bed_fname, $bed_fid, $instance ); ?><br>
    <label for="<?php echo $this->get_field_id( 'BathsTotal' ); ?>"><?php _e( 'Baths:' ); ?></label>
    <?php $bath_fname = $this->get_field_name( 'BathsTotal' ); ?>
    <?php $bath_fid = $this->get_field_id( 'BathsTotal' ); ?>
    <?php echo fridx_select_widget( 'BathsTotal', $bath_options, $bath_fname, $bath_fid, $instance ); ?><br>
    <label for="<?php echo $this->get_field_id( 'BuildingSize' ); ?>"><?php _e( 'Lot Size (SQFT):' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'BuildingSize' ); ?>" name="<?php echo $this->get_field_name( 'BuildingSize' ); ?>" type="text" value="<?php echo esc_attr( $BuildingSize ); ?>" />
</p>
<?php
    }
}
