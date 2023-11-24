<?php
require_once(LIB_DIR . "sql.php");
require_once(LIB_DIR . "sql_auth.php");

haveSession();
$user = false;
$page = PAGE;

$email = fromPOST("user");
$pwd = fromPOST("pwd");
$repwd = fromPOST("repwd");
$name = fromPOST("name");

if (
    isset($email)
    && isset($pwd)
    && isset($repwd)
    && isset($name)
) {
    sqlConnect();
    $result = sqlSignup($email, $pwd, $repwd, $name, true);
    sqlDisconnect();
}

if ($result != false) {
    pushFeedbackToLog("Application successful.");
} elseif(!isThereFeedback()) {
    pushFeedbackToLog("Application failed.", true);
}
redirectTo(ROOT,  $page);