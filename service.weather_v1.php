<?php
	// Service Module for Ajax Weather Widget in Wordpress
	// Author : Ben Kim (skkim0112000@yahoo.com)
	// version 1.0
	// Ported on 01/27/2012
	// This module does not need the server configutaion option "allow_url_fopen = on" in php.ini file 

	// include("../inc/function.php");
	require_once("class.Weather.php");
	require_once("class.HTML.php");
	define ("CRLF", "\n");
	define ("DEGREE", "¡Æ");
	define ("DEFAULT_ZIP", "11354");

	if ( isset($_GET['where']) ) {
		$weather_city_request = $_GET['where'];
	} else {
		$weather_city_request = DEFAULT_ZIP;
	}
	require_once("logic.weather_bar.php");

	//header("content-type: text/html; charset=euc-kr");
	header("content-type: text/html; charset=euc-kr");
	echo($weather_bar);

	//header("content-type: text/xml"); 
	//$where = $_REQUEST['where']; 
	//$xmlData = get_file_contents("http://www.google.com/ig/api?weather=$where"); 
	//echo $xmlData; 
?>