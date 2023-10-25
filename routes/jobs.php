<?php
require_once("./__prologue.php");

haveSession(DEFAULT_PAGE);
setPage("jobs");
header("Location: " . ROOT);
exit;