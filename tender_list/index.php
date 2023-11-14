<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "tender_list");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if(!auth(false, true, true)){
    redirectTo(ROOT, "log_in");
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
    
    $buttons = $dom->getElementById("contentButtons");

    if(isUserAdmin()) {

        $addTender = $dom->createElement("a", "Add New Tender");
        $addTender->setAttribute("class", "a_button");
        $addTender->setAttribute("href", "../" . findPage("new_tender"));
        $buttons->appendChild($addTender);
        
        $addTopic = $dom->createElement("a", "Add New Topic");
        $addTopic->setAttribute("class", "a_button");
        $addTopic->setAttribute("href", "../" . findPage("new_topic"));
        $buttons->appendChild($addTopic);
    }
    else {
        $buttons->parentNode->removeChild($buttons);
    }

    domSetTitle(toDisplayText(PAGE));

    domPopFeedback();
}

echo $dom->saveHTML();
