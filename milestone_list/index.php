<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "milestone_list");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

$tenderCode = getTender();

if(!auth(false, true, true)){
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

    $conn = sqlConnect();
    sqlQueryContentParam(
        "SELECT * FROM MILESTONE WHERE `tender`=?", 
        "s", 
        [$tenderCode], 
        [
            "tender", 
            "number", 
            "name", 
            "date", 
            "description", 
            "progress"
        ], 
        "milestone", 
        [
            "number"
        ]
    );
    sqlDisconnect();

    $buttons = $dom->getElementById("contentButtons");

    if(isUserAdmin()) {
        $addMS = $dom->createElement("a", "Add New Milestone");
        $addMS->setAttribute("class", "a_button");
        $addMS->setAttribute("href", "../" . findPage("new_milestone"));
        $buttons->appendChild($addMS);
    }
    $goBack = $dom->createElement("a", "Back to Tender");
    $goBack->setAttribute("class", "a_button");
    $goBack->setAttribute("href", "../" . findPage("tender"));
    $buttons->appendChild($goBack);

    domSetTitle(toDisplayText(PAGE));

    domPopFeedback();
}

echo $dom->saveHTML();
