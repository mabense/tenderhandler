<?php
require_once("./__prologue2.php");

haveSession(DEFAULT_PAGE);
setPage("new_topic");
header("Location: " . ROOT);
exit;