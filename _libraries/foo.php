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

function fromGET($nameInGET) {
	if(isset($_GET[$nameInGET])) {
		return $_GET[$nameInGET];
	}
	return null;
}

function fromPOST($nameInPOST) {
	if(isset($_POST[$nameInPOST])) {
		return $_POST[$nameInPOST];
	}
	return null;
}