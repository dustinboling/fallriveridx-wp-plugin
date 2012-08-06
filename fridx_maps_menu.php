<?php
    if ( isset ( $_REQUEST[ 'settings-updated' ] ) ) {
        $jsonurl = "http://fallriveridx.heroku.com/api/geocode/geocode.json?Address=";
        $jsonurl .= get_option( 'fridx_maps_center' );

        $json = file_get_contents( $jsonurl );
        $json_output = json_decode( $json, true );

        // TODO: needs to be tested on a fresh install
        if ( isset ( $json_output[ 'response' ][ 'success' ] ) ) {
            if ( $json_output[ 'response' ][ 'success' ] == false ) {
                $response = $json_output[ 'response' ][ 'message' ];
            } else if ( $json_output[ 'response' ][ 'success' ] == true ) {
                // set geocode
                $geocode = $json_output[ 'response' ][ 'message' ];
                update_option( 'fridx_maps_center_geocode', $geocode );

                // set geocode response
                $geocode_response = "Successfully updated map center.";
            } else {
                $response = "Wordpress connectivity error.";
            }

        }
    }
?>
<div class="wrap">
    <h2>Change the options to your maps here.</h2>
    <h3 id="fridx-error-message"><?php if ( isset ( $response ) ) { echo $response; } ?></h3>

    <form method="post" action="options.php" id="fridx-maps-options">
        <?php settings_fields( 'maps-settings-group' ); ?>
        <?php do_settings_fields( 'fridx-maps-menu', 'fridx_maps_center' ); ?> 
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Center Map On (city)</th>
                <td style="width: 300px;"><input type="text" name="fridx_maps_center" value="<?php echo get_option( 'fridx_maps_center' ); ?>" style="width: 300px;" /></td>
                <td><?php if ( isset ( $geocode_response ) ) { echo $geocode_response; } ?></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
        </p>
    </form>
</div>
