<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "tender_list");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if(!auth(false, true, true)){
    header("Location: " . ROOT . "log_in");
    exit;
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
    sqlQueryContent(
        "SELECT * FROM TENDER", 
        [
            "code", 
            "begins", 
            "ends", 
            "proposed", 
            "granted", 
            "topic", 
            "manager"
        ], 
        "tender", 
        [
            "code"
        ]
    );
    sqlDisconnect();

    if(isUserAdmin()) {
        $buttons = $dom->getElementById("contentButtons");

        $addTender = $dom->createElement("a", "Add new tender");
        $addTender->setAttribute("class", "a_button");
        $addTender->setAttribute("href", "../" . findPage("new_tender"));
        $buttons->appendChild($addTender);
        
        $addTopic = $dom->createElement("a", "Add new topic");
        $addTopic->setAttribute("class", "a_button");
        $addTopic->setAttribute("href", "../" . findPage("new_topic"));
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
