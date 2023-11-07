<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "jobs");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

haveSession();

if(!auth(true, false, false)){

    redirectTo(ROOT . "home");
    // header("Location: " . ROOT . "home");
    // exit;
}

domHandleMissingPage();

domHandleAction();

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domMakeToolbar([
        "log_in",
        "sign_up"
    ]);

    domAppendTemplateTo("content", "./view.htm");

    domSetTitle(toDisplayText(PAGE));
    
    domPopFeedback();
}

echo $dom->saveHTML();
