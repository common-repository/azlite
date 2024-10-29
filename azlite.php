<?php 
/* 
Plugin Name: AzLite 
Plugin URI: http://www.polaraul.com
Description: Displays Amazon RSS Tag Feeds In Your Post.
Version: 1.1 
Author: Paul Morley  
Author URI: http://www.polaraul.com 
*/ 

function my_function ($text){


    $text = str_replace('[azlite]','<azlite>',$text);
	$text = str_replace('[/azlite]','</azlite>',$text);
    preg_match_all( "/\<azlite\>(.*?)\<\/azlite\>/s", $text, $listtitle );

	$formURL = split(",", $listtitle[1][0]);

	include_once "lastRSS.php";

	// Create lastRSS object
	$rss = new lastRSS;

	// Set cache dir and cache time limit (1200 seconds)
	// (don't forget to chmod cahce dir to 777 to allow writing)
	$rss->cache_dir = '';
	$rss->cache_time = 1200;
	$rss->CDATA = 'content';


    $atext = "";
	$ctrl = "360";
	

	// Try to load and parse RSS file
	if ($rs = $rss->get('http://www.amazon.com/rss/tag/'.strtolower(rawurlencode($formURL[0])).'/popular?length='.$formURL[1].'&tag='.$formURL[2])) {
    	foreach($rs['items'] as $item) {
			$atext = $atext . "<h4>" . $item['title'] . "</h4>";
			$atext = $atext . $item['description'];
			$atext = $atext . "<BR clear='All'>";
			$atext = $atext . "<BR clear='All'><hr size='1' noshade='noshade' />";
        }
    }
		else {
    $atext = $atext . "Error: It's not possible to reach RSS file...\n";
}



	$text = str_replace("<azlite>".$listtitle[1][0]."</azlite>",$atext,$text);
	return $text;
}

add_filter('the_content','my_function');

?>