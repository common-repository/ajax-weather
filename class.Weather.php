<?php
/**
 *	Weather Class v.1.3
 *	
 *	Developer: Sun Kim at skkim0112000@gmail.com
 *	Use:	Get the Google weather information to build my own
 *	Remarks:	
 *			If "file_get_contents" or "simplexml_load_file" php functions cannot be used
 *			for server configuration option "allow_url_fopen" set to OFF does not allow you to access outside,
 *			then, call Weather Class as follows,
 *			$weather = Weather::load(FALSE);
 *
 */


/*
 *	Factory Class
 */
 class Weather {
	private $where;
	static function load($URL_ACCESS_ALLOWED=TRUE ) {
		if (!isset($_REQUEST['where'])) {
			if ( isset($GLOBALS['weather_city_request']) ) {
				$where = $GLOBALS['weather_city_request'];
			} else {
				$where = '10001';
			}
		} else {
			$where = $_GET['where'];
		}
		if (strpos($where, ' ') !== FALSE) {
			$where = urlencode($where);
		} 
		return new New_weather_load($where, $URL_ACCESS_ALLOWED);
	}
}

/*
 *	New_weather_load Class
 *
 *		Probe Goggle weather info newly
 */
class New_weather_load {
	private $xml;
	private $simple_xml;
	private $info = array(); 
	private $condition = array();
	private $forecast1 = array();
	private $forecast2 = array();
	private $forecast3 = array();
	private $forecast4 = array();
	private $tmp;
	private $city;
	private $today;
	private $zcode;
	private $assoc;
	function __construct ($where, $URL_ACCESS_ALLOWED=TRUE) {

		if ($URL_ACCESS_ALLOWED) {
			$this->xml = $this->get_file_contents("http://www.google.com/ig/api?weather=".$where);
		} else {
			//
			$this->tmp = new Url_file("http://www.google.com/ig/api?weather=".$where);
			$this->xml = $this->tmp->get();
		}
		$pos1 = stripos($this->xml, '302');
		$pos2 = stripos($this->xml, 'moved');
		$pos3 = stripos($this->xml, 'sorry.google.com');
		if ( !$pos1 && !$pos2 && !$pos3 ) {
			$this->simple_xml = new SimpleXMLElement($this->xml);

			$this->assoc= $this->xml2assoc($this->simple_xml);
		} else {
			return("GOOGLE_BLOCKED");
		}
	}	

	/* 
	 *	Returns the contents of file name passed [for PHP4]
	 */
	function get_file_contents($filename) {
		if (!function_exists('file_get_contents')){
			$fhandle = fopen($filename, "r");
			$fcontents = fread($fhandle, filesize($filename));
			fclose($fhandle);
		} else {
			$fcontents = file_get_contents($filename);
		}
		return $fcontents;
	} 

	function xml2assoc($xml) {        
		$i = 0;        
		foreach ($xml as $e) {            
			foreach ($e as $name => $data) {                
				if (($name == 'forecast_information') || ($name == 'current_conditions')) {                    
					foreach ($data as $col => $val) {                        
						$weather[$name][$col] = (string)$val['data'];                    
					}                
				} else {                    
					foreach ($data as $col => $val) {                        
						$weather['forecast'][$i][$col] = (string)$val['data'];                    
					}                    
					$i++;                
				}            
			}        
		}        
		return $weather;
	}

	function get_xml_file() {
		return ($this->xml);
	}

	function weather_show($i) {
			return ($this->info);
	}
	function weather_show_all_xml() {
			return ($this->xml);
	}
	function weather_show_all_assoc() {
			return ($this->assoc);
	}

	#
	# take out the basic weather variables from $filter_id
	#
	function takeOutWeatherInfo(&$city,						// Name of City
														&$today,						// Date 
														&$time,							// Time
														&$zcode,						// Postal Code
														&$c_icon,						// Current Weather Condition
														&$c_condition,			// Current Weather Condition
														&$c_temp_f,					// Current Temperature Fahrenheit
														&$c_temp_c,					// Current Temperature Celcius
														&$c_humidity,				// Current Humidity
														&$c_wind						// Current Wind Condition
														) {
		$city				= $this->get_city();
		$today			= $this->get_today();
		$time				= $this->get_time();
		$zcode			= $this->get_zcode();
		$c_icon			= $this->get_icon();
		$c_condition= $this->get_current_condition();
		$c_temp_f		= $this->get_current_temp_f();
		$c_temp_c		= $this->get_current_temp_c();
		$c_humidity	= $this->get_current_humidity();
		$c_wind			= $this->get_current_wind();
		
	}

	function get_city() {		
		return($this->assoc['forecast_information']['city']);
	}

	function get_today() {		
		return($this->assoc['forecast_information']['forecast_date']);
	}

	function get_time() {		
		return($this->assoc['forecast_information']['current_date_time']); 
	}

	function get_zcode() {		
		return($this->assoc['forecast_information']['postal_code']);
	}

	function get_icon() {		
		return($this->assoc['current_conditions']['icon']);
	}

	function get_current_condition() {
		return($this->assoc['current_conditions']['condition']);
	}

	function get_current_temp_f() {
		return($this->assoc['current_conditions']['temp_f']);
	}

	function get_current_temp_c() {
		return($this->assoc['current_conditions']['temp_c']);
	}

	function get_current_humidity() {
		$hum = $this->assoc['current_conditions']['humidity'];
		$hum_array = explode(":", $hum);
		return(trim($hum_array[1]));
	}

	function get_current_wind() {
		$win = $this->assoc['current_conditions']['wind_condition'];
		$win_array = explode(":", $win);
		return(trim($win_array[1]));
	}

	function get_forecast_all() { 
		return($this->assoc['forecast']);
	}

	function get_forecast($i=1) { 
		if (empty($i)) {
			$i = 0;
		} elseif ($i > 0) {
			$i -= 1;
		}
		if ($i > 3) { // total 4 forecast entries
			$i = 3;
		}
		return($this->assoc['forecast'][$i]);
	}
}

/*
 *	Url_file Class
 *
 *		Fetch the URL file
 *		May customize options, if needed
 */
 class Url_file {
	private $ch;
	private $timeout;
	private $urlFile;
	function __construct($request_url) {
		$this->ch = curl_init();
		$this->timeout = 5;
		curl_setopt($this->ch, CURLOPT_URL, $request_url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		$this->urlFile = curl_exec($this->ch);
		curl_close($this->ch);
	}
	function get() {
		return ($this->urlFile);
	}
}
?>
