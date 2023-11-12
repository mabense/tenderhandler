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
    $GLOBALS["conn"] = sqlConnect();
    $result = sqlSignup($email, $pwd, $repwd, $name, true);
    sqlDisconnect();
}

if ($result != false) {
    pushFeedbackToLog("Application successful.");
} elseif(!isThereFeedback()) {
    pushFeedbackToLog("Application failed.", true);
}
redirectTo(ROOT,  $page);