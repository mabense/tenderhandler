<?php
require_once("./__prologue2.php");
require_once(LIB_DIR . "sql.php");

$id = $_POST["id"];
$title = $_POST["title"];
$purpose = $_POST["purpose"];

$result = false;

if (
    isset($id)
    && isset($title)
    && isset($purpose)
) {
    haveSession(DEFAULT_PAGE);
    $conn = sqlConnect();
    $result = sqlNewTopic($id, $title, $purpose);
    sqlDisconnect();
}

if ($result) {
    pushFeedbackToLog("Topic added successfully.");
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to add topic.", true);
}
setPage("tenders");
header("Location: " . ROOT);
exit;
