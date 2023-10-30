<?php
require_once("./__prologue2.php");

haveSession(DEFAULT_PAGE);
setPage("schedule");
header("Location: " . ROOT);
exit;