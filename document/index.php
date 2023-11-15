<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "document");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if (!auth(false, true, true)) {
    redirectTo(ROOT, "log_in");
}

domHandleMissingPage();

domHandleAction();

domHandleTableRow();
$tenderCode = getTender();
$msCode = getMilestone();
$docReq = getDocument();

$page = PAGE;
$placeholder = "...";
$docData = [
    "tender" => $placeholder,
    "milestone" => $placeholder,
    "what's required" => $placeholder,
    "participant" => $placeholder,
    "fulfilled" => $placeholder,
    "sum_paid" => $placeholder,
    "deadline_submit" => $placeholder,
    "deadline_verify" => $placeholder
];

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    if ($tenderCode && $msCode && $docReq) {
        $conn = sqlConnect();

        $docData["tender"] = $tenderCode;
        $docData["milestone"] = $msCode;
        $docData["what's required"] = $docReq;
        $page = getMilestoneTitle() . " - " . $docReq;
        $fields = "`tender`, `milestone`, `requirement`, `participant`, `fulfilled`, `sum_paid`, `deadline_submit`, `deadline_verify`";
        $docStmt = sqlPrepareBindExecute(
            "SELECT $fields FROM DOCUMENT WHERE `tender`=? AND `milestone`=? AND `requirement`=?",
            "sis",
            [$tenderCode, $msCode, $docReq],
            __FUNCTION__
        );
        $result = $docStmt->get_result();
        $doc = $result->fetch_assoc();
        if ($doc) {
            $docData["participant"] = $doc["participant"];
            $docData["fulfilled"] = $doc["fulfilled"];
            $docData["sum_paid"] = $doc["sum_paid"];
            $docData["deadline_submit"] = $doc["deadline_submit"];
            $docData["deadline_verify"] = $doc["deadline_verify"];
        } else {
            pushFeedbackToLog("Document disappeared!?", true);
        }

        domContentTableFrom($docData);

        sqlDisconnect();

        $buttons = $dom->getElementById("contentButtons");

        $down = $dom->createElement("a", "Download Document");
        $down->setAttribute("class", "a_button");
        $down->setAttribute("href", "../" . findPage("download"));
        $buttons->appendChild($down);

        if (!isUserAdmin()) {
            $up = $dom->createElement("a", "Upload Document");
            $up->setAttribute("class", "a_button");
            $up->setAttribute("href", "../" . findPage("upload"));
            $buttons->appendChild($up);
        }

        $listDoc = $dom->createElement("a", "Other Documents");
        $listDoc->setAttribute("class", "a_button");
        $listDoc->setAttribute("href", "../" . findPage("document_list"));
        $buttons->appendChild($listDoc);
    } else {
        pushFeedbackToLog("Document isn't selected.", true);
    }

    domSetTitle(toDisplayText($page));

    domPopFeedback();
}

echo $dom->saveHTML();
