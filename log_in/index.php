<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "log_in");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

haveSession();

domHandleMissingPage();

domHandleAction();

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domMakeToolbar([
        "sign_up",
        "jobs"
    ]);

    domAppendTemplateTo("content", "./view.htm");

    domSetTitle(toDisplayText(PAGE));
    
    domPopFeedback();
}

echo $dom->saveHTML();
