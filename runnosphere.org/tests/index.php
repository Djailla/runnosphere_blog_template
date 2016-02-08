<?php

	libxml_use_internal_errors(true);

	// URL OK :
	// $rss = "http://feeds.feedburner.com/LeBlogDeDjailla";

	// URL KO :
	$rss = "http://www.jerem-runner.com/rss";

	if(!empty($rss)){
		$flux = stripslashes($rss);
		$error = 0;

		print_r($flux);

		if(!@$fluxrss=simplexml_load_file($flux, 'SimpleXMLElement', LIBXML_NOCDATA)){
			    $errors = libxml_get_errors();
			    print_r($errors);

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
	print_r($ok);

?>
