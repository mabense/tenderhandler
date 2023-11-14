<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "delete_milestone");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if (!auth(false, true, true)) {
    redirectTo(ROOT, "log_in");
}

domHandleMissingPage();

domHandleAction();
$tenderCode = getTender();
$msCode = getMilestone();

if (isset($tenderCode) && isset($msCode)) {
    $conn = sqlConnect();

    $msStmt = sqlPrepareBindExecute(
        "SELECT COUNT(`document`) AS uploaded FROM DOCUMENT WHERE `tender`=? AND `milestone`=?",
        "si",
        [$tenderCode, $msCode],
        __FUNCTION__
    );
    $result = $msStmt->get_result();
    $row = $result->fetch_assoc();
    $uploads = $row["uploaded"];
    if ($uploads > 0) {
        pushFeedbackToLog("Can't delete milestone as $uploads files have already been uploaded!", true);
    } else {
        $delStmt = sqlPrepareBindExecute(
            "DELETE FROM MILESTONE WHERE `tender`=? AND `number`=?",
            "si",
            [$tenderCode, $msCode],
            __FUNCTION__
        );

        // $result = $delStmt->get_result();

        if ($delStmt != false) {
            pushFeedbackToLog("Milestone deleted.");
        } else {
            pushFeedbackToLog("Failed to delete milestone.", true);
        }
    }

    sqlDisconnect();
}
else {
    pushFeedbackToLog("Session error.", true);
}

redirectTo(ROOT, "milestone_list");