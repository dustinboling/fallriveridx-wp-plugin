<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAzyqUkdz4bnrV9ftHR-7i9qgmO7NzfLbs&sensor=false"></script> 
<?php
get_header();

// enqueue scripts
wp_enqueue_script( 'jquery-ui-core' );
wp_enqueue_script( 'jquery-ui-widget' );
wp_enqueue_script( 'jquery-ui-mouse' );
wp_enqueue_script( 'jquery-ui-slider' );
wp_enqueue_script( 'fridx-maps' );
wp_enqueue_script( 'fridx-fluster' );

// localize options to fridx-maps
function localize_map_options() {
    return array(
        'map_center' => get_option( 'fridx_maps_center_geocode' ),
        'fridx_token' => get_option( 'fridx_token' )
    );
}
wp_localize_script( 'fridx-maps', 'map_options', localize_map_options());

wp_head();

?>
<style type="text/css">
    #map_canvas {
        height: 700px;
        padding: 0;
        margin: 0 auto;
        position: fixed;
    }
    /* zoom control fix */
    #map_canvas img { 
        max-width: none; 
    }
    #price-slider {
        width: 90%;
        margin: 0 auto;
    }
    #amount {
        border: 1px solid #fff;
        background-color: #fff;
        color: #f6931f;
        font-weight: bold;
    }
    #beds-slider, 
    #baths-slider, 
    #house-size-slider, 
    #lot-size-slider {
        width: 28%;
        margin: 5px 0 5px 25px;
    }
    #bath-count, 
    #bed-count, 
    #lot-size-count, 
    #house-size-count {
        border: 0 !important;
        background-color: #fff;
        color: #f6931f;
        font-weight: bold;
        width: 40px;
        margin: 0 auto;
    }
    .fridx-slider-label-1 {
        padding-left: 25px;
        padding-top: 0 !important;
    }
    .fridx-slider-label-2 {
        padding-left: 46px;
        padding-top: 0 !important;
    }
</style>


<div class="fridx-container">
    <div class="fridx-row">
        <div id="map_canvas" class="fridx-twelvecol"></div>
    </div>
    <div id="fridx-map-user-controls">
        <!-- PRICE -->
        <p class="fridx-slider-label-1">
            <label for="amount">Max Price:</label>
            <input type="text" id="amount" />
        </p>
        <div id="price-slider"></div>
        <!-- BEDS/BATHS -->
        <div class="fridx-row">
            <p class="fridx-threecol fridx-slider-label-1">
                <label for="bed-count">Beds:</label>
                <input type="text" id="bed-count" />
            </p>
            <p class="fridx-threecol fridx-slider-label-2">
                <label for="bath-count">Baths:</label>
                <input type="text" id="bath-count" />
            </p>
        </div>
        <div class="fridx-row">
            <div id="beds-slider" class="fridx-fivecol"></div>
            <div id="baths-slider" class="fridx-fivecol"></div>
        </div>
        <!-- HOUSE/LOT SIZE -->
        <div class="fridx-row">
            <p class="fridx-threecol fridx-slider-label-1">
                <label for="house-size-count">House Size:</label>
                <input type="text" id="house-size-count" />
            </p>
            <p class="fridx-threecol fridx-slider-label-2">
                <label for="lot-size-count">Lot Size:</label>
                <input type="text" id="lot-size-count" />
            </p>
        </div>
        <div class="fridx-row">
            <div id="house-size-slider" class="fridx-fivecol"></div>
            <div id="lot-size-slider" class="fridx-fivecol"></div>
        </div>
    </div>
</div>

<?php
get_footer();
?>
