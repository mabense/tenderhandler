<?php
require_once("./__prologue2.php");

haveSession(DEFAULT_PAGE);
$page = $_GET["r"];
$title = pageTitle($page);
pushFeedbackToLog("\"" . $title . "\" not found.", true);
header("Location: " . ROOT);
exit;