<?php
    function randomPasskey($length) 
    { 
		$possible = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ; 
	 
		$str = ""; 
		mt_srand((double)microtime() * 1000000); 

		while ( strlen($str) < $length ) 
		{ 
			$str .= substr($possible, mt_rand(0, strlen($possible) - 1), 1); 
		} 

		return($str); 
    }
    
    function adjustDate($month, $year)
    {
        $a = array();  
        $a[0] = $month;
        $a[1] = $year;
        
        while ($a[0] > 12)
        {
            $a[0] -= 12;
            $a[1]++;
        }
        
        while ($a[0] <= 0)
        {
            $a[0] += 12;
            $a[1]--;
        }

        return $a;
    }

    function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12)
        {
            return 0;
        }
	
	$daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        $d = $daysInMonth[$month - 1];
   
        if ($month == 2)
        {  
            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                    {
                        $d = 29;
                    }
                }
                else
                {
                    $d = 29;
                }
            }
        }
        return $d;
    }

    function getMysqlDate($cdate)
    {
        if( strlen($cdate) ==0 ) return "0000-00-00";

        $date_array = explode("/", $cdate);
        return $date_array[2]."-".$date_array[0]."-".$date_array[1];
    }

    function getDisplayDate($cdate)
    {
        if( strlen($cdate) ==0 ) return "";

        $date_array = explode("-", $cdate);
        return $date_array[1]."/".$date_array[2]."/".$date_array[0];
    }

    function getMysqlTime($timeValue)
    {
	if( strlen($timeValue) ==0 ) return "00:00:00";
	
        $tf_array = explode(" ", $timeValue);
        $time_array = explode(":", $tf_array[0]);

        if($tf_array[1] == "AM")
        {
	    if($time_array[0] == 12)
	    {
		    return "00:".$time_array[1].":00";
	    }
	    else
	    {
		    return $tf_array[0].":00";
	    }
        }
        else
        {
	    if($time_array[0] == 12)
	    {
		    return $tf_array[0].":00";
	    }
	    else
	    {
		    return ($time_array[0]+12).":".$time_array[1].":00";
	    }
        }
    }

    function getDisplayTime($timeValue)
    {
        return date("g:i A", strtotime($timeValue));
    }
    
    //You do not need to alter these functions
    function resizeThumbnailImage($crop_image, $image, $width, $height, $start_width, $start_height, $scale)
    {
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($image); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
				break;
			case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
				break;
		}
		imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
		imageinterlace($newImage);
		switch($imageType) {
			case "image/gif":
			imagegif($newImage, $crop_image); 
			break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
			imagejpeg($newImage, $crop_image,90); 
			break;
			case "image/png":
			case "image/x-png":
			imagepng($newImage, $crop_image);  
			break;
		}
		return $crop_image;
    }
    
    function imagecreatefrom($tmpfile, $info) {
		switch ($info) {
			case 1:
			$new_img = imagecreatefromgif($tmpfile);
			break;
			case 2:
			$new_img = imagecreatefromjpeg($tmpfile);
			break;
			case 3:
			$new_img = imagecreatefrompng($tmpfile);
			break;
			default:
			$new_img = imagecreatefromgif($tmpfile);
		}
		
		return $new_img;
    }

	function getGPSFromAddress($street, $postcode, $city, $region)
	{
		$gps_info = array();
		$gps_info["lat"] = 0;
		$gps_info["lng"] = 0;

		$address = urlencode($street.", ".$postcode.", ".$city.", ".$region);
		$link = "http://maps.google.com/maps/api/geocode/xml?address=".$address."&sensor=false";
		$file = file_get_contents($link);

		if($file) 
		{
			$get = simplexml_load_string($file);

			if ($get->status == "OK") {
				$gps_info["lat"] = (float)$get->result->geometry->location->lat;
				$gps_info["lng"] = (float)$get->result->geometry->location->lng;
			}
		}

		return $gps_info;
	}
	
	function echo_array($array)
	{
	    echo "<pre>";
	    print_r($array);
	    echo "</pre>";
	}

?>