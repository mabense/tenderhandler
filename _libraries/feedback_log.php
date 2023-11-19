<?php
// DON'T require: ANYTHING

function isThereFeedback()
{
    $log = fromSESSION("log");
    if (isset($log)) {
        return (is_array($log) && sizeof($log) > 0);
    }
    return false;
}


function getFeedbackLog()
{
    if (isset($_SESSION["log"])) {
        return $_SESSION["log"];
    } else {
        return false;
    }
}


function pushFeedbackToLog($message, $isError = false)
{
    if (!isset($_SESSION["log"])) {
        $_SESSION["log"] = [];
    }
    array_push($_SESSION["log"], [$message, $isError]);
}


function resetFeedbackLog()
{
    if (isset($_SESSION["log"])) {
        $_SESSION["log"] = [];
        unset($_SESSION["log"]);
    }
}
