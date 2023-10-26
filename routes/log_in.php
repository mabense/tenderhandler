<?php
require_once("./__prologue2.php");

haveSession(DEFAULT_PAGE);
setPage("log_in");
header("Location: " . ROOT);
exit;