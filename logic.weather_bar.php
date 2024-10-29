<?php
/*
include_once('../../common/inc/include.php');
include_once('../../common/inc/function.php');
*/ 
function get_weather_info($full_path) {
	$path_parts = pathinfo($full_path);
	return($path_parts['filename']);
}

$weather = Weather::load(false);	// false when option "allow_url_fopen" == OFF
//print_r($weather);
if ($weather === "GOOGLE_BLOCKED") {
	$weather_1 = new HTML_DIV( array("id"=>"w_1", "class"=>"w_entry"), array("Sorry!") );
	$weather_2 = new HTML_DIV( array("id"=>"w_2", "class"=>"w_entry"), array("Google Too Busy") );
	$weathers =  new HTML_DIV( array("id"=>"weathers"), array($weather_1, $weather_2) );
	$weather_box = new HTML_DIV( array("id"=>"weather_box"), array($weathers, "Try Later!") );
	$weather_bar = $weather_box->outerHTML();
} else {
	$ret = $weather->weather_show_all_assoc();
	$weather->takeOutWeatherInfo($city, 
								$today, 
								$time, 
								$zcode, 
								$c_icon,
								$c_condition,
								$c_temp_f, 
								$c_temp_c, 
								$c_humidity, 
								$c_wind
					);
	$c_icon_text = get_weather_info($c_icon);
	$tomorrow_weather = array();
	$forecast2 = $weather->get_forecast(2);
	$forecast3 = $weather->get_forecast(3);

	$weather_city = new HTML_DIV( array("id"=>"w_city"), "City:&nbsp;". $city );
	$weather_zip = new HTML_INPUT( array("id"=>"w_zip", "type"=>"text", "value"=>$zcode, 
											"title"=>"zip code or city name", "size"=>"6"), "" );
	$weather_button = new HTML_INPUT( array("id"=>"w_zip_change", "type"=>"button", "value"=>"Change", "title"=>"zip code or city name"), "" );
	$weather_error = new HTML_DIV( array("id"=>"w_error"), "" );
	$weather_title = new HTML_DIV( array("id"=>"w_title", "style"=>"clear: left;"), array($weather_city, $weather_zip, $weather_button, $weather_error ) );

	$current_title = new  HTML_DIV( array("id"=>"w_1_title", "class"=>"w_date"), "Current Weather" );
	$google = "http://www.google.com";
	//$current_clip = new HTML_IMG( array("id"=>"w_1_icon", "src"=>"". $c_icon), "" );
	$current_clip = new HTML_IMG( array("id"=>"w_1_icon", "src"=>"". $google. $c_icon, "alt"=>$c_icon_text), "" );
	$current_icon = new HTML_DIV( array("id"=>"w_1_clip", "class"=>"w_icon", "style"=>"float: left;"), $current_clip );

	$current_celcius = new HTML_SPAN( array( "id"=>"w_1_celcius", "class"=>"" ), $c_temp_c. DEGREE. "C" ); //  
	$current_Fahrenheit = new HTML_SPAN( array( "id"=>"w_1_fahrenheit", "class"=>"" ), $c_temp_f. DEGREE. 'F' );
	$current_temp = new HTML_DIV( array("id"=>"w_1_temp", "class"=>"w_temp", "style"=>"float: left; padding: 0 10px;"), array($current_Fahrenheit, " (", $current_celcius, ")" ) );


	$tomorrow_title = new  HTML_DIV( array("id"=>"w_2_title", "class"=>"w_date", "style"=>"clear:left;"), "Tomorrow" );
	$tomorrow_pic = $forecast2['icon'];
	$tomorrow_icon_text = get_weather_info($tomorrow_pic);


	//$tomorrow_clip = new HTML_IMG( array("id"=>"w_2_icon", "src"=>"". $tomorrow_pic), "" );
	$tomorrow_clip = new HTML_IMG( array("id"=>"w_2_icon", "src"=>"". $google. $tomorrow_pic, "alt"=>$tomorrow_icon_text), "" );
	$tomorrow_icon = new HTML_DIV( array("id"=>"w_2_clip", "class"=>"w_icon", "style"=>"float: left;"), $tomorrow_clip );

	$tomorrow_f_low = $forecast2['low'];
	$tomorrow_f_high = $forecast2['high'];
	$tomorrow_c_low = round( ($tomorrow_f_low - 32) * 5 / 9 );
	$tomorrow_c_high = round( ($tomorrow_f_high - 32) * 5 / 9 );
	$tomorrow_fahrenheit = new HTML_SPAN( array( "id"=>"w_2_fahrenheit", "class"=>"w_range" ), $tomorrow_f_low. " - ". $tomorrow_f_high. DEGREE. "F" ); 
	$tomorrow_celcius = new HTML_SPAN( array( "id"=>"w_2_celcius", "class"=>"w_range" ), "(". $tomorrow_c_low. " - ". $tomorrow_c_high. DEGREE. "C". ")" ); 
	$tomorrow_temp = new HTML_DIV( array("id"=>"w_2_temp", "class"=>"w_temp", "style"=>"float: left; padding: 0 10px;"), array($tomorrow_fahrenheit, $tomorrow_celcius) );


	$third_title = new  HTML_DIV( array("id"=>"w_3_title", "class"=>"w_date"), "2 Days After" );
	$third_pic = $forecast3['icon'];
	//$third_clip = new HTML_IMG( array("id"=>"w_3_icon", "src"=>"". $third_pic), "" );
	$third_clip = new HTML_IMG( array("id"=>"w_3_icon", "src"=>"". $google. $third_pic), "" );
	$third_icon = new HTML_DIV( array("id"=>"w_3_clip", "class"=>"w_icon"), $third_clip );

	$third_fahrenheit = new HTML_SPAN( array( "id"=>"w_3_fahrenheit", "class"=>"" ), $forecast3['low']. " - ". $forecast3['high']. "¢ªF" ); 
	$third_temp = new HTML_DIV( array("id"=>"w_3_temp", "class"=>"w_temp"), $third_fahrenheit );


	$weather_1 = new HTML_DIV( array("id"=>"w_1", "class"=>"w_entry"), array($current_title, $current_icon, $current_temp) );
	$weather_2 = new HTML_DIV( array("id"=>"w_2", "class"=>"w_entry"), array($tomorrow_title, $tomorrow_icon, $tomorrow_temp) );
	$weather_3 = new HTML_DIV( array("id"=>"w_3", "class"=>"w_entry"), array($third_title, $third_icon, $third_temp) );

	$weathers =  new HTML_DIV( array("id"=>"weathers"), array($weather_1, $weather_2) );

	//$weathers =  new HTML_DIV( array("id"=>"weathers"), array($weather_1, $weather_2, $weather_3) );

	$empty_4h = new HTML_DIV ( array("style"=>"height: 6px; clear:both;"), " ");

	$weather_box = new HTML_DIV( array("id"=>"weather_box"), array($weathers, $empty_4h, $weather_title) );
	$weather_bar = $weather_box->outerHTML();
}

?>
