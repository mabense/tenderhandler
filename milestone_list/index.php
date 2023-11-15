<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "milestone_list");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

$tenderCode = getTender();

if (!auth(false, true, true)) {
    redirectTo(ROOT, "log_in");
}

domHandleMissingPage();

domHandleAction();

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");
    // domAddStyle(STYLE_DIR . "query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    // $sql = "SELECT * FROM MILESTONE WHERE `tender`=?";
    // $sql = "SELECT `number`, `name`, `date`, `description`, `progress` FROM MILESTONE WHERE `tender`=?";
    // $sql = "SELECT `number`, `name`, `date`, COUNT(DOCUMENT.`document`) AS doc_up, COUNT(DOCUMENT.`requirement`) AS doc_req " .
    //     "FROM MILESTONE LEFT JOIN DOCUMENT " .
    //     "ON MILESTONE.`tender`=DOCUMENT.`tender` AND MILESTONE.`number`=DOCUMENT.`milestone`" .
    //     "WHERE MILESTONE.`tender`=?";
    // $sql = "SELECT `number`, `name`, `date` FROM MILESTONE WHERE `tender`=?";
    // $sql = "SELECT `number`, `name`, `date`, NOT ISNULL(DOCUMENT.`document`) AS doc_up, DOCUMENT.`requirement` AS doc_req " .
    //     "FROM MILESTONE LEFT JOIN DOCUMENT " .
    //     "ON MILESTONE.`tender`=DOCUMENT.`tender` AND MILESTONE.`number`=DOCUMENT.`milestone`" .
    //     "WHERE MILESTONE.`tender`=?";
    /* * /
    $sql = "SELECT MSDOC.`number`, MSDOC.`name`, MSDOC.`date`, SUM(MSDOC.`doc_up`) AS `doc_ups`, SUM(MSDOC.`doc_req`) AS `doc_reqs`
    FROM(
        SELECT `number`, `name`, `date`, NOT ISNULL(DOCUMENT.`document`) AS `doc_up`, NOT ISNULL(DOCUMENT.`requirement`) AS `doc_req` 
        FROM MILESTONE LEFT JOIN DOCUMENT 
        ON MILESTONE.`tender`=DOCUMENT.`tender` AND MILESTONE.`number`=DOCUMENT.`milestone` 
        WHERE MILESTONE.`tender`='ASDasd'
    ) AS MSDOC
    GROUP BY MSDOC.`number`";
    /* /
    $sql = "SELECT `milestone`,  `paid`, `sum_granted`
    FROM(
        SELECT `milestone`, SUM(`sum_paid`) AS `paid`
        FROM DOCUMENT 
        WHERE `tender`='ASDasd'
    ) AS DOC NATURAL RIGHT JOIN (
        SELECT MILESTONE.`number` AS `milestone`, `sum_granted`
        FROM MILESTONE LEFT JOIN TENDER 
        ON MILESTONE.`tender`=TENDER.`code`
        WHERE `code`='ASDasd'
    ) AS TEN";
    /* * /
    $sql = "SELECT `milestone`, `name`, `date`, SUM(`has_file`) AS files, SUM(`has_req`) AS reqs, SUM(`sum_paid`) AS paid, `sum_granted`
    FROM (
        SELECT * FROM (
            SELECT * FROM (
                SELECT `tender`, `number` AS milestone, `name`, `date`
                FROM MILESTONE 
                WHERE `tender`='ASDasd'
            ) AS MS NATURAL LEFT JOIN (
                SELECT `code` AS tender, `sum_granted`
                FROM TENDER
            ) AS TEN
        ) AS TEN_MS NATURAL LEFT JOIN (
            SELECT `tender`, `milestone`, NOT ISNULL(`document`) AS has_file, NOT ISNULL(`requirement`) AS has_req, `sum_paid`
            FROM DOCUMENT
        ) AS DOC
    ) AS EVERYTHING
    GROUP BY EVERYTHING.`tender`, EVERYTHING.`milestone`";

    /* * /
    $sql = "SELECT `tender`, `milestone` AS number, `name`, `date`, 
    SUM(NOT ISNULL(`document`)) AS files, SUM(NOT ISNULL(`requirement`)) AS reqs, 
    SUM(NOT ISNULL(`sum_paid`)) AS paid, `sum_granted`
    FROM (
        SELECT * FROM (
            SELECT * FROM (
                SELECT `tender`, `number` AS milestone, `name`, `date`
                FROM MILESTONE 
                WHERE `tender`='ASDasd'
            ) AS MS NATURAL LEFT JOIN (
                SELECT `code` AS tender, `sum_granted`
                FROM TENDER
            ) AS TEN
        ) AS TEN_MS NATURAL LEFT JOIN (
            SELECT `tender`, `milestone`, `document`, `requirement`, `sum_paid`
            FROM DOCUMENT
        ) AS DOC
    ) AS EVERYTHING
    GROUP BY EVERYTHING.`tender`, EVERYTHING.`milestone`";
    /* /
    $sql = "SELECT `tender`, `number`, `name`, `date` 
    FROM MILESTONE 
    WHERE `tender`='BeadandóDB'";
    /* */
    $sql = "SELECT `tender`, `milestone` AS number, `name`, `date`, 
    SUM(NOT ISNULL(`document`)) AS files, SUM(NOT ISNULL(`requirement`)) AS reqs, 
    SUM(`sum_paid`) AS paid
    FROM (
        (SELECT `tender`, `number` AS milestone, `name`, `date` FROM MILESTONE WHERE `tender`=?) AS MS 
        NATURAL LEFT JOIN 
        (SELECT `tender`, `milestone`, `document`, `requirement`, `sum_paid` FROM DOCUMENT) AS DOC
    )
    GROUP BY `tender`, `milestone`;";

    $conn = sqlConnect();
    sqlQueryContentParam(
        $sql,
        "s",
        [$tenderCode],
        [
            "number",
            "name",
            "date",
            "files/required",
            "paid out"
        ],
        "milestone",
        [
            "number"
        ], 
        true
    );
    sqlDisconnect();

    $buttons = $dom->getElementById("contentButtons");

    if (isUserAdmin()) {
        $addMS = $dom->createElement("a", "Add New Milestone");
        $addMS->setAttribute("class", "a_button");
        $addMS->setAttribute("href", "../" . findPage("new_milestone"));
        $buttons->appendChild($addMS);
    }
    $goBack = $dom->createElement("a", "Back to Tender");
    $goBack->setAttribute("class", "a_button");
    $goBack->setAttribute("href", "../" . findPage("tender"));
    $buttons->appendChild($goBack);

    domSetTitle(toDisplayText($tenderCode . " - " . PAGE));

    domPopFeedback();
}

echo $dom->saveHTML();
