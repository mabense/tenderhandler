<?php
require_once(LIB_DIR . "sql.php");

$dom = $GLOBALS["dom"];
if ($dom) {

    // Add content
    $conn = sqlConnect();
    $contentTag = sqlQueryPage(
        "Tenders", 
        "SELECT * FROM TENDER", 
        [
            "code", 
            "begins", 
            "ends", 
            "proposed", 
            "granted", 
            "topic", 
            "manager"
        ]
    );
    sqlDisconnect();

    // Add button
    if(isUserAdmin()) {
        $buttons = $dom->getElementById("contentButtons");

        $addTender = $dom->createElement("a", "Add new tender");
        $addTender->setAttribute("class", "a_button");
        $addTender->setAttribute("href", domFindRoute("new_tender"));
        $buttons->appendChild($addTender);
        
        $addTopic = $dom->createElement("a", "Add new topic");
        $addTopic->setAttribute("class", "a_button");
        $addTopic->setAttribute("href", domFindRoute("new_topic"));
        $buttons->appendChild($addTopic);
    }
    else {
        $buttons = $dom->getElementById("contentButtons");
        $buttons->parentNode->removeChild($buttons);
    }
}
