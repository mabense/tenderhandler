<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "jobs");

require_once(ROOT . "const.php");

require_once(LIB_DIR . "feedback_log.php");
require_once(LIB_DIR . "session.php");
require_once(LIB_DIR . "foo.php");
require_once(LIB_DIR . "dom.php");

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
