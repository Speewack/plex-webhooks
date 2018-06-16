<?php

/*
	Process Plex WebHook calls and triggering Hue Lights to change
	URL: http://{host}/{path}/plexhue/index.php?playername={Plex Media Player to match on}&huetoken="Hue Bridge Token for API"
*/

//Verify all parameters are here. Else display error and exit
if( isset($_REQUEST['playername']) && 
    isset($_REQUEST['huetoken']) &&
    isset($_REQUEST['huegroup']) && 
    isset($_REQUEST['hueaddress']) &&
    isset($_REQUEST['payload']) ) {

    // Read configurations from URL Query String
    $playername = $_REQUEST['playername'];
    $huetoken = $_REQUEST['huetoken'];
    $huegroup = $_REQUEST['huegroup'];
    $hueaddress = $_REQUEST['hueaddress'];
    
    // Only proceed if the correct player fired the webhook

	// Process Plex Payload
	$plex = json_decode($_REQUEST['payload']);	
	$plexEvent = $plex->event;
	$plexPlayer = $plex->Player->title;

    if ($playername == $plexPlayer) 
    {
	// Build hue URL
		$hueURL = "http://".$hueaddress."/api/".$huetoken."/groups/".$huegroup."/action";    
    	
		// Set Brightness based on plexEvent
		switch ($plexEvent) {
			case "media.play":
			case "media.resume":
				$huecommand="{\"on\":false}";
				break;
			case "media.pause":
				$bri=75;
				$huecommand="{\"on\":true,\"bri\":".$bri."}";
				break;
			case "media.stop":
				$bri=254;
				$huecommand="{\"on\":true,\"bri\":".$bri."}";
				break;
		}

		// Send command to Hue via http PUT
		$opts = array('http' =>
					array(
						'method' => 'PUT',
						'header' => 'Content-type: application/x-www-form-urlencoded',
						'content' => $huecommand
					)
				);

		$context = stream_context_create($opts);
	
		$result = file_get_contents($hueURL, false, $context);
	
	}
}
else {
	http_response_code(400);

	print "<h1>Bad Request</h1>Missing inputs.<br/> URL should be in the form:<br/> http://{host}/{basepath}/plexhue/index.php?playername={Plex Player Name to match against}&huetoken={Hue Bridge Token for API}&huegroup=[hue light group to control]&hueaddress={ip address of hue bridge}";
}   

?>