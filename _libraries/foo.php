<?php
// DON'T require: dom.php


function toDisplayText($page)
{
    return ucwords(str_replace("_", " ", $page));
}


function findPage($nextPage) {
	$route = ROOT . $nextPage . DIRECTORY_SEPARATOR;
	if (file_exists($route . "index.php")) {
		return $nextPage;
	}
	return PAGE . "?missing=" . $nextPage;
}


function redirectTo($pageRoute) {
	header("Location: " . $pageRoute);
	exit;
}