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
    && ($pwd === $repwd)
) {
    $conn = sqlConnect();
    $result = sqlSignup($email, $pwd, $name, true);
    sqlDisconnect();
}

if ($result) {
    pushFeedbackToLog("Application successful.");
} else {
    pushFeedbackToLog("Application failed.", true);
}
header("Location: " . ROOT);
exit;
