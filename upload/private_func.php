<?php
require_once(LIB_DIR . "sql.php");


function sqlUpdateMilestoneProgressAll()
{
    $tMilestone = MILESTONE_TABLE;
    $msListAll = "SELECT `tender`, `number` FROM $tMilestone WHERE `progress` > 0";
    $stmtList = sqlPrepareExecute(
        $msListAll,
        __FUNCTION__
    );
    if (!$stmtList) {
        return false;
    }
    $msList = $stmtList->get_result();
    while ($msRow = $msList->fetch_assoc()) {
        $tender = $msRow["tender"];
        $ms = $msRow["number"];
        sqlUpdateMilestoneProgress($tender, $ms);
    }
    return true;
}


function sqlUpdateMilestoneProgress($tender, $milestone)
{
    $tDocument = DOCUMENT_TABLE;
    $tMilestone = MILESTONE_TABLE;
    $unfulfilled = "(\"" . DOCUMENT_CREATED . "\", \"" . DOCUMENT_REJECTED . "\")";
    $sqlMSProgress = "SELECT `tender`, `number`, 
    (
        SELECT COUNT(`fulfilled`) 
        FROM (
            SELECT * FROM $tDocument WHERE `tender`=? AND `milestone`=?
        ) AS doc
        WHERE `fulfilled` NOT IN $unfulfilled 
    ) AS numerator, 
    ( 
        SELECT COUNT(*) 
        FROM (
            SELECT * FROM $tDocument WHERE `tender`=? AND `milestone`=?
        ) AS doc
    ) AS denominator 
    FROM (
        SELECT * FROM $tMilestone WHERE `tender`=? AND `number`=?
    ) AS ms";
    $stmtProgress = sqlPrepareBindExecute(
        $sqlMSProgress,
        "sisisi",
        [
            $tender,
            $milestone,
            $tender,
            $milestone,
            $tender,
            $milestone
        ],
        __FUNCTION__
    );
    $progressRow = $stmtProgress ? $stmtProgress->get_result()->fetch_assoc() : null;
    if ($progressRow !== null) {
        $progress = 100 * ($progressRow["numerator"] / $progressRow["denominator"]);
        $progressConditions = "`tender`=? AND `number`=?";
        sqlPrepareBindExecute(
            "UPDATE $tMilestone SET `progress`=? WHERE $progressConditions",
            "isi",
            [
                $progress,
                $tender,
                $milestone
            ],
            __FUNCTION__
        );
    }
}


function sqlDeleteDocsKeepLatestN($N)
{
    $tDocument = DOCUMENT_TABLE;
    $statusDeleted = DOCUMENT_DELETED;
    $deleteAll = "UPDATE $tDocument 
    SET `document`=NULL, `file_name`=NULL, `upload_time`=NULL, `fulfilled`=?
    WHERE `upload_id` NOT IN (
        SELECT `upload_id` 
        FROM (
            SELECT `upload_id`, `upload_time` 
            FROM $tDocument 
            HAVING `upload_time` 
            ORDER BY `upload_time` DESC 
            LIMIT $N 
        ) AS latest_uploads
    )";
    $success = sqlPrepareBindExecute(
        $deleteAll,
        "s",
        [
            $statusDeleted
        ],
        __FUNCTION__
    );

    sqlUpdateMilestoneProgressAll();

    return $success;
}