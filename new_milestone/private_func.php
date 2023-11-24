<?php
require_once(LIB_DIR . "sql.php");


function sqlNewMilestone($tender, $name, $date, $description)
{
    $number = 1;
    $numStmt = sqlPrepareBindExecute(
        "SELECT MAX(`number`) AS max FROM milestone WHERE `tender`=?",
        "s",
        [$tender],
        __FUNCTION__
    );
    $numResult = $numStmt->get_result();
    if ($numRow = $numResult->fetch_assoc()) {
        $number = $numRow["max"] + 1;
    }
    $fields = "(`tender`, `number`, `name`, `date`, `description`)";
    $sql = "INSERT INTO milestone $fields VALUES (?, ?, ?, ?, ?)";
    $success = sqlPrepareBindExecute(
        $sql,
        "sisss",
        [$tender, $number, $name, $date, $description],
        __FUNCTION__
    );
    return $success;
}