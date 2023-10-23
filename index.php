<?php
require_once("./prologue.php"); // --> foo, const, paths

// PAGE - LOAD TEMPLATE
$dom = new DOMDocument();
$dom->loadHTMLFile(BASE_TEMPLATE); // <-- paths

// PAGE - FILL TEMPLATE // <-- foo
haveSession("login");
$page = getPage();


?>