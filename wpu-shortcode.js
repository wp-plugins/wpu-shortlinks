(function() {
    tinymce.create('tinymce.plugins.wpu_shortcode', {
        init : function(ed, url) {
            ed.addButton('wpu_shortcode', {
                title : wpu_shortlinks_ast,
                image : url+'/wpu_shortlink.png',
                onclick : function() {
					var shortlink_text;
					shortlink_text = ed.selection.getContent();
					if(shortlink_text=="")
						shortlink_text = prompt(wpu_shortlinks_slt,wpu_shortlinks_sltd);
					if (shortlink_text!=null && shortlink_text!=""){
						ed.selection.setContent('[wpu]' + shortlink_text + '[/wpu]');
					}                    
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('wpu_shortcode', tinymce.plugins.wpu_shortcode);
})();