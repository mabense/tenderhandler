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
    $sql = "SELECT MSDOC.`number`, MSDOC.`name`, MSDOC.`date`, SUM(MSDOC.`doc_up`) AS `doc_ups`, SUM(MSDOC.`doc_req`) AS `doc_reqs`
    FROM(
        SELECT `number`, `name`, `date`, NOT ISNULL(DOCUMENT.`document`) AS `doc_up`, NOT ISNULL(DOCUMENT.`requirement`) AS `doc_req` 
        FROM MILESTONE LEFT JOIN DOCUMENT 
        ON MILESTONE.`tender`=DOCUMENT.`tender` AND MILESTONE.`number`=DOCUMENT.`milestone` 
        WHERE MILESTONE.`tender`='ASDasd'
    ) AS MSDOC
    GROUP BY MSDOC.`number`;";

    $conn = sqlConnect();
    sqlQueryContentParam(
        $sql,
        "s",
        [$tenderCode],
        [
            "number",
            "name",
            "date",
            "uploaded",
            "required",
            "paid",
            "granted"
        ],
        "milestone",
        [
            "number"
        ]
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
