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
	return PAGE . "?missing=" . $nextPage;
}


function redirectTo($root, $pageRoute) {
	header("Location: " . $root . findPage($pageRoute));
	exit;
}