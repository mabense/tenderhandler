<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
require_once(ROOT . "libraries/paths.php");
require_once(ROOT . "libraries/const.php");
require_once(ROOT . "libraries/foo.php");

function refresh()
{
	header("Location: " . ROOT);
	exit;
}
