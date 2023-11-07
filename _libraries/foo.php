<?php
// DON'T require: dom.php


function toDisplayText($page)
{
    return ucwords(str_replace("_", " ", $page));
}


function findPage($nextPage) {
	// TODO: GET params
	$route = ROOT . $nextPage . DIRECTORY_SEPARATOR;
	if (file_exists($route . "index.php")) {
		return $nextPage;
	}
	else {
		$alias = ALT_ROUTES[$nextPage];
		$alt_route = ROOT . $alias . DIRECTORY_SEPARATOR;
		if (file_exists($alt_route . "index.php")) {
			return $alias;
		}
	}
	return PAGE . "?missing=" . $nextPage;
}


function redirectTo($pageRoute) {
	header("Location: " . findPage($pageRoute));
	exit;
}