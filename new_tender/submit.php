<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = getUserEmail();
$page = PAGE;

$code = fromPOST("code");
$begin = fromPOST("begin");
$end = fromPOST("end");
$asked = fromPOST("asked");
$granted = fromPOST("granted");
$topic = fromPOST("topic");
$manager = fromPOST("manager");

$result = false;

if (
    isset($user)
    && isset($code)
    && isset($begin)
    && isset($end)
    && isset($asked)
    && isset($granted)
    && isset($topic)
    && isset($manager)
) {
    sqlConnect();
    $result = sqlNewTender($code, $begin, $end, $asked, $granted, $topic, $manager) 
    && sqlPrepareBindExecute(
        "INSERT INTO ADMIN (`admin`, `tender`) VALUES (?, ?)", 
        "ss", 
        [$user, $code], 
        __FUNCTION__
    );
    sqlDisconnect();
}

if ($result) {
    pushFeedbackToLog("Tender added successfully.");
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to add tender.", true);
}
$page = "tender_list";
redirectTo(ROOT, $page);