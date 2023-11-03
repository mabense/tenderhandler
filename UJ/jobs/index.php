<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "jobs");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");


haveSession();

domHandleMissingPage();

domHandleAction();

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domSetTitle(toDisplayText(PAGE));

    domMakeToolbar([
        "log_in",
        "sign_up"
    ]);

    domAppendTemplateTo("content", "./view.htm");
    
    domPopFeedback();
}

echo $dom->saveHTML();
