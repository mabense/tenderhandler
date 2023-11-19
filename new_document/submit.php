<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = false;
$page = PAGE;

$tender = getTender();
$ms = getMilestone();

$req = $_POST["req"];
$parti = $_POST["parti"];
$submit_date = $_POST["submit_date"];
$verify_date = $_POST["verify_date"];

$result = false;

if (
    isset($req)
    && isset($parti)
    && isset($submit_date)
    && isset($verify_date)
) {
    sqlConnect();
    $result = sqlNewDocument($tender, $ms, $req, $parti, $submit_date, $verify_date);
    sqlDisconnect();
}

if ($result) {
    pushFeedbackToLog("Document added successfully.");
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to add document.", true);
}
$page = "document_list";
redirectTo(ROOT, $page);
