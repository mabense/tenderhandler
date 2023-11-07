<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "milestone_list");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

$tenderCode = getTender();

if(!auth(false, true, true)){
    redirectTo(ROOT . "log_in");
    // header("Location: " . ROOT . "log_in");
    // exit;
}

domHandleMissingPage();

domHandleAction();

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");
    // domAddStyle(STYLE_DIR . "query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    $conn = sqlConnect();
    sqlQueryContentParam(
        "SELECT * FROM MILESTONE WHERE `tender`=?", 
        "s", 
        [$tenderCode], 
        [
            "tender", 
            "number", 
            "name", 
            "date", 
            "description", 
            "progress"
        ], 
        "milestone", 
        [
            "number"
        ]
    );
    sqlDisconnect();

    if(isUserAdmin()) {
        $buttons = $dom->getElementById("contentButtons");

        $addTender = $dom->createElement("a", "Add new milestone");
        $addTender->setAttribute("class", "a_button");
        $addTender->setAttribute("href", "../" . findPage("new_milestone"));
        $buttons->appendChild($addTender);
        
        $addTopic = $dom->createElement("a", "Back to tender");
        $addTopic->setAttribute("class", "a_button");
        $addTopic->setAttribute("href", "../" . findPage("tender"));
        $buttons->appendChild($addTopic);
    }
    else {
        $buttons = $dom->getElementById("contentButtons");
        $buttons->parentNode->removeChild($buttons);
    }

    domSetTitle(toDisplayText(PAGE));

    domPopFeedback();
}

echo $dom->saveHTML();
