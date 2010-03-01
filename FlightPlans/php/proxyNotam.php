<?php

$config['url']       = $_GET['url']; // url of html to grab
$config['url'] = str_replace(" ", "+", $config['url']);
$config['start_tag'] = '<body>'; // where you want to start grabbing
$config['end_tag']   = "</body>"; // where you want to stop grabbing
$config['show_tags'] = 0; // do you want the tags to be shown when you show the html? 1 = yes, 0 = no

class grabber
{
	var $error = '';
	var $html  = '';
	
	function grabhtml( $url, $start, $end )
	{
		$file = file_get_contents( $url );
		
		if( $file )
		{
			if( preg_match_all( "#$start(.*?)$end#s", $file, $match ) )
			{				
				$this->html = $match;
			}
			else
			{
				$this->error = "Tags cannot be found.";
			}
		}
		else
		{
			$this->error = "Site cannot be found!";
		}
	}
	
	function strip( $html, $show, $start, $end )
	{
		if( !$show )
		{
			$html = str_replace( $start, "", $html );
			$html = str_replace( $end, "", $html );
			
			return $html;
		}
		else
		{
			return $html;
		}
	}
}

$grab = new grabber;
$grab->grabhtml( $config['url'], $config['start_tag'], $config['end_tag'] );

echo $grab->error;

foreach( $grab->html[0] as $html )
{
	echo $grab->strip( $html, $config['show_tags'], $config['start_tag'], $config['end_tag'] );
}

?>