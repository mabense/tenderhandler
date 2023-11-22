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
$msCode = getMilestone();

$page = PAGE;
$placeholder = "...";
$milestoneData = [
    "tender" => $placeholder,
    "number" => $placeholder,
    "name" => $placeholder,
    "date" => $placeholder,
    "description" => $placeholder,
    "progress" => $placeholder
];

if (newDOMDocument(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    if ($tenderCode && $msCode) {
        sqlConnect();

        $milestoneData["tender"] = $tenderCode;
        $milestoneData["number"] = $msCode;
        $page = $tenderCode . ": " . $msCode . ". Milestone";
        $msStmt = sqlPrepareBindExecute(
            "SELECT * FROM milestone WHERE `tender`=? AND `number`=?",
            "si",
            [$tenderCode, $msCode],
            __FUNCTION__
        );
        $result = $msStmt->get_result();
        $milestone = $result->fetch_assoc();
        if ($milestone) {
            $milestoneData["name"] = $milestone["name"];
            $milestoneData["date"] = $milestone["date"];
            $milestoneData["description"] = $milestone["description"];
            $milestoneData["progress"] = $milestone["progress"] . "%";

            $tenderStmt = sqlPrepareBindExecute(
                "SELECT `topic_id` FROM tender WHERE `code`=?",
                "s",
                [$tenderCode],
                __FUNCTION__
            );
            $result = $tenderStmt->get_result();
            $tender = $result->fetch_assoc();
            if ($tender) {
                $topic_id = $tender["topic_id"];
                $topicStmt = sqlPrepareBindExecute(
                    "SELECT `title` FROM topic WHERE `id`=?",
                    "i",
                    [$topic_id],
                    __FUNCTION__
                );
                $result = $topicStmt->get_result();
                $topic = $result->fetch_assoc();
                if ($topic) {
                    $milestoneData["topic_title"] = $topic["title"];
                    $page = $topic["title"] . " - " . $page;
                }
            }
        } else {
            pushFeedbackToLog("Milestone disappeared!?", true);
        }

        domContentTableFrom($milestoneData);

        sqlDisconnect();

        $buttons = $dom->getElementById("contentButtons");

        if (isUserAdmin()) {
            $delMS = $dom->createElement("a", "Delete Milestone");
            $delMS->setAttribute("class", "a_button");
            $delMS->setAttribute("href", "../" . findPage("delete_milestone"));
            $buttons->appendChild($delMS);
        }

        $listDoc = $dom->createElement("a", "List Documents");
        $listDoc->setAttribute("class", "a_button");
        $listDoc->setAttribute("href", "../" . findPage("document_list"));
        $buttons->appendChild($listDoc);

        $listMS = $dom->createElement("a", "Other Milestones");
        $listMS->setAttribute("class", "a_button");
        $listMS->setAttribute("href", "../" . findPage("milestone_list"));
        $buttons->appendChild($listMS);
        
    } else {
        pushFeedbackToLog("Milestone isn't selected.", true);
    }

    setMilestoneTitle($page);
    domSetTitle(toDisplayText($page));

    domPopFeedback();
}

global $dom;
echo $dom->saveHTML();
