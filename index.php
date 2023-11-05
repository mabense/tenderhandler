<?php
define("ROOT", "." . DIRECTORY_SEPARATOR);
define("PAGE", "");

require_once(ROOT . "const.php");
// require_once(ROOT . "requirements.php");

// haveSession();

// domHandleMissingPage();

header("Location: " . ROOT . DEFAULT_PAGE);
exit;