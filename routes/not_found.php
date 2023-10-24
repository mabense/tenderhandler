<?php
require_once("./__prologue.php");

haveSession(DEFAULT_PAGE);
$page = $_GET["r"];
$title = pageTitle($page);
pushFeedbackToLog("\"" . $title . "\" not found.", true);
refresh();