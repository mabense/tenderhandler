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

if (newDOMDocument(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");
    // domAddStyle(STYLE_DIR . "query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    $sql = "SELECT `tender`, `milestone` AS number, `name`, `date`, 
    SUM(NOT ISNULL(`document`)) AS files, SUM(NOT ISNULL(`requirement`)) AS reqs, 
    SUM(`sum_paid`) AS paid
    FROM (
        (SELECT `tender`, `number` AS milestone, `name`, `date` FROM milestone WHERE `tender`=?) AS MS 
        NATURAL LEFT JOIN 
        (SELECT `tender`, `milestone`, `document`, `requirement`, `sum_paid` FROM document) AS DOC
    )
    GROUP BY `tender`, `milestone`;";

    sqlConnect();
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

global $dom;
echo $dom->saveHTML();
