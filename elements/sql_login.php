<?php
require_once("./__prologue2.php");
require_once(LIB_DIR . "sql.php");

haveSession(DEFAULT_PAGE);
$user = false;

$email = $_POST["user"];
$pwd = $_POST["pwd"];

if (
    isset($email)
    && isset($pwd)
) {
    $conn = sqlConnect();
    $user = sqlLogin($email, $pwd);
    sqlDisconnect();
}

if ($user) {
    setUser($user["email"], $user["name"], $user["is_admin"]);
    setPage("home");
    pushFeedbackToLog("Logged in successfully.");
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to log in.", true);
}
header("Location: " . ROOT);
exit;
