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

				$path = dirname(__FILE__)."/xml_v2/rss-".$uid.".xml";

				// Try to import from the URL with simple XML
				if(!@$fluxrss=simplexml_load_file($flux, 'SimpleXMLElement', LIBXML_NOCDATA)){

					// If the simplexml failed, let's use CURL
					$curl = curl_init();
					curl_setopt_array($curl, Array(
						CURLOPT_URL            => $flux,
						CURLOPT_USERAGENT      => 'spider',
						CURLOPT_TIMEOUT        => 120,
						CURLOPT_CONNECTTIMEOUT => 30,
						CURLOPT_RETURNTRANSFER => TRUE,
						CURLOPT_ENCODING       => 'UTF-8'
					));
					$data = curl_exec($curl);
					curl_close($curl);

					// If CURL failed, we have no more solution
					if(!@$rss_data=simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)){
						$error = 1;
						$ok[$uid] = "error : ".$flux;
					}
					// Otherwise, save the data from the CURL request
					else {
						file_put_contents($path, "");
						file_put_contents($path, $data);

						$ok[$uid] = "ok";
					}
				}

				// If the simplexml succeeded, let's save this
				else {
					file_put_contents($path, "");
					file_put_contents($path, file_get_contents($flux));

					$ok[$uid] = "ok";
				}
			}
		}
	}

	ksort($ok);
	$out_string = "";
	foreach($ok as $k => $v) {
		$nick_name = get_user_meta($k, "nickname", true);
		$out_string = $out_string."$k | $nick_name => $v\n";
	}

	mail("bastien.vallet@gmail.com", "Runnosphere RSS status", "RESULT:\n".$out_string);
?>
