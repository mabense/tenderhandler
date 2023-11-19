<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = false;
$page = PAGE;

$title = $_POST["title"];
$purpose = $_POST["purpose"];

$result = false;

if (
    isset($title)
    && isset($purpose)
) {
    sqlConnect();
    $result = sqlNewTopic($title, $purpose);
    sqlDisconnect();
}

if ($result) {
    pushFeedbackToLog("Topic added successfully.");
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to add topic.", true);
}
$page = "tender_list";
redirectTo(ROOT, $page);