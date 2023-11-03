<?php
require_once("./__prologue2.php");

haveSession(DEFAULT_PAGE);
$tender = $_GET["t"];
setPage("tender" . $tender);
setTenderCode($tender);
header("Location: " . ROOT);
exit;