<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "tender");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if(!auth(false, true, true)){
    header("Location: " . ROOT . "log_in");
    exit;
}

domHandleMissingPage();

domHandleAction();

domHandleTableRow();
$keys = getTableKeys();

$page = PAGE;
$placeholder = "...";
$tenderData = [
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

    domAppendTemplateTo("content", "./view.htm");

    if ($keys) {
        $conn = sqlConnect();

        $tenderCode = $keys["code"];
        $tenderData["code"] = $tenderCode;
        $page = $tenderCode;
        $tenderStmt = sqlPrepareBindExecute(
            "SELECT * FROM TENDER WHERE `code`=?",
            "s",
            [$tenderCode],
            __FUNCTION__
        );
        $result = $tenderStmt->get_result();
        $tender = $result->fetch_assoc();
        if ($tender) {
            $tenderData["begins"] = $tender["begins"];
            $tenderData["ends"] = $tender["ends"];
            $tenderData["sum_asked"] = $tender["sum_asked"];
            $tenderData["sum_granted"] = $tender["sum_granted"];
            $tenderData["manager_email"] = $tender["manager"];

            $managerID = $tender["manager"];
            $managerStmt = sqlPrepareBindExecute(
                "SELECT `name` FROM USER WHERE `email`=?",
                "s",
                [$managerID],
                __FUNCTION__
            );
            $result = $managerStmt->get_result();
            $manager = $result->fetch_assoc();
            if($manager) {
                $tenderData["manager_name"] = $manager["name"];
            }

            $topic_id = $tender["topic_id"];
            $topicStmt = sqlPrepareBindExecute(
                "SELECT `title`, `purpose` FROM TOPIC WHERE `id`=?",
                "i",
                [$topic_id],
                __FUNCTION__
            );
            $result = $topicStmt->get_result();
            $topic = $result->fetch_assoc();
            if($topic) {
                $tenderData["topic_title"] = $topic["title"];
                $tenderData["topic_purpose"] = $topic["purpose"];
                $page = $topic["title"] . " - " . $page;
            }
        } else {
            pushFeedbackToLog("Tender disappeared!?", true);
        }

        domContentTableFrom($tenderData);

        sqlDisconnect();
    } else {
        pushFeedbackToLog("Tender isn't selected.", true);
    }

    domSetTitle(toDisplayText($page));

    domPopFeedback();
}

echo $dom->saveHTML();
