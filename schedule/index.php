<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "schedule");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

domHandleMissingPage();

domHandleAction();

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domSetTitle(toDisplayText(PAGE));

    domAddStyle("../_styles/query_page.css");
    // domAddStyle(STYLE_DIR . "query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    $conn = sqlConnect();
    sqlQueryContent(
        "SELECT * FROM MILESTONE",
        [
            "month",
            "milestones due"
        ]
    );
    sqlDisconnect();
    
    $buttons = $dom->getElementById("contentButtons");
    $buttons->parentNode->removeChild($buttons);

    domPopFeedback();
}

echo $dom->saveHTML();
