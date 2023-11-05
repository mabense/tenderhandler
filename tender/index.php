<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "tender");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

domHandleMissingPage();

domHandleAction();

domHandleTableRow();
$keys = getTableKeys();
resetTableKeys();

$page = PAGE;

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", "./view.htm");

    if ($keys) {

        $tenderCode = $keys["code"];
        $fields = "`topic_id`";

        $conn = sqlConnect();

        $stmt = sqlPrepareBindExecute(
            "SELECT $fields FROM TENDER WHERE `code`=?",
            "s",
            [$tenderCode],
            __FUNCTION__
        );
        
        $result = $stmt->get_result();
        if ($tender = $result->fetch_assoc()) {
            $page = $tender["topic_id"];
        }

        sqlDisconnect();
    } else {
        pushFeedbackToLog("Tender isn't selected.", true);
    }

    domSetTitle(toDisplayText($page));

    domPopFeedback();
}

echo $dom->saveHTML();
