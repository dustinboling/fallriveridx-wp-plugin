var markers = [];
var infowindow = [];
var infowindowList = [];
var center;
var bounds;
var ne;
var sw;
var ne_lat;
var ne_lng;
var sw_lat;
var sw_lng;
var latDiff;
var windowPct;
var map;
var listPrice;
var bedCount;
var bathCount;
var lotSize;
var buildingSize;
var markerCounter;
var propertyCount;

// this guy clears the overlays when we call him.
function clearOverlays() {
    if (markers) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
    }
}

// check for undefined values, set them
function checkForUndefined() {
    if(listPrice == undefined) {
        listPrice = 100000;
    }
    if(bedCount == undefined) {
        bedCount = 1;
    }
    if(bathCount == undefined) {
        bathCount = 1;
    }
    if(lotSize == undefined) {
        lotSize = 1;
    }
    if(buildingSize == undefined) {
        buildingSize = 1;
    }
}

function initialize() {
    // initialize map
    var uc = map_options.map_center.split(",");
    if ((uc == undefined) || (uc == "")) {
        var centerOn = new google.maps.LatLng(33.7301328, -117.87216760);
    } else {
        var centerOn = new google.maps.LatLng(uc[0], uc[1]);
    }

    var myOptions = {
        center: centerOn,
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    function flashFailureResponse(errorMessage) {
        var failureResponse = document.createElement('h2');
        failureResponse.style.color = 'orange';
        failureResponse.style.background = 'rgba(255, 255, 255, 0.8)';
        failureResponse.innerHTML = errorMessage;
        var failureResponseControl = document.createElement('div');

        if (map.controls[google.maps.ControlPosition.TOP_CENTER].length > 0) {
            map.controls[google.maps.ControlPosition.TOP_CENTER].pop();
            failureResponseControl.appendChild(failureResponse);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(failureResponse);
        } else {
            failureResponseControl.appendChild(failureResponse);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(failureResponse);
        }
    }

    function Client() {
        this.center = map.getCenter();
        this.bounds = map.getBounds();
        this.ne = this.bounds.getNorthEast();
        this.sw = this.bounds.getSouthWest();
        this.ne_lat = this.ne.lat();
        this.ne_lng = this.ne.lng();
        this.sw_lat = this.sw.lat();
        this.sw_lng = this.sw.lng();
        latDiff = this.ne_lat - this.sw_lat;
        windowPct = latDiff * .15;

        // assign undefined vars minimum value
        checkForUndefined();

        queryString = 'http://fallriveridx.heroku.com'
            + '/api/geocode/index.json?'
            + 'Token=' + map_options.fridx_token
            + '&ne_lat=' + (this.ne_lat - windowPct)
            + '&ne_long=' + (this.ne_lng - windowPct)
            + '&sw_lat=' + (this.sw_lat + windowPct)
            + '&sw_long=' + (this.sw_lng + windowPct)
            + '&ListPrice=>' + listPrice
            + '&BedroomsTotal=' + bedCount
            + '&BathsTotal=' + bathCount
            + '&LotSizeSQFT=' + lotSize
            + '&BuildingSize=' + buildingSize
            + '&Limit=1000'
            + '&callback=?';
    }

    // TODO: when we get back a response: no matches found
    // TODO: a little counter of the number of responses returned (total)
    // TODO: an overlay at map center that outputs data.response.message
    var data;
    Client.prototype.connect = function() {
        jQuery.getJSON(queryString, function(data) {
            var fluster = new Fluster2(map);

            // clear overlays and old markers
            clearOverlays();
            if (data.response) {
                if (data.response.success == false) {
                    flashFailureResponse(data.response.message);
                } 
                markers = [];
                markers.length = 0;
            } else {
                if (map.controls[google.maps.ControlPosition.TOP_CENTER].length > 0) {
                    map.controls[google.maps.ControlPosition.TOP_CENTER].pop();
                }
                markers.length = 0;
                jQuery.each(data.markers, function(index, val) {
                    index_marker = new google.maps.LatLng(this.LatLng[0], this.LatLng[1]);
                    marker = new google.maps.Marker({
                        position: index_marker,
                    });
                    fluster.addMarker(marker);
                    markers.push(marker);
                    infowindow[index] = new google.maps.InfoWindow({
                        content: '<div id="infowindow">' +
                        '<address>' +
                        this.Address + '<br />' +
                        this.City + ', ' + this.State + ' ' + this.ZipCode +
                        '</address>' +
                        '<hr />' +
                        '<div id="details">' +
                        '<p id="list-price">' + this.ListPrice + '</p>' +
                        '<p id="beds-baths">' + this.Bedrooms + ' Bedrooms, ' + this.Baths + ' Baths</p>' +
                        '<p id="sqft">SQFT: ' + this.BuildingSize + ' SQFT</p>' +
                        '<p id="lot-size">Lot SQFT: ' + this.LotSizeSQFT + ' SQFT</p>' +
                        '<p id="listing-date">Listed: ' + this.ListingDate + '</p>' +
                        '<p id="status">Status: ' + this.ListingStatus + '</p>' +
                        '</div>' +
                        '</div>'
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                        // close last infowindow
                        if(infowindowList.length > 0) {
                            lastWindow = infowindowList[infowindowList.length-1];
                            lastWindow.close(map, this);
                        }
                        // open new one
                        infowindowList.push(infowindow[index]);
                        infowindow[index].open(map, this);
                    });
                });
            }
            // TODO: move these into their own function
            // set new marker count
            markerCount = markers.length;

            // add count to map
            var propertyCount = document.createElement('h3');
            propertyCount.style.color = 'red';
            propertyCount.style.background = 'rgba(255, 255, 255, 0.6)';
            propertyCount.innerHTML = 'Property Count: ' + markerCount;
            var propertyCountControl = document.createElement('div');
            if (map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].length > 0) {
                map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].pop();
                propertyCountControl.appendChild(propertyCount);
                map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(propertyCount);
            } else {
                propertyCountControl.appendChild(propertyCount);
                map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(propertyCount);
            }

            // TODO: make the flash not happen
            // add control sliders to map
            var sliderControls = document.getElementById('fridx-map-user-controls');
            if (map.controls[google.maps.ControlPosition.BOTTOM_CENTER].length > 0)
            {
                map.controls[google.maps.ControlPosition.BOTTOM_CENTER].pop();
                propertyCountControl.appendChild(sliderControls);
                map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(sliderControls);
            } else {
                map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(sliderControls);
            }

            // set fluster styles, then initialize
            fluster.initialize();
        });
    }

    function Slider(div, counterDiv, value, min, max, step, counterSign, varChange) {
        jQuery(function() {
            jQuery( div ).slider({
                value: value,
            min: min,
            max: max, 
            step: step,
            slide: function( event, ui ) {
                jQuery( counterDiv ).val( counterSign + ui.value );
            },
            change: function( event, ui ) {
                // set global variable value
                argname = varChange;
                window[argname] = ui.value;

                // fire off client
                client = new Client();
                client.connect();
            }
            });
            jQuery( counterDiv ).val( counterSign + jQuery( div ).slider( "value" ) );
        });
    }

    // initiate sliders
    priceSlider = new Slider("#price-slider", "#amount", 100000, 50000, 2000000, 50000, "$", "listPrice");
    bedSlider = new Slider("#beds-slider", "#bed-count", 1, 0, 8, 1, "", "bedCount");
    bathSlider = new Slider("#baths-slider", "#bath-count", 1, 0, 8, 1, "", "bathCount");
    lotsizeSlider = new Slider("#lot-size-slider", "#lot-size-count", 500, 0, 5000, 100, "", "lotSize");
    houseSizeSlider = new Slider("#house-size-slider", "#house-size-count", 500, 0, 10000, 100, "", "buildingSize");

    // set markers on page load (only once)
    google.maps.event.addListenerOnce(map, 'idle', function(event) {
        var fluster = new Fluster2(map);
        var listPrice = 100000;
        client = new Client();
        client.connect();
    });

    // update listeners when a user finishes a drag
    google.maps.event.addListener(map, 'dragend', function(event) {
        client = new Client();
        client.connect();
    });

    // update listeners on zoom
    google.maps.event.addListener(map, 'zoom_changed', function(event) {
        client = new Client();
        client.connect();
    });
}
