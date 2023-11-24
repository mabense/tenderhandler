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

handleMissingPage();

handleAction();

$doc = getDocument();

if (newDOMDocument(BASE_TEMPLATE)) {

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", "./view.htm");

    pushFeedbackToLog("This demo site stores only the " . MAX_FILE_COUNT . " latest uploads!");
    
    domSetTitle(toDisplayText(getMilestoneTitle() . " - uploading: " . $doc));

    domPopFeedback();
}

global $dom;
echo $dom->saveHTML();
