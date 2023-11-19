<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = false;
$page = PAGE;

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
    sqlConnect();
    $result = sqlSignup($email, $pwd, $repwd, $name, false);
    sqlDisconnect();
}

if ($result != false) {
    pushFeedbackToLog("Signed up successfully.");
} elseif(!isThereFeedback()) {
    pushFeedbackToLog("Failed to sign up.", true);
}
redirectTo(ROOT, $page);