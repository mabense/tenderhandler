<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = false;
$page = PAGE;

$email = fromPOST("user");
$pwd = fromPOST("pwd");

if (
    isset($email)
    && isset($pwd)
) {
    sqlConnect();
    $user = sqlLogin($email, $pwd);
    sqlDisconnect();
}

if ($user != false) {
    setUser($user["email"], $user["name"], $user["is_admin"]);
    pushFeedbackToLog("Logged in successfully.");
    $page = "home";
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to log in.", true);
}
redirectTo(ROOT, $page);