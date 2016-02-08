<?php

	libxml_use_internal_errors(true);

	$path = dirname(__FILE__)."/rss-aaa.xml";

	// URL OK :
	// $rss = "http://feeds.feedburner.com/LeBlogDeDjailla";

	// URL KO :
	$flux = "http://www.jerem-runner.com/rss";

	if(!@$fluxrss=simplexml_load_file($flux, 'SimpleXMLElement', LIBXML_NOCDATA)){
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


		echo '<pre>';
		print_r($data);
		echo '</pre>';

		if(!@$rss_data=simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)){
			$error = 1;
			$ok[$uid] = "error : ".$flux;
		}
		else {
			file_put_contents($path, "");
			file_put_contents($path, $data);

			$ok[$uid] = "ok";
		}
	}
	else {
		file_put_contents($path, "");
		file_put_contents($path, file_get_contents($flux));

		$ok[$uid] = "ok";
	}

	print_r($ok)
?>
