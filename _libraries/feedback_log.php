<?php
// DON'T require: ANYTHING

function isThereFeedback()
{
    return (is_array($_SESSION["log"]) && sizeof($_SESSION["log"]) > 0);
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