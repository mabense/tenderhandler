<?php
require_once("./__prologue2.php");

haveSession(DEFAULT_PAGE);
setPage("sign_up");
header("Location: " . ROOT);
exit;