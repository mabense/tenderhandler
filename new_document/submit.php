<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = false;
$page = PAGE;

$tender = getTender();
$ms = getMilestone();

$req = fromPOST("req");
$parti = fromPOST("parti");
$submit_date = fromPOST("submit_date");
$verify_date = fromPOST("verify_date");

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
