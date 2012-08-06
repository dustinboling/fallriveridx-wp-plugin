<?php
// javascript version
$ref_url = parse_url( site_url() );
$siteUrl = $ref_url['host'];
$data = array(
    'token' => __( get_option( 'fridx_token' ) ),
    'siteUrl' => __( $siteUrl )
);
wp_enqueue_script( 'fridx-token-ajax' );
wp_localize_script( 'fridx-token-ajax', 'php_data', $data );

// php version
global $wp;

if ( isset ( $_REQUEST['settings-updated'] ) ) {
    $wpurl = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
    $jsonurl = "http://fallriveridx.heroku.com/api/accounts/update.json?Token=";
    $jsonurl .= FALL_RIVER_TOKEN;
    $jsonurl .= "&SiteUrl=" . $wpurl;

    $json = file_get_contents( $jsonurl );
    $json_output = json_decode( $json, true );
} else {
    $jsonurl = "http://fallriveridx.heroku.com/api/accounts/show.json?Token=";
    $jsonurl .= FALL_RIVER_TOKEN;

    $json = file_get_contents( $jsonurl );
    $json_output = json_decode( $json, true );
}

if ( isset ( $json_output[ 'response' ] ) ) {
    $response = $json_output[ 'response' ][ 'message'];
} elseif ( isset ( $json_output[ 'user' ] ) ) {
    if ( $json_output[ 'user' ][ 'site_url' ] == site_url() ) {
        $response = "ACTIVE";
    } else {
        $response = "INACTIVE";
    }
} else {
    $response = "Wordpress connectivity error, please try again in a few moments.";
}

function fridx_account_status( $resp ) {
    if ( $resp == "Wordpress connectivity error, please try again in a few moments." ) {
        return "Unknown";
    } elseif ( $resp == "You have activated your subscription for this site." || $resp == "ACTIVE" ) {
            return '<span style="color: green; text-transform: uppercase; font-size: 16px; vertical-align: middle;">Active</span>';
        } elseif ( $resp == "INACTIVE" ) {
            return '<span style="color: red; text-transform: uppercase; font-size: 16px; vertical-align: middle;">Inactive</span>';
        } elseif ( $resp == "There has been an error with your request, please try again." ) {
            return "Inactive";
        } elseif ( $resp == "Invalid API key." ) {
            return "Inactive";
        } else {
            return $resp;
        }
} 

function fridx_account_status_errors( $resp ) {
    if ( $resp == "Wordpress connectivity error, please try again in a few moments." 
        || $resp == "There has been an error with your request, please try again."
        || $resp == "Invalid API key."
        || $resp == "No directive given.") {
            return  '<span style="color: red;">' . $resp . '</span>';
        } 
}
?>
<div class="wrap">
    <h2>Hi. I'm a menu. Use me to activate this site with your FRIDX token.</h2>
    <h3 id="fridx-error-message"><?= fridx_account_status_errors( $response ) ?></h3>

    <form method="post" action="options.php" id="fridx-activation">
        <?php settings_fields( 'baw-settings-group' ); ?>
        <?php do_settings_fields( 'fridx-activation-menu', 'fridx_token' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"> API Key</th>
                <td style="width: 300px;"><input type="text" name="fridx_token" value="<?php echo get_option( 'fridx_token' ); ?>" style="width: 300px;"/></td>
                <td style="float: left;"><?= fridx_account_status( $response ); ?></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
        </p>
    </form>
</div>
