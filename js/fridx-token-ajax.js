var token;
var jsonUrl;
var siteUrl;

function verifyKey() {
    // probably do not need siteUrl as we can resolve on the api...
    // may also be more accurate that way.
    var token = php_data.token;
    var siteUrl = php_data.siteUrl;
    var jsonUrl = "http://fallriveridx.heroku.com/api/accounts/update.json?"
        + "Token=" + token
        + "UpdateSiteUrl=true";

    // ask api if key is valid

    // manipulate dom... green yes, red no
}

// jQuery(window).ready(function() {
//     verifyKey();
// });

jQuery('#fridx-activation').submit(function() {
    window.location.reload(true);
}); 
