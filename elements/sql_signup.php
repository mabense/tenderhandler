<?php
require_once("./__prologue2.php");
require_once(LIB_DIR . "sql.php");

haveSession(DEFAULT_PAGE);
$data = "";

$email = $_POST["user"];
$pwd = $_POST["pwd"];
$repwd = $_POST["repwd"];
$name = $_POST["name"];

if (
    isset($email)
    && isset($pwd)
    && isset($repwd)
    && isset($name)
    //&& strcmp($pwd, $repwd)
) {
    $conn = dbConn();
    $data = dbSignup($email, $pwd, $name, false);
    pushFeedbackToLog($data ? "Signed up successfully." : "Failed to sign up.");
    $conn->close();
}
header("Location: ../");
exit;
