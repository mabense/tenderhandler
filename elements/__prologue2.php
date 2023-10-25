<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);

define("ELEM_DIR", ROOT . "elements" . DIRECTORY_SEPARATOR);
define("LIB_DIR", ROOT . "libraries" . DIRECTORY_SEPARATOR);
define("PAGE_DIR", ROOT . "pages" . DIRECTORY_SEPARATOR);

define("BASE_TEMPLATE", ELEM_DIR . "base.htm");
define("TOOLBAR", ELEM_DIR . "toolbar.htm");

require_once(LIB_DIR . "const.php");
require_once(LIB_DIR . "foo.php");
