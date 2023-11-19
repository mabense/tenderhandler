<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "home");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

haveSession();

if(!auth(false, true, true)){

    redirectTo(ROOT, "log_in");
}

domHandleMissingPage();

domHandleAction();

if (newDOMDocument(BASE_TEMPLATE)) {

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", "./view.htm");

    domSetTitle(toDisplayText(PAGE));
    
    domPopFeedback();
}

echo $dom->saveHTML();
