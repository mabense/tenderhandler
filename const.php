<?php
if (!defined("ROOT")) {
    echo "Error: root directory not found!";
    exit;
}
if (!defined("PAGE")) {
    echo "Error: page directory not found!";
    exit;
}

// Directory paths

define("LIB_DIR", ROOT . "_libraries" . DIRECTORY_SEPARATOR);
define("TEMPLATE_DIR", ROOT . "_templates" . DIRECTORY_SEPARATOR);
define("STYLE_DIR", ROOT . "_styles" . DIRECTORY_SEPARATOR);
define("CONFIG_DIR", ROOT . "_configs" . DIRECTORY_SEPARATOR);

// File paths

define("BASE_TEMPLATE", TEMPLATE_DIR . "base.htm");
define(
    "CONFIG_FILE",
    CONFIG_DIR .
        (file_exists(CONFIG_DIR . "localhost.php")
            ? "localhost"
            : "livehost")
        . ".php"
);

// Pages

define("DEFAULT_PAGE", "home");
