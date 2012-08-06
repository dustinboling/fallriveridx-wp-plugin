<?php 
/**
 * Single property shortcode interface
 */

function single_property_shortcode( $atts ) {
  // Make sure atts are ok, then output information
  if ( count( $atts ) > 1 ) {
    return "This shortcode only accepts one attribute (ListingID)";
  } elseif ( key( $atts )  != "listingid" ) {
    return "Invalid key, this shortcode only accepts ListingID";
  } else {
    $jsonurl = "http://fallriveridx.heroku.com/api/properties/show.json?Token=";
    $jsonurl .= FALL_RIVER_TOKEN;
    $jsonurl .= "&ListingID=" . $atts[ 'listingid' ];

    $json_output = fridx_get_json_object( $jsonurl );

    // make sure we have a valid query return, then put shortcode output.
    if ( isset ( $json_output[ 'response' ][ 'success' ] ) && $json_output[ 'response' ][ 'success' ] == false ) { 
      $fr_error_msg =  $json_output[ 'response' ][ 'message' ];
  } else if ( count ( $json_output ) == 0 ) {
      $fr_error_msg = "Listing not found, check ListingID.";
  }
  else {
      $listing = $json_output[0][ 'listing' ];
    }
    if ( isset ( $fr_error_msg ) ) {
      echo $fr_error_msg;
  } else {
    $images = $listing['PropertyMedia'];
?>

<div id="show-listing-container" class="fridx-container">
  <h1 id=""><?php echo $listing[ 'FullStreetAddress' ] ?></h1>
  <h2><?php echo $listing[ 'City' ] . ", " . $listing[ 'State'] . " " . $listing[ 'ZipCode' ]; ?></h2>
  <h3 id="price"><?php echo $listing[ 'ListPrice' ] ?></h3>

  <?php echo fridx_photo_gallery( $images ); ?>
  <div id="show-listing-secondary-details">
      <ul class="fridx-tabs">
          <li><a class="active" href="#info">Info</a></li>
          <li><a href="#interior">Interior</a></li>
          <li><a href="#exterior">Exterior</a></li>
          <li><a href="#community">Community</a></li>
      </ul>
      <ul class="fridx-tabs-content">
          <li class="active" id="info">
              <p>
                  <?php echo $listing[ 'PublicRemarks' ] ?>
              </p>
              <table class="">
                  <tr>
                      <td class="first">List Price</td>
                      <td><?php echo $listing[ 'ListPrice' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Listing Date</td>
                      <td><?php echo $listing[ 'ListingDate' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Bedrooms</td>
                      <td><?php echo $listing[ 'BedroomsTotal' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Baths</td>
                      <td><?php echo $listing[ 'BathsTotal' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Garage Spaces</td>
                      <td><?php echo $listing[ 'GarageSpacesTotal' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Lot Size</td>
                      <td><?php echo $listing[ 'LotSizeSQFT' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Building Size</td>
                      <td><?php echo $listing[ 'BuildingSize' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Property Type</td>
                      <td><?php echo $listing[ 'PropertyType' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Neighborhood</td>
                      <td><?php echo $listing[ 'Area' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Tract</td>
                      <td><?php echo $listing[ 'BuildersTractName' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Year Built</td>
                      <td><?php echo $listing[ 'YearBuilt' ] ?></td>
                  </tr>
              </table>
          </li>
          <li id="interior">
              <table class="">
                  <tr>
                      <td class="first">Laundry Locations</td>
                      <td><?php echo $listing[ 'LaundryLocations' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Entry Floor Number</td>
                      <td><?php echo $listing[ 'EntryFloorNumber' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Entry Location</td>
                      <td><?php echo $listing[ 'EntryLocation' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Appliances</td>
                      <td><?php echo $listing[ 'Appliances' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Common Walls</td>
                      <td><?php echo $listing[ 'CommonWalls' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Cooking Appliancess</td>
                      <td><?php echo $listing[ 'CookingAppliances' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Disability Access</td>
                      <td><?php echo $listing[ 'DisabilityAccess' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Doors</td>
                      <td><?php echo $listing[ 'Doors' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Eating Areas</td>
                      <td><?php echo $listing[ 'EatingAreas' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Fireplace Rooms</td>
                      <td><?php echo $listing[ 'FireplaceRooms' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Fireplace Features</td>
                      <td><?php echo $listing[ 'FireplaceFeatures' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Fireplace Fuel</td>
                      <td><?php echo $listing[ 'FirePlaceFuel' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Floor Material</td>
                      <td><?php echo $listing[ 'FloorMaterial' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Interior Features</td>
                      <td><?php echo $listing[ 'InteriorFeatures' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Kitchen Features</td>
                      <td><?php echo $listing[ 'KitchenFeatures' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Number of Remote Controls</td>
                      <td><?php echo $listing[ 'NumberOfRemoteControls' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Security / Safety</td>
                      <td><?php echo $listing[ 'SecuritySafety' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Levels</td>
                      <td><?php echo $listing[ 'Levels' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">TV Services</td>
                      <td><?php echo $listing[ 'TVServices' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Total Floors</td>
                      <td><?php echo $listing[ 'TotalFloors' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Windows</td>
                      <td><?php echo $listing[ 'Windows' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Dwelling Stories</td>
                      <td><?php echo $listing[ 'DwellingStories' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Bath Full</td>
                      <td><?php echo $listing[ 'BathFull' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Bath (1/2)</td>
                      <td><?php echo $listing[ 'BathHalf' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Bath (1/4)</td>
                      <td><?php echo $listing[ 'BathOneQuarter' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Bath (3/4)</td>
                      <td><?php echo $listing[ 'BathThreeQuarter' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Rooms</td>
                      <td><?php echo $listing[ 'Rooms' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Cooling Type</td>
                      <td><?php echo $listing[ 'CoolingType' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Heating Fuel</td>
                      <td><?php echo $listing[ 'HeatingFuel' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Heating Type</td>
                      <td><?php echo $listing[ 'HeatingType' ] ?></td>
                  </tr>
              </table>
          </li>
          <li id="exterior">
              <table>
                  <tr>
                      <td class="first">Building Structure Style</td>
                      <td><?php echo $listing[ 'BuildingStructureStyle' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Other Structures</td>
                      <td><?php echo $listing[ 'OtherStructures' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">PropertyType</td>
                      <td><?php echo $listing[ 'PropertyType' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">PricePerSqft</td>
                      <td><?php echo $listing[ 'PricePerSqft' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Property Subtype</td>
                      <td><?php echo $listing[ 'PropertySubType' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">UnitsTotalInComplex</td>
                      <td><?php echo $listing[ 'UnitsTotalInComplex' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Windows</td>
                      <td><?php echo $listing[ 'Windows' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Lot Description</td>
                      <td><?php echo $listing[ 'LotDescription' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Lot Size (Acres)</td>
                      <td><?php echo $listing[ 'LotSizeAcres' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Lot Size (SQFT)</td>
                      <td><?php echo $listing[ 'LotSizeSQFT' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Patio Features</td>
                      <td><?php echo $listing[ 'PatioFeatures' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Property Condition</td>
                      <td><?php echo $listing[ 'PropertyCondition' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Carport Spaces</td>
                      <td><?php echo $listing[ 'CarportSpacesTotal' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Covered Spaces</td>
                      <td><?php echo $listing[ 'CoveredSpacesTotal' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Garage Spaces</td>
                      <td><?php echo $listing[ 'GarageSpacesTotal' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Parking Spaces (other)</td>
                      <td><?php echo $listing[ 'OpenOtherSpacesTotal' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Parking Features</td>
                      <td><?php echo $listing[ 'ParkingFeatures' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Parking Type</td>
                      <td><?php echo $listing[ 'ParkingType' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Playing Courts</td>
                      <td><?php echo $listing[ 'PlayingCourts' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Pool Accessories</td>
                      <td><?php echo $listing[ 'PoolAccessories' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Pool Construction</td>
                      <td><?php echo $listing[ 'PoolConstruction' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Pool Descriptions</td>
                      <td><?php echo $listing[ 'PoolDescriptions' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Direction Faces</td>
                      <td><?php echo $listing[ 'DirectionFaces' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Other Structural Features</td>
                      <td><?php echo $listing[ 'OtherStructuralFeatures' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">View</td>
                      <td><?php echo $listing[ 'View' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Water</td>
                      <td><?php echo $listing[ 'Water' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Volt 220 Location</td>
                      <td><?php echo $listing[ 'Volt220Location' ] ?></td>
                  </tr>
              </table>
          </li> 
          <li id="community">
              <table>
                  <tr>
                      <td class="first">Sewer</td>
                      <td><?php echo $listing[ 'Sewer' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Water District</td>
                      <td><?php echo $listing[ 'WaterDistrict' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">HOA Fee</td>
                      <td><?php echo $listing[ 'HOAFee1' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">HOA Fee Frequency</td>
                      <td><?php echo $listing[ 'HOAFeeFrequency1' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Other Association Fees</td>
                      <td><?php echo $listing[ 'OtherAssociationFees' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Association Amenities</td>
                      <td><?php echo $listing[ 'AssociationAmenities' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Association Fees Include</td>
                      <td><?php echo $listing[ 'AssociationFeesInclude' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Association Name</td>
                      <td><?php echo $listing[ 'AssociationName' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">AssociationRules</td>
                      <td><?php echo $listing[ 'AssociationRules' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Community Features</td>
                      <td><?php echo $listing[ 'CommunityFeatures' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Elementary School</td>
                      <td><?php echo $listing[ 'ElementarySchool' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">High School</td>
                      <td><?php echo $listing[ 'HighSchool' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Middle School</td>
                      <td><?php echo $listing[ 'JuniorMiddleSchool' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Playing Courts</td>
                      <td><?php echo $listing[ 'PlayingCourts' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">Distance To Beach</td>
                      <td><?php echo $listing[ 'DistanceToBeachInMiles' ] ?></td>
                  </tr>
                  <tr>
                      <td class="first">School District</td>
                      <td><?php echo $listing[ 'SchoolDistrict' ] ?></td>
                  </tr>
              </table>
          </li>
      </ul>
  </div>
</div>
<?php
}
  }
}
add_shortcode( 'single_property', 'single_property_shortcode' );

?>
