<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "upload");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if(!auth(false, true, false)){
    redirectTo(ROOT, "home");
}

domHandleMissingPage();

domHandleAction();

$doc = getDocument();

if (newDOMDocument(BASE_TEMPLATE)) {

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", "./view.htm");

    // sqlConnect();

    // sqlDisconnect();
    
    domSetTitle(toDisplayText(getMilestoneTitle() . " - uploading: " . $doc));

    domPopFeedback();
}

echo $dom->saveHTML();
