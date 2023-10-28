<?php
require_once("./__prologue2.php");

haveSession(DEFAULT_PAGE);
setPage("home");
header("Location: " . ROOT);
exit;