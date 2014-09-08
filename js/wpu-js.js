function wpu_load_box(){
    jQuery("#wpu_shortlink_box_t").val("");
    jQuery("#wpu_shortlink_box").fadeToggle("fast");
    jQuery("#wpu_shortlink_box_overlay").fadeToggle("fast");
    jQuery("#wpu_shortlink_box_t").focus();
}

function wpu_ajax_request(){
    url = jQuery("#wpu_shortlink_box_t").val();
    
    if(url.length > 4){
        jQuery("#wpu_shortlink_box_t").removeClass("required");
    }else{
        jQuery("#wpu_shortlink_box_t").addClass("required");
        return;
    }
    
    jQuery("#wpu_shortlink_box_overlay .result").hide();
    jQuery("#wpu_shortlink_box_overlay .spinner").show();
    var data = {
        action: 'wpu_shortlinks_get',
        url: url
    };
    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
    jQuery.post(ajaxurl, data, function(response) {
        if(response.length > 2){
            jQuery("#wpu_shortlink_box_overlay .result_input").html('<input type="text" value="'+ response +'" size="25" onfocus="this.select()" style="text-align:left;direction:ltr;" readonly /> <a href="'+response+'" target="_blank">*</a>');
            jQuery("#wpu_shortlink_box_overlay .result").show();
        }else{
            jQuery("#wpu_shortlink_box_t").addClass("required");
        }
        jQuery("#wpu_shortlink_box_overlay .spinner").hide();
    });   
}

jQuery('#wpu_shortlink_box, #wpu_shortlink_box_overlay .hidebox').click(function() {
   wpu_load_box();
});

jQuery("#wpu_shortlink_box_t").keyup(function(e){
    jQuery("#wpu_shortlink_box_t").removeClass("required");
    if(e.keyCode == 13){
       wpu_ajax_request();
    }
});

jQuery('#wpu_shortlink_box_b').click(function() {
    wpu_ajax_request();
});

(function($) {

    var $f = $("#wpu_settings");

    function toggleRows() {
        $f.find('.row-icon-size').toggle( ($f.find('.row-load-icon-css input:checked').val() == 1) );
    }

    $f.change(toggleRows);

    // run once on init
    toggleRows();

})(jQuery);