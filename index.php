<?php
define("ROOT", "." . DIRECTORY_SEPARATOR);
define("PAGE", "");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

// haveSession();

domHandleMissingPage();

redirectTo(ROOT,  DEFAULT_PAGE);