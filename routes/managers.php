<?php
require_once("./__prologue2.php");

haveSession(DEFAULT_PAGE);
setPage("managers");
header("Location: " . ROOT);
exit;