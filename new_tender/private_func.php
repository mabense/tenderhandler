<?php
require_once(LIB_DIR . "sql.php");


function sqlNewTender($code, $begin, $end, $asked, $granted, $topic, $manager)
{
    $fields = "(`code`, `begins`, `ends`, `sum_asked`, `sum_granted`, `topic_id`, `manager`)";
    $sql = "INSERT INTO tender $fields VALUES (?, ?, ?, ?, ?, ?, ?)";
    $success = sqlPrepareBindExecute(
        $sql,
        "sssiiis",
        [$code, $begin, $end, $asked, $granted, $topic, $manager],
        __FUNCTION__
    );
    return $success;
}