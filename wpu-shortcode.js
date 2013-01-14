(function() {
    tinymce.create('tinymce.plugins.wpu_shortcode', {
        init : function(ed, url) {
            ed.addButton('wpu_shortcode', {
                title : 'افزودن لینک کوتاه wpu.ir',
                image : url+'/wpu_shortlink.png',
                onclick : function() {
					var shortlink_text;
					shortlink_text = ed.selection.getContent();
					if(shortlink_text=="")
						shortlink_text = prompt("لطفا نوشته‌ای برای لینک کوتاه وارد نمائید","پیوند کوتاه");
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