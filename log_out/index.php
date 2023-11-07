<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "log_out");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if(!auth(false, true, true)){
    redirectTo(ROOT . "log_in");
    // header("Location: " . ROOT . "log_in");
    // exit;
}

$user = getUserEmail();
$page = "home";

if ($user != false) {
    $conn = sqlConnect();
    $out = sqlLogout($user);
    sqlDisconnect();
    if ($out) {
        pushFeedbackToLog("Logged out successfully.");
        $page = "log_in";
    } elseif (!isThereFeedback()) {
        pushFeedbackToLog("Failed to log out.", true);
        pushFeedbackToLog("user: " . $user . " --- sqlLogout($user): " . $out);
    }
}
redirectTo(ROOT . $page);
// header("Location: " . ROOT . $page);
// exit;