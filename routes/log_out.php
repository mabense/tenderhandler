<?php
require_once("./__prologue2.php");
require_once(LIB_DIR . "sql.php");

haveSession(DEFAULT_PAGE);
$user = getUserEmail();

if ($user) {
    $conn = sqlConnect();
    $out = sqlLogout($user);
    sqlDisconnect();
    if ($out) {
        pushFeedbackToLog("Logged out successfully.");
        setPage("log_in");
    } elseif (!isThereFeedback()) {
        pushFeedbackToLog("Failed to log out.", true);
    }
}
header("Location: " . ROOT);
exit;
