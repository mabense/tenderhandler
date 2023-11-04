<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = false;
$page = PAGE;

$id = $_POST["id"];
$title = $_POST["title"];
$purpose = $_POST["purpose"];

$result = false;

if (
    isset($id)
    && isset($title)
    && isset($purpose)
) {
    $GLOBALS["conn"] = sqlConnect();
    $result = sqlNewTopic($id, $title, $purpose);
    sqlDisconnect();
}

if ($result) {
    pushFeedbackToLog("Topic added successfully.");
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to add topic.", true);
}
$page = "tender_list";
header("Location: " . ROOT . $page);
exit;