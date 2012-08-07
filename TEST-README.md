# FALL RIVER IDX: LIST OF THINGS TO TEST:
First sign up for an api key: http://fallriveridx.heroku.com/signup
After initializing plugin, refreshing permalinks and activating your api key, try the following:

## The map:
* set center in Fall River IDX->maps menu
* go to www.yourblog.com/idx/map/ - does it display properly?

## IDX Search Widget
* add search widget to a sidebar
* try to do some searches with it

## IDX Areas Widget
* add areas widget to sidebar
* add some areas to it
* do they show up in the sidebar?
* do the links work?

## IDX Listings Widget
* add listings widget to sidebar
* try a bunch of variations, do you get the expected listings?

## Property Search Shortcode
```ruby
[property_search PriceRange="" ListPrice="" City="" ZipCode="" BedroomsTotal="" BathsTotal="" 
  BuildingSize="" ListAgentAgentID="" SaleAgentAgentID=""]
```
* start a new page
* try a complex query with EACH of the above params
* try a complex query with SOME/ALL of the above params
* does the query return the expected results?
* does the html render properly? --NOTE: the css is in a very early stage, needs work

## Single Property Shortcode
```ruby
[single_property ListingID="21406401"]
```
* start a new page
* put in a valid listing id like above
* does it render properly?

