<?php
require_once(LIB_DIR . "sql.php");

$dom = $GLOBALS["dom"];
if ($dom) {

    // Add content
    $conn = sqlConnect();
    $contentTag = sqlQueryPage(
        "Managers", 
        "SELECT `email`, `name`, `now_active`, `last_active` FROM USER WHERE `is_admin`=FALSE", 
        [
            "email", 
            "name", 
            "active", 
            "last active"
        ]
    );
    sqlDisconnect();
}
