<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = false;
$page = PAGE;

$code = $_POST["code"];
$begin = $_POST["begin"];
$end = $_POST["end"];
$asked = $_POST["asked"];
$granted = $_POST["granted"];
$topic = $_POST["topic"];
$manager = $_POST["manager"];

$result = false;

if (
    isset($code)
    && isset($begin)
    && isset($end)
    && isset($asked)
    && isset($granted)
    && isset($topic)
    && isset($manager)
) {
    $GLOBALS["conn"] = sqlConnect();
    /* */
    $result = sqlNewTender($code, $begin, $end, $asked, $granted, $topic, $manager);
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
$page = "tender_list";
redirectTo(ROOT . $page);
// header("Location: " . ROOT . $page);
// exit;