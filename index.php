<?php

// Definitions

function try_require_once($path, $require=true, $once=true){
    if (file_exists($path)) {
        if ($require) {
            if ($once) require_once($path);
            else require($path);
        }
        else {
            if ($once) include_once($path);
            else include($path);
        }
    }
    elseif ($require) {
        die("Error: " . $path . " not found!");
    }
}

try_require_once("./libraries/paths.php"); // paths
try_require_once("./libraries/const.php"); // constants
try_require_once("./libraries/foo.php"); // functions

// Session setup

haveSession("log_in");
$page = getPage();
$pageTitle = ucwords(str_replace("_", " ", $page));
$pageContent = PAGE_DIR . $page . ".php";

// DOM build

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {
    
    $titleTag = $dom->getElementsByTagName("title")[0];
    $titleTag->textContent .= " - " . $pageTitle;
    
    try_require_once(TOOLBAR);

    try_require_once($pageContent);

    echo $dom->saveHTML();
}
else {
    echo "Error loading page!";
}

// Session cleanup

session_destroy();
