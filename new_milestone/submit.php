<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = false;
$page = PAGE;

$tender = getTender();

$name = $_POST["name"];
$date = $_POST["date"];
$desc = $_POST["desc"];

$result = false;

if (
    isset($name)
    && isset($date)
    && isset($desc)
) {
    $GLOBALS["conn"] = sqlConnect();
    /* */
    $result = sqlNewMilestone($tender, $name, $date, $desc);
    /*/
    $result = sqlNewTender('proba_5','2020-02-02','2023-12-12',100,10, 'qqqq','q');
    /* */
    sqlDisconnect();
}

if ($result) {
    pushFeedbackToLog("Tender added successfully.");
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to add tender.", true);
}
$page = "milestone_list";
header("Location: " . ROOT . $page);
exit;