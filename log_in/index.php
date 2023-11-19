<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "log_in");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

haveSession();

if(!auth(true, false, false)){
    redirectTo(ROOT, "home");
}

domHandleMissingPage();

domHandleAction();

if (newDOMDocument(BASE_TEMPLATE)) {

    domMakeToolbar([
        "sign_up",
        "jobs"
    ]);

    domAppendTemplateTo("content", "./view.htm");

    domSetTitle(toDisplayText(PAGE));
    
    domPopFeedback();
}

global $dom;
echo $dom->saveHTML();
