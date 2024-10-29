var FAILED = -1;
function commonOperations()
{
	
	common = this;
	this.ajaxActive = true;
	this.ajaxBusy = false;
	this.timer = 0;
	this.login_timer = '';
	this.operationFailed = -1;

	this.setCookie = function(c_name, value, expiredays) {
		var exdate = new Date();
		exdate.setDate( exdate.getDate() + expiredays);
		document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toUTCString());
	}
	this.getCookie = function(c_name) {
		if (document.cookie.length > 0) {
			name_start = document.cookie.indexOf( c_name + "=" );
			if (name_start != -1) {
				value_start = name_start + c_name.length + 1;
				value_end = document.cookie.indexOf(";", value_start);

				if (value_end == -1) value_end = document.cookie.length;
				return unescape(document.cookie.substring(value_start, value_end));
			}
		}
		return "";
	}
	this.showPopUp = function(page, pwidth, pheight) {
		createPopUp(page, "_self", pwidth, pheight, "yes", "no");
	}
	this.createPopUp = function(theURL, Name, popW, popH, scroll, resize) {
		var winleft = (screen.width - popW) / 2;
		var winUp = (screen.height - popH) / 2;
		winProp = 'width=' + popW + ', height=' + popH + ', left=' + winleft + ', top=' + winUp + ', scrollbars=' + scroll + ',resizable=' + resize + '';
		Win = window.open(theURL, Name, winProp);
		Win.window.focus();
	}
	this.refresh = function() {
		window.location.reload( false );
	}
	this.parentRefresh = function() {
		self.close();
		opener.refresh();
	}
	this.showInProcessInfo = function(show) {
		if (show == true) {
			clearTimeout(common.timer);
			jQuery('processing').hide();
			jQuery('processing').html("Operation In Process");
			jQuery('processing').show();			
		}
		else {
			jQuery('processing').hide();
		}		
	}
	this.showOperationInfo = function (text){
		
		jQuery('processing').html(text);		
		
		jQuery('processing').fadeIn(1, function(){			
			common.timer = setTimeout("jQuery('#processing').fadeOut(1000)", 1000);			
		});
	}
	this.isInt = function(t) {
		try {
			var y = parseInt(t);
			
			if (isNaN(y)) {
				return false;
			}
			return t == y && t.toString() == y.toString();
		}
		catch(ex){			
		}
		return false;
 	} 
	this.get_filename = function(str) {
		var start = str.lastIndexOf('/');
		return( str.substr(start + 1) );
	}
	
	this.fill_saved_ID = function() {
		var saved_id = common.getCookie('kgsa_login');
		if (saved_id == "") {
			return(false);
		}
		jQuery("#login_id").val(saved_id);
		return(saved_id);
	}

	/* timer */

	this.clear_message = function(id) {
		if (common.login_timer != "") {
			clearTimeout(common.login_timer);
			jQuery("#" + id).addClass("hide");
		}
	}

	this.email_good = function(login_email) {
		var str_len = login_email.length;
		var apos = login_email.indexOf("@");
		var dotpos = login_email.lastIndexOf(".");
		
		if ( apos < 1 || dotpos - apos < 2 || str_len - dotpos < 4) {
			return false;
		} else {
			return true;
		}
	}
	this.trim = function(stringToTrim) {
		return stringToTrim.replace(/^\s+|\s+$/g,"");
	}
	this.str_left = function(source_str, delimiter) {
		var tmp_array = source_str.split(delimiter); // menu_front
		return ( tmp_array[0] );
	}

	this.str_right = function(source_str, delimiter) {
		var tmp_array = source_str.split(delimiter); // menu_front
		return ( tmp_array[1] );
	}
	this.trimName = function(str) {
		str = str.replace(/ /g, '');
		str = str.replace(/\$/g, '');
		str = str.replace(/\\/g, '');
		str = str.replace(new RegExp('\\/', 'g'), '');
		return(str);
	}
	this.badID = function(name) {
		var str = common.trimName(name);
		if ( str == '' ) {
			return(true);
		}
		var str_dot = str.replace(/\./g, '');
		if ( str_dot == '' ) {
			return(true);
		}
		return(false);
	}
	this.badFilename = function(name) {
		var str = common.trim(name);
		if ( str == '' ) {
			return(true);
		}
		var str_comp = str.replace(/ /g, '');
		if ( str_comp != str ) {
			return(true);
		}
		return(false);
	}
	this.badBarName = function(name) {	
		if (common.badID(name)){
			return(true);
		}
		var tmp_array = name.split('.'); // bar.menu.primary
		if ( tmp_array[0] != 'bar' || common.trim(tmp_array[0]) == '') {
			return(true);
		}
		if ( tmp_array[1] != 'menu' || common.trim(tmp_array[1]) == '') {
			return(true);
		}
		if ( tmp_array[2] == undefined || common.trim(tmp_array[2]) == '') {
			return(true);
		}
		return(false);
	}
	this.newFile = function(name) {
		return( name + '?' + (new Date()).getTime() );
	}
	this.key_press = function(evt) {												
		if (evt.keyCode == 13) {					// when pressed enter 
			evt.data.ok();
		}
		else if (evt.keyCode == 27) {				// pressed esc
			evt.data.cancel();
		}
	}
	// A handler to kill the action
	this.nothing = function (e) {
		e.stopPropagation();
		e.preventDefault();
		return false;
	}; 

	this.ajaxRequest = function(params, 
							url,					// service.manageMenuDB.php  
							callback,				// Used if ajaxActive != true
							overrideSuccessFunc)
	{
		if (common.ajaxActive == true){
			var successFunction = function(result){	
						
				common.ajaxBusy = false;
				common.showInProcessInfo(false);
				
				try {
					var t = eval(result);
					// if result is less than 0, it means an error occured	
					if (common.isInt(t) == true  && t < 0) { 
						alert("Ajax Error");									
					}	
					else{ // if result is greater than 0 it means operation is succesfull
						callback(result);
						common.showOperationInfo("Mission Completed");
					}
				}
				catch(ex) {	// if result is string it means operation is succesfull				
					callback(result);
					common.showOperationInfo("Mission Completed");								
				}
				jQuery("#progress").removeClass('loading');
			};
			
			if (typeof overrideSuccessFunc == 'function') {
				successFunction = overrideSuccessFunc;	
			}
			jQuery.ajax({
					type: 'POST',
					url: url,
					async: false,
					data: params,
					//dataType: 'json',
					timeout:100000,
					beforeSend: function(){ 
							common.showInProcessInfo(true);
							common.ajaxBusy = true;
							jQuery("#progress").addClass('loading'); 
					},
					success: function(result) {
							jQuery("#progress").removeClass('loading');
							successFunction(result);
					},
					failure: function(result) {								
							common.ajaxBusy = false;
							jQuery("#progress").removeClass('loading');
							common.showInProcessInfo(false);
							if (result == common.operationFailed) {
								alert("Failed in ajax.")
							}
					},
					error: function(par1, par2, par3){
						jQuery("#progress").removeClass('loading');
						common.showInProcessInfo(false);
						alert("Error in ajax, try later.")
					}
			});
		}
		else {
			callback();
			common.ajaxBusy = false;
		}
	}
}
function languageManager() {
	this.lang = "en";
	
	this.load = function(lang) {
		this.lang = lang
		this.url = location.href.substring(0, location.href.lastIndexOf('/'));
		this.jquery = location.href.substring(0, this.url.lastIndexOf('/')) + '/jquery';
		
		document.write("<script language='javascript' src='" + this.jquery + "/langs/" + this.lang + ".js'></script>");
	}	
	
	this.addIndexes= function() {
		for (var n in arguments[0]) { 
			this[n] = arguments[0][n]; 
		}
	}	
}
jQuery(document).ready(function() {
	jQuery('.ultari table').hover( 
		function () {
			jQuery(this).parent().addClass("borderred");
		}, 
		function () {
			jQuery(this).parent().removeClass("borderred");
		}
	);
	jQuery("#the_piecemaker_slideshow").parents("table").parent("li").hover( function() {
		window.location.href = "http://donail.com";
	});
});
