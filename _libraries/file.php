<?php

function get_file_extension($file_name) {
	// returns the file extension (substring after last '.')
    return substr(strrchr($file_name,'.'),1);
}


function fix_multi_file_input( $arr ){
	//		$_FILES['field']['key']['index'] --> $_FILES['field']['index']['key']
	//(e.g.	$_FILES['img']['name'][0] --> $_FILES['img'][0]['name']
	// for the filename of the first(0th) file selected in the <input type="file" name="img[]" multiple /> tag)
    foreach( $arr as $key => $all ){
        foreach( $all as $i => $val ){
            $new[$i][$key] = $val;   
        }   
    }
    return $new;
}


function images_upload($multi_file_input_id, $img_dir_on_server, $product_id, $exception_array = array()){
	$kepek = fix_multi_file_input( $_FILES[$multi_file_input_id] );
	$fails = 0;
	$wins = 0;
	$len = count($kepek);
	for ($i = 0; $i < $len; $i++) {
		if (in_array($i, $exception_array, true)) {
			continue;
		}
		$kep = $kepek[$i];
		$name = $kep['name'];
		$tmp_name = $kep['tmp_name'];
	
		$fileextension= get_file_extension($name);
		$fileextension= strtolower($fileextension);
		if (isset($name)) {
			$path = $img_dir_on_server;
			if (empty($name)) {
				return new Error(1, "Nem lett képfájl kiválasztva.");
				exit;
			}
			else {
				if (($fileextension == "jpg") || ($fileextension == "jpeg") || ($fileextension == "png") || ($fileextension == "bmp")) {
					$new_name = $product_id."_".$wins.".".$fileextension;
					if (move_uploaded_file($tmp_name, $path."/".$new_name)) {
						$wins++;
					}
					else {
						$fails++;
					}
				}
				else {
					$fails++;
				}
			}
		}
	}
	if ($wins === 0) {
		return new Error(2, "Nem lett képfájl feltöltve!");
	}
	elseif ($fails === 0) {
		return new Error(0, "Minden fájl fel lett töltve!");
	}
	else {
		return new Error(3, "Nem minden fájl lett feltöltve!");
	}
}


function images_delete($product_id){
	// GET DIR
	$imagesDirectory = "images";
	// LOOP THROUGH DIR
	if(is_dir($imagesDirectory)) {
		$fails = 0;
		$wins = 0;
		$opendirectory = opendir($imagesDirectory);
		while (($image = readdir($opendirectory)) !== false) {
			if (($image == '.') || ($image == '..')) {
				continue;
			}
			$imgFileType = pathinfo($image,PATHINFO_EXTENSION);
			$imgFileName = pathinfo($image,PATHINFO_BASENAME);
			if(
				($imgFileType == 'png') || 
				($imgFileType == 'jpg') || 
				($imgFileType == 'jpeg') || 
				($imgFileType == 'webp') || 
				($imgFileType == 'bmp') || 
				($imgFileType == 'gif') || 
				($imgFileType == 'apng') || 
				($imgFileType == 'pjpeg') || 
				($imgFileType == 'tiff')) {
				// FIND IMAGES
				$imgProductId = substr($imgFileName, 0, strrpos($imgFileName, "_"));
				if($imgProductId === $product_id){
					// DELETE IT
					if (!unlink($imagesDirectory."/".$imgFileName)) {
						$fails++;
						//echo ("$file_pointer cannot be deleted due to an error");
					}
					else {
						$wins++;
						//echo ("$file_pointer has been deleted");
					}
				}
			}
		}
		closedir($opendirectory);
		if ($wins === 0) {
			return new Error(2, "Nem lett képfájl törölve!");
		}
		elseif ($fails === 0) {
			return new Error(0, "Sikeresen törölve lett!");
		}
		else {
			return new Error(3, "Nem minden képfájl lett törölve!");
		}
	 
	}
	else {
		return new Error(1, "Mappa nem található!");
	}
}