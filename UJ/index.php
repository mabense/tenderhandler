<?php
define("ROOT", "." . DIRECTORY_SEPARATOR);
define("PAGE", "");

require_once(ROOT . "const.php");

header("Location: " . ROOT . DEFAULT_PAGE);
exit;