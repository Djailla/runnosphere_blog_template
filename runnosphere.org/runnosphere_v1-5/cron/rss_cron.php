<?php

	//CRON FOR RUNNERS RSS BLOGs !
	require_once( dirname(__FILE__) . '/../../../../wp-load.php');

	$args = array(
		'exclude' => array(),
		'orderby' => 'login',
		'order' => 'ASC',
		'fields' => 'all'
	);

	$users = get_users($args);
	foreach($users as $usr){
		$uid = $usr->ID;

		if(get_user_meta($uid, "rss_active", true) == "1"){

			//Admin enable this RSS
			$rss = get_user_meta($uid, "rss_address", true);
			if(!empty($rss)){
				$flux = stripslashes($rss);
				$error = 0;

				if(!@$fluxrss=simplexml_load_file($flux, 'SimpleXMLElement', LIBXML_NOCDATA)){
						$error = 1;
						$ok[$uid] = "error : ".$flux;
				}

				if($error == 0){
						$path = dirname(__FILE__)."/xml_v2/rss-".$uid.".xml";

						file_put_contents($path, "");
						file_put_contents($path, file_get_contents($flux));

						$ok[$uid] = "ok";
				}
			}
		}
	}

	$new_array = array_map(create_function('$key, $value', 'return $key.": ".$value;'), array_keys($ok), array_values($ok));
	mail("bastien.vallet@gmail.com", "Runno RSS : CRON", "RESULT  :\n".implode("\n", $new_array));
?>
