<?php
require_once("./__prologue.php");

haveSession(DEFAULT_PAGE);
setPage("log_in");
header("Location: " . ROOT);
exit;