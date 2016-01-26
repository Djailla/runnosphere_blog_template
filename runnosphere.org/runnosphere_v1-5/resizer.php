<?php
	
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	
	$file = $_REQUEST['file'];
	
	if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == "url"){
		$filename = $file;
	}else{
		$t = explode("wp-content", $file);
	
		$filename = dirname(__FILE__)."/../..".$t[1];
	}

	
	/*if(!file_exists($filename)){
		
		print($filename.' not found'); 
		exit(); 
		
	}*/
	
	$imgs_tab = explode('.', $filename);
	$ext = strtolower($imgs_tab[count($imgs_tab)-1]);
	
	switch($ext){
		case 'bmp':
			$type = 'img';
			$image = imagecreatefromwbmp($filename);
		break;

		case 'jpeg':
			$image = imagecreatefromjpeg($filename);
			$type = 'img';
		break;

		case 'jpg':
			$image = imagecreatefromjpeg($filename);
			$type = 'img';
		break;
			
		case 'png':
			$image = imagecreatefrompng($filename);
			$type = 'img';
		break;
			
		case 'gif':
			$image = imagecreatefromgif($filename);
			$type = 'img';
		break;
	
		default:
			print('File load error');
			exit();
		break;
	}
	
	if($type == 'img' && $_REQUEST['width']>0 && $_REQUEST['height']>0){
		
		// Redimensionnement
		$width = $_REQUEST['width'];
		$height = $_REQUEST['height'];
		
		list($width_orig, $height_orig) = getimagesize($filename);
	
		$ratio_orig = $width_orig/$height_orig;

		// Fit the file, white background, and image 100% in center
		if($width / $height > $ratio_orig){
			$height_temp = $height;
			$width_temp = $height * $ratio_orig;
		}else{
			$width_temp = $width;
			$height_temp = $width / $ratio_orig;
		}
                
		$image_p = imagecreatetruecolor($width_temp, $height_temp);
                
		$white = imagecolorallocate($image_p, 255, 255, 255);
		imagefill($image_p, 0, 0, $white);
		
		if($height_temp<$height){		
			$y = round(($height-$height_temp)/2);
		}else{
			$y = 0;
		}
                
                //Correction
                $y = 0;
		
		if($width_temp<$width){
			$x = round(($width-$width_temp)/2);
		}else{
			$x=0;
		}
                
                //correction
                $x=0;
		
		if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == "url"){
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width_temp, $height_temp, $width_orig, $height_orig);
		}else{
			imagecopyresampled($image_p, $image, $x, $y, 0, 0, $width_temp, $height_temp, $width_orig, $height_orig);
		}
	
		// Displays the image
		header('Content-type: image/jpeg');
		imagejpeg($image_p, NULL, 90);		
	}
?>