<?php
require_once("./__prologue2.php");

haveSession(DEFAULT_PAGE);
setPage("new_tender");
header("Location: " . ROOT);
exit;