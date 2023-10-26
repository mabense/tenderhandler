<?php
require_once("./__prologue2.php");

haveSession(DEFAULT_PAGE);
setPage("jobs");
header("Location: " . ROOT);
exit;