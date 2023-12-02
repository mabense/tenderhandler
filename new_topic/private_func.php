<?php
require_once(LIB_DIR . "sql.php");


function sqlNewTopic($title, $purpose)
{
    $tTopic = TOPIC_TABLE;
    $fields = "(`title`, `purpose`)";
    $sql = "INSERT INTO $tTopic $fields VALUES (?, ?)";
    $success = sqlPrepareBindExecute(
        $sql,
        "ss",
        [$title, $purpose],
        __FUNCTION__
    );
    return $success;
}