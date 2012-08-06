jQuery(document).on('ready', function(event) {
    jQuery('.chzn-select').chosen();
});
jQuery(document).ajaxSuccess(function(e, xhr, settings) {
    var widget_id_base = 'fridx_areas_widget';

    if(settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=' + widget_id_base) != -1) {
        jQuery('.chzn-select').chosen();
    }
});
