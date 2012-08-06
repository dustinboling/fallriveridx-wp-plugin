<?php
/**
 * Lets a user perform a property search from a widget.
 */
class FridxSearchWidget extends WP_Widget {
    /* Register widget */
    function __construct() {
        parent::__construct(
            'fridx_search_widget', // Base ID
            'IDX Search',
            array( 'description' => __( 'Search for properties', 'text_domain' ), ) // Args
        );
    }

    /* Actual widget */
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );

        echo $before_widget;
        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;

?>
        <div id="fridx-search">
            <form action="<?php echo get_bloginfo( 'wpurl' ); ?>/idx/search" method="get">
                <?php if ( isset ( $instance[ 'cities_field' ] ) ) { ?>
                    <label for="GET-city">City</label><br />
                    <input id="GET-city" type="text" name="city"><br />
                <?php } ?>
                <?php if ( isset ( $instance[ 'zip_codes_field' ] ) ) { ?>
                    <label for="GET-zip-code">Zip Code</label><br />
                    <input id="GET-zip-code" type="text" name="zip_code"><br />
                <?php } ?>
                <?php if ( isset ( $instance[ 'communities_field' ] ) ) { ?>
                    <label for="GET-community">Community</label><br />
                    <input id="GET-community" type="text" name="community"><br />
                <?php } ?>
                <?php if ( isset ( $instance[ 'tracts_field' ] ) ) { ?>
                    <label for="GET-tract">Tract</label><br />
                    <input id="GET-tract" type="text" name="tract"><br />
                <?php } ?>
                <?php if ( isset ( $instance[ 'mls_numbers_field' ] ) ) { ?>
                    <label for="GET-mls-number">MLS Number</label><br />
                    <input id="GET-mls-number" type="text" name="mls_number"><br />
                <?php } ?>
                <label for="GET-min-price">Min Price</label><br />
                <input id="GET-min-price" type="text" name="min_price"><br />
                <label for="GET-max-price">Max Price</label><br />
                <input id="GET-max-price" type="text" name="max_price"><br />
                <label for="GET-beds">Beds</label><br />
                <input id="GET-beds" type="text" name="beds"><br />
                <label for="GET-baths">Baths</label><br />
                <input id="GET-baths" type="text" name="baths"><br />
                <label for="GET-building-size">SQFT (min)</label><br />
                <input id="GET-building-size" type="text" name="building_size">
                <input type="submit" value="Search">
            </form>
        </div>
<?php
        echo $after_widget;
    }

    /* Widget Form */
    public function form( $instance ) {
        if ( isset ( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( 'Property Search', 'text_domain' );
        }
        // currently set this MUST be checked
        if ( isset ( $instance[ 'cities_field' ] ) ) {
            $cities_field = $instance[ 'cities_field' ];
        } else {
            $instance[ 'cities_field' ] = true;
        }
        if ( isset ( $instance[ 'communities_field' ] ) ) {
            $communities_field = $instance[ 'communities_field' ];
        }
        if ( isset ( $instance[ 'tracts_field' ] ) ) {
            $tracts_field = $instance[ 'tracts_field' ];
        }
        if ( isset ( $instance[ 'zip_codes_field' ] ) ) {
            $zip_codes_field = $instance[ 'zip_codes_field' ];
        }
        if ( isset ( $instance[ 'mls_numbers_field' ] ) ) {
            $mls_numbers_field = $instance[ 'mls_numbers_field' ];
        }
?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <h3>Search Fields (optional)</h3>
            <input class="checkbox" type="checkbox" <?php checked( (bool) $instance[ 'cities_field' ], true ); ?> id="<?php echo $this->get_field_id( 'cities_field' ); ?>" name="<?php echo $this->get_field_name( 'cities_field' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'communities_field' ); ?>">City</label><br />
            <input class="checkbox" type="checkbox" <?php checked( (bool) $instance[ 'communities_field' ], true ); ?> id="<?php echo $this->get_field_id( 'communities_field' ); ?>" name="<?php echo $this->get_field_name( 'communities_field' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'communities_field' ); ?>">Community</label><br />
            <input class="checkbox" type="checkbox" <?php checked( (bool) $instance[ 'tracts_field' ], true ); ?> id="<?php echo $this->get_field_id( 'tracts_field' ); ?>" name="<?php echo $this->get_field_name( 'tracts_field' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'tracts_field' ); ?>">Tract</label><br />
            <input class="checkbox" type="checkbox" <?php checked( (bool) $instance[ 'zip_codes_field' ], true ); ?> id="<?php echo $this->get_field_id( 'zip_codes_field' ); ?>" name="<?php echo $this->get_field_name( 'zip_codes_field' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'zip_codes_field' ); ?>">Zip Code</label><br />
            <input class="checkbox" type="checkbox" <?php checked( (bool) $instance[ 'mls_numbers_field' ], true ); ?> id="<?php echo $this->get_field_id( 'mls_numbers_field' ); ?>" name="<?php echo $this->get_field_name( 'mls_numbers_field' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'mls_numbers_field' ); ?>">MLS Number</label><br />
        </p>

<?php
    }
}
