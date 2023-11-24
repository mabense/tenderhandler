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
    $result = sqlSignup($email, $pwd, $repwd, $name, false);
    sqlDisconnect();
}

if ($result != false) {
    pushFeedbackToLog("Signed up successfully.");
} elseif(!isThereFeedback()) {
    pushFeedbackToLog("Failed to sign up.", true);
}
redirectTo(ROOT, $page);