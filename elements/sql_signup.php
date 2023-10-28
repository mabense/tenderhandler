<?php
require_once("./__prologue2.php");
require_once(LIB_DIR . "sql.php");

haveSession(DEFAULT_PAGE);
$result = false;

$email = $_POST["user"];
$pwd = $_POST["pwd"];
$repwd = $_POST["repwd"];
$name = $_POST["name"];

if (
    isset($email)
    && isset($pwd)
    && isset($repwd)
    && isset($name)
) {
    $conn = sqlConnect();
    $result = sqlSignup($email, $pwd, $repwd, $name, false);
    sqlDisconnect();
}

if ($result) {
    pushFeedbackToLog("Signed up successfully.");
} elseif(!isThereFeedback()) {
    pushFeedbackToLog("Failed to sign up.", true);
}
header("Location: " . ROOT);
exit;
