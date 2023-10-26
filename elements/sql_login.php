<?php
require_once("./__prologue2.php");
require_once(LIB_DIR . "sql.php");

haveSession(DEFAULT_PAGE);

$conn = dbConn();



$sql = "SELECT * FROM USER";
$data = dbQuery($sql, $conn);
while ($row = mysqli_fetch_assoc($data)) {
    $feedback = _sqlRowDump($row);
    pushFeedbackToLog($feedback);
}

$conn->close();

header("Location: " . ROOT);
exit;
