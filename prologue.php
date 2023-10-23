<?php


function successBool($expr){
	return ($expr !== false);
}


function try_require_once($path, $require=true, $once=true){
		if (file_exists($path)) {
			if ($require) {
				if ($once) require_once($path);
				else require($path);
			}
			else {
				if ($once) include_once($path);
				else include($path);
			}
		}
        elseif ($require) {
            die("Error: " . $path . " not found!");
        }
}


function try_require_once_each($array_of_paths, $require=true, $once=true){
	foreach ($array_of_paths as $path) {
		try_require_once($path);
	}
}



try_require_once_each([
    "./libraries/paths.php", 
    "./libraries/const.php", 
    "./libraries/foo.php"
]);

?>