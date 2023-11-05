<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "manager_list");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

domHandleMissingPage();

domHandleAction();

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");
    // domAddStyle(STYLE_DIR . "query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    $conn = sqlConnect();
    sqlQueryContent(
        "SELECT `email`, `name`, `now_active`, `last_active` FROM USER WHERE `is_admin`=FALSE", 
        [
            "email", 
            "name", 
            "active", 
            "last active"
        ]
    );
    sqlDisconnect();
    
    $buttons = $dom->getElementById("contentButtons");
    $buttons->parentNode->removeChild($buttons);

    domSetTitle(toDisplayText(PAGE));

    domPopFeedback();
}

echo $dom->saveHTML();
