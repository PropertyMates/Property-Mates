jQuery(document).ready(function($) {
	 
   $('.bsp-quote-link').click(function() {
	   
	   var id = $(this).attr("href").substr(1);
	   
	   var data = {
				'action' : 'get_status_by_ajax',
				'id' : id,
				'security': bsp_ajax_object.quote
			}
			$.post(bsp_ajax_object.ajax_url, data, function(response) {
				
				tinymce.get("bbp_reply_content").execCommand("mceInsertContent", false, response); 
				location.hash = "#new-post" ;
				}) ;
			
                    
	 });
         
 });
