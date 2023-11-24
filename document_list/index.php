<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "document_list");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");
require_once(LIB_DIR . "sql_dom.php");

haveSession();

$tenderCode = getTender();
$msCode = getMilestone();

if(!auth(false, true, true)){
    redirectTo(ROOT, "log_in");
}

handleMissingPage();

handleAction();

if (newDOMDocument(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");
    // domAddStyle(STYLE_DIR . "query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    sqlConnect();
    $tDocument = DOCUMENT_TABLE;
    $fields = "`tender`, `milestone`, `requirement`, `participant`, `fulfilled`, `sum_paid`, `deadline_submit`, `deadline_verify`";
    // $sql = "";
    sqlQueryContentParam(
        "SELECT $fields FROM $tDocument WHERE `tender`=? AND  `milestone`=?",
        "si",
        [$tenderCode, $msCode],
        [
            "tender", 
            "milestone", 
            "about", 
            "by", 
            "fulfilled", 
            "sum_paid", 
            "submit till", 
            "verify till"
        ], 
        "document", 
        [
            "requirement"
        ]
    );
    sqlDisconnect();

    $buttons = $dom->getElementById("contentButtons");

    if(isUserAdmin()) {
        $addDoc = $dom->createElement("a", "Add New Document");
        $addDoc->setAttribute("class", "a_button");
        $addDoc->setAttribute("href", "../" . findPage("new_document"));
        $buttons->appendChild($addDoc);
    }
    $goBack = $dom->createElement("a", "Back to Milestone");
    $goBack->setAttribute("class", "a_button");
    $goBack->setAttribute("href", "../" . findPage("milestone"));
    $buttons->appendChild($goBack);

    pushFeedbackToLog("This demo site stores only the " . MAX_FILE_COUNT . " latest uploads!");

    domSetTitle(toDisplayText(getMilestoneTitle() . " - Documents"));

    domPopFeedback();
}

global $dom;
echo $dom->saveHTML();
