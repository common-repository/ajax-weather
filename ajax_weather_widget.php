<?php
/*
Plugin Name: Ajax Weather Widget
Plugin URI: http://autophp.com
Description: Weather information ajaxed by U.S. zip code or name of city.
Author: Sun Kim 
Version: 1.6
Author URI: http://autophp.com
*/

/*  Copyright 2012  Sun Kim  (email : skkim0112000 @ gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once 'class.HTML.php';
require_once 'class.Weather.php';
define ("CRLF", "\n");
define ("DEGREE", "˚");

class Ajax_Weather_Widget extends WP_Widget {
    //
    //  Constructor
    //
    function Ajax_Weather_Widget() {
        $widget_ops = array('classname' => 'widget_ajaxweather', 'description' => __('Show the weather info. from  API interfacing with Google') );
        $this->WP_Widget('ajax-weather-widget', __('AJAX Weather'), $widget_ops);
    }

	//
	//  form() - outputs the options form on admin in Appearance => Widgets (backend).
	//
	function form($instance) {
		//  Assigns values
		$instance = wp_parse_args( (array) $instance, array( 'weather_title' => '','weather_zip' => '', 'weather_width' => '' ) );

		$title = strip_tags($instance['weather_title']);
		$zip = strip_tags($instance['weather_zip']);
		$width = strip_tags($instance['weather_width']);
?>
		<p><label for="<?php echo $this->get_field_id('weather_title'); ?>">
		<?php echo __('Title'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('weather_title'); ?>" name="<?php echo $this->get_field_name('weather_title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

		<p><label for="<?php echo $this->get_field_id('weather_zip'); ?>">
		<?php echo __('Zip'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('weather_zip'); ?>" name="<?php echo $this->get_field_name('weather_zip'); ?>" type="text" value="<?php echo attribute_escape($zip); ?>" /></label></p>

		<p><label for="<?php echo $this->get_field_id('weather_width'); ?>">
		<?php echo __('Width(px)'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('weather_width'); ?>" name="<?php echo $this->get_field_name('weather_width'); ?>" type="text" value="<?php echo attribute_escape($width); ?>" /></label></p>
	
<?php
	}

	//
	//  update() - processes widget options to be saved.
	//
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['weather_title'] = strip_tags($new_instance['weather_title']);
		$instance['weather_zip'] = strip_tags($new_instance['weather_zip']);
		$instance['weather_width'] = strip_tags($new_instance['weather_width']);
		return $instance;
	}

	//
	//  widget() - outputs the weather div.
	//
	function widget($args, $instance) {
		global $weather_city_request;
		extract($args);
		
		//  Get the title of the widget and the specified width of the image
		$title = empty($instance['weather_title']) ? ' ' : apply_filters('widget_title', $instance['weather_title']);
		$zip = empty($instance['weather_zip']) ? ' ' : $instance['weather_zip'];
		$width = empty($instance['weather_width']) ? ' ' : $instance['weather_width'];
		
		$weather_city_request = $zip;
		require_once ("logic.weather_bar.php"); 

		$wpurl = get_bloginfo('wpurl'); 

		echo '<script src="'. $wpurl.'/wp-content/plugins/ajax-weather/jquery-1.5.2.min.js" type="text/javascript"></script>';
		echo '<script src="'. $wpurl.'/wp-content/plugins/ajax-weather/jquery-common.js" type="text/javascript"></script>';

		echo '<script type="text/javascript">';
		echo 'var AWURL = "'. $wpurl.'";';
		echo '</script>';

		echo '<script src="'. $wpurl.'/wp-content/plugins/ajax-weather/jquery.weather_indirect.js" type="text/javascript"></script>';
		
		//  Outputs the widget in its standard ul li format.
		echo $before_widget;
			if (!empty( $title )) {
				echo $before_title . $title . $after_title;
			};
			$ul__head = '<ul style="list-style:none;margin-left:0px; width:'. $width. 'px;">';
			echo $ul__head;
			
				//  Let's display the weather block			
				echo '  <li>'. $weather_bar. '</li>';

			echo '</ul>';
		echo $after_widget;
		//  Done
	}
}
add_action('widgets_init', create_function('', 'return register_widget("Ajax_Weather_Widget");')); 
