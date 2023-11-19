<?php
require_once(LIB_DIR . "sql.php");

haveSession();

$user = false;
$page = PAGE;

$code = getTender();
$manager = $_POST["manager"];

$result = false;

if (
    isset($code)
    && isset($manager)
) {
    sqlConnect();
    $result = sqlPrepareBindExecute(
        "UPDATE TENDER SET `manager`=? WHERE `code`=?", 
        "ss", 
        [$manager, $code], 
        __FUNCTION__
    );
    
    sqlDisconnect();
}

if ($result != false) {
    pushFeedbackToLog("Tender updated successfully.");
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to update tender.", true);
}
$page = "tender";
redirectTo(ROOT, $page);