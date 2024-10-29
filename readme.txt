=== Ajax Weather ===
Plugin URI: http://autophp.com/
Contributors: Kim, Sun (skkim0112000@gmail.com)
Donate link: 
Tags: weather, google, ajax, solution, php, jquery
Requires at least: 3.3.1
Tested up to: 3.4.1
Stable tag: 1.6

== Short description ==

This widget provides Google weather information and ajaxed by zip code

== Description == 

Ajax weather service is developed for the users not familiar with computer systems, for example, the elderly. Included in web pages as a left/right bar, this module delivers a local weather information automatically without user's intervention. Furthermore, end users may change the location of interest by entering zip code or name of city that may give them a fun and attract them to computer systems.

This widget provides Google weather information and ajaxed by zip code. Ajax technology enables us to see other information without refreshing the web page. A location is designated by U.S. zip code or name of city, for example, '10001' or 'london'.

jQuery-1.5.2, javascript and web service backend modules are conglomerated in this technology. After installation through plugin admin menu and appearance menu, however, no more customizations are needed for the service concept is pretty simple.

Check the demonstration at http://autophp.com. For inquiries, feel free to email to Sun Kim at skkim0112000@gmail.com

== Installation ==

	This section describes how to install the plugin and get it working.

	1. Download "Ajax Weather Widget" at http://wordpress.org/extend/plugins/ajax-weather
	2. Extract zipped files to get the "ajax-weater" folder and subordinate files.
	3. Upload the folder and files to web server at "wp-content/plugins" directory
	   (If you are using Wordpress v.3.3.1 or later, you may use the admin menu of "plugins" and "add new")
	4. Activate the plugin through the 'Plugins' menu in WordPress
	5. Place the widget at the right place you want, through the "Appearance" and "Widget" admin menu

	* The widget file, ajax_weather_widget.php, should encoded in UTF-8 to see the degree mark.

== Frequently Asked Questions ==

	1.	question:	License;  
		answer:		GPLv2 Compatible

== Screenshots ==

1. Sample Output
1. image file name must conform to the format of screenshot-1.jpg 


== Changelog ==

	= 1.6 =
	* 7/6/2012   Debugging of bug in Ajax service (v.1.5 function not found)
		     Compatibility Test for WP v.3.4.1

	= 1.5 =
	* 7/2/2012   Addition of "alt" attribute to "img" tag 

	= 1.4 =
	* 5/24/2012  Joint display of temperatures for Fahrenheit and Celcius degree 

	= 1.3 =
	* 5/11/2012  Fix the typo errors
	* 5/5/2012   Eliminate the embedded style in #weather_box margin attribute
        * 5/5/2012   jQuery "$" notation changed into "jQuery" for prevention of conflict

	= 1.2 =
	* 4/1/2012   Debugging for Width parameter not working problem
	
	= 1.1 =
	* 2/8/2012   Add the demonstration site

	= 1.0 =
	* 2/4/2012   Add the screenshot-1.jpg 
	
	= 0.1 =
	* 1/31/2012  Set the URL automatically by get_bloginfo('wpurl')

== Upgrade Notice ==

	= 1.0 =
	Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

	= 0.5 =
	This version fixes a security related bug.  Upgrade immediately.

== Arbitrary section ==

	= Features =
	Current and tomorrow weather
	Accept U.S. zip code or Name of city
	Default location, Relocatable by Ajax
	Simple and small
	Attract users
	Easy to install
	No code, No key, No password

	= Browser Tested =
	Internet Explorer v8, v9 
	Firefox v10 
	Chrome v16, 
	iPhone Safari v5
	
