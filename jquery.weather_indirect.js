/* AJAX Weather Service, contact skkim0112000@gmail.com for questions */
jQuery(document).ready(function() { 
	var sendAjax =function (where){
		jQuery('#w_error').stop().hide('fast'); 
		jQuery.ajax({ 		 
			type: "GET", 	
			data:"where=" + where, 	
			// when direct access is not supported
			url: AWURL + "/wp-content/plugins/ajax-weather/service.weather_v1.php",
			success: function(data){
				jQuery('#weather_box').html(data);
			},
			error: function(data) {
				jQuery('#w_city').text('???');
				jQuery('#w_error').text('Not In Service!');	
				jQuery('#w_error').stop().show('slow');
			} 		 	 
		}); 
	}
	jQuery("#w_zip").live( 'keydown', function(event) {
		if(event.keyCode == 13){ 
			jQuery("#w_zip_change").click(); 
			return(false);
		} 
	});
	jQuery('#w_zip_change').live( 'click', function() {
		jQuery('#w_error').stop().hide('fast');
		var where   = jQuery('#w_zip').val(); 
		sendAjax( where.replace(" ", "%40") ); 
	});
	jQuery('#w_zip').live( 'click', function() {
		this.select();
	});
	//sendAjax('10001');
}) 
