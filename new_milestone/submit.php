<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = false;
$page = PAGE;

$tender = getTender();

$name = fromPOST("name");
$date = fromPOST("date");
$desc = fromPOST("desc");

$result = false;

if (
    isset($name)
    && isset($date)
    && isset($desc)
) {
    sqlConnect();
    /* */
    $result = sqlNewMilestone($tender, $name, $date, $desc);
    /*/
    $result = sqlNewTender('proba_5','2020-02-02','2023-12-12',100,10, 'qqqq','q');
    /* */
    sqlDisconnect();
}

if ($result) {
    pushFeedbackToLog("Milestone added successfully.");
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to add milestone.", true);
}
$page = "milestone_list";
redirectTo(ROOT, $page);