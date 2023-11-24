<?php
require_once(LIB_DIR . "sql.php");


function sqlNewTopic($title, $purpose)
{
    $fields = "(`title`, `purpose`)";
    $sql = "INSERT INTO topic $fields VALUES (?, ?)";
    $success = sqlPrepareBindExecute(
        $sql,
        "ss",
        [$title, $purpose],
        __FUNCTION__
    );
    return $success;
}