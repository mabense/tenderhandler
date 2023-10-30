<?php
require_once(LIB_DIR . "sql.php");

$dom = $GLOBALS["dom"];
if ($dom) {

    // Add content
    $conn = sqlConnect();
    $contentTag = sqlQueryPage(
        "Schedule", 
        "SELECT * FROM MILESTONE", 
        [
            "month", 
            "milestones due"
        ]
    );
    sqlDisconnect();
}
