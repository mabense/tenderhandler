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
            "topic_id", 
            "manager"
        ]
    );
    sqlDisconnect();

    // Add button
    if(isUserAdmin()) {
        $addButton = $dom->createElement("a", "Add new tender");
        $addButton->setAttribute("href", domFindRoute("new_tender"));
        $contentTag->appendChild($addButton);
    }
}
