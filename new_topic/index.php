<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "new_topic");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

haveSession();

if(!auth(false, false, true)){
    redirectTo(ROOT, "home");
}

domHandleMissingPage();

domHandleAction();

if (newDOMDocument(BASE_TEMPLATE)) {

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", "./view.htm");

    domSetTitle(toDisplayText(PAGE));
    
    domPopFeedback();
}

global $dom;
echo $dom->saveHTML();
