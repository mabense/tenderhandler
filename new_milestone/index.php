<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "new_milestone");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if(!auth(false, false, true)){
    redirectTo(ROOT . "home");
    // header("Location: " . ROOT . "home");
    // exit;
}

domHandleMissingPage();

domHandleAction();

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", "./view.htm");

    // $conn = sqlConnect();

    // sqlDisconnect();
    
    domSetTitle(toDisplayText(PAGE));

    domPopFeedback();
}

echo $dom->saveHTML();
