<?php

/**
 * @package wp-pocketrss
 */
/*
Plugin Name: wp-pocketrss
Plugin URI: 
Description: GetPocket.com rss parser plugin
Version: 0.5.1
Author: Krzysztof Turek
Author URI: http://krzysztof-turek.com
License: GPLv2 or later
*/

function rssparser ($atts) {
	
	extract(shortcode_atts(array(
       'url' => ''
    ), $atts));
	if($url != '')
	{
	   $array = get_headers($url);
	   $string = $array[0];
	   if(strpos($string,"200")) $rss = simplexml_load_file($url);
	}
	if($rss)
	{
		$items = $rss->channel->item;
		foreach($items as $item)
		{
			$title = $item->title;
			$link = $item->link;
			$domain = parse_url($link);
			$published_on = $item->pubDate;
			$description = $item->description;
			$out .= '<div class="pocket_item">'."\n";
			$out .= '<h4><a href="'.$link.'">'.$title.'</a></h4>';
			$out .= '<div class="pocket_details">';
			$out .= '<small>('.$published_on.')</small><br />';
			$out .= '<small><img style="width: 16px; height: 16px; float: left;" src="http://img.readitlater.com/i/' . $domain[host] . '/favicon.ico?f=fi">';
			$out .= '&nbsp;  <a href="http://' . $domain[host] . '" >' . $domain[host] . '</a></small>';
			$out .= '</div>';
			$out .= '</div>';
		}
	}
	return $out;
}
function register_plugin_styles() {
	wp_enqueue_style( 'getpocketrss', plugin_dir_url( __FILE__ ). '/style.css'   );
}
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );
add_shortcode('getpocketrss', 'rssparser');  