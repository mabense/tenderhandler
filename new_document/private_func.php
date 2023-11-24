<?php
require_once(LIB_DIR . "sql.php");


function sqlNewDocument($tender, $ms, $req, $parti, $submit_date, $verify_date)
{
    $fields = "(`tender`, `milestone`, `requirement`, `participant`, `deadline_submit`, `deadline_verify`, `fulfilled`)";
    $sql = "INSERT INTO document $fields VALUES (?, ?, ?, ?, ?, ?, ?)";
    $success = sqlPrepareBindExecute(
        $sql,
        "sisssss",
        [
            $tender,
            $ms,
            $req,
            $parti,
            $submit_date,
            $verify_date,
            DOCUMENT_CREATED
        ],
        __FUNCTION__
    );
    return $success;
}