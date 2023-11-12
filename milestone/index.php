<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "milestone");

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

$page = PAGE;
$placeholder = "...";
// $tender = [
$milestoneData = [
    "code" => $placeholder,
    "begins" => $placeholder,
    "ends" => $placeholder,
    "sum_asked" => $placeholder,
    "sum_granted" => $placeholder,
    "manager_name" => $placeholder,
    "manager_email" => $placeholder,
    "topic_title" => $placeholder,
    "topic_purpose" => $placeholder
];

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    if ($tenderCode) {
        $conn = sqlConnect();

        $milestoneData["code"] = $tenderCode;
        $page = $tenderCode;
        $tenderStmt = sqlPrepareBindExecute(
            "SELECT * FROM TENDER WHERE `code`=?",
            "s",
            [$tenderCode],
            __FUNCTION__
        );
        $result = $tenderStmt->get_result();
        $milestone = $result->fetch_assoc();
        if ($milestone) {
            $milestoneData["begins"] = $milestone["begins"];
            $milestoneData["ends"] = $milestone["ends"];
            $milestoneData["sum_asked"] = $milestone["sum_asked"];
            $milestoneData["sum_granted"] = $milestone["sum_granted"];
            $milestoneData["manager_email"] = $milestone["manager"];

            $managerID = $milestone["manager"];
            $managerStmt = sqlPrepareBindExecute(
                "SELECT `name` FROM USER WHERE `email`=?",
                "s",
                [$managerID],
                __FUNCTION__
            );
            $result = $managerStmt->get_result();
            $manager = $result->fetch_assoc();
            if ($manager) {
                $milestoneData["manager_name"] = $manager["name"];
            }

            $topic_id = $milestone["topic_id"];
            $topicStmt = sqlPrepareBindExecute(
                "SELECT `title`, `purpose` FROM TOPIC WHERE `id`=?",
                "i",
                [$topic_id],
                __FUNCTION__
            );
            $result = $topicStmt->get_result();
            $topic = $result->fetch_assoc();
            if ($topic) {
                $milestoneData["topic_title"] = $topic["title"];
                $milestoneData["topic_purpose"] = $topic["purpose"];
                $page = $topic["title"] . " - " . $page;
            }
        } else {
            pushFeedbackToLog("Milestone disappeared!?", true);
        }

        domContentTableFrom($milestoneData);

        sqlDisconnect();

        if (isUserAdmin()) {
            $buttons = $dom->getElementById("contentButtons");

            $setMan = $dom->createElement("a", "Set manager");
            $setMan->setAttribute("class", "a_button");
            $setMan->setAttribute("href", "../" . findPage("tender_settings"));
            $buttons->appendChild($setMan);

            $listMS = $dom->createElement("a", "List documents");
            $listMS->setAttribute("class", "a_button");
            $listMS->setAttribute("href", "../" . findPage("document_list"));
            $buttons->appendChild($listMS);
        } else {
            $buttons = $dom->getElementById("contentButtons");
            $buttons->parentNode->removeChild($buttons);
        }
    } else {
        pushFeedbackToLog("Milestone isn't selected.", true);
    }

    domSetTitle(toDisplayText($page));

    domPopFeedback();
}

echo $dom->saveHTML();
