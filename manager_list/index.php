<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "manager_list");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if (!auth(false, false, true)) {
    redirectTo(ROOT, "home");
}

domHandleMissingPage();

domHandleAction();

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");
    // domAddStyle(STYLE_DIR . "query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    // $sql = "SELECT `email`, `name`, `now_active`, `last_active` FROM USER WHERE `is_admin`=FALSE";
    $sql = "SELECT USER.`name` AS name, TENDER.`manager` AS email, COUNT(TENDER.`code`) AS projects " .
        "FROM TENDER LEFT JOIN USER ON TENDER.`manager`=USER.`email` " .
        "GROUP BY email";

    $conn = sqlConnect();
    sqlQueryContent(
        $sql,
        [
            "name",
            "email",
            "projects"
        ]
    );
    sqlDisconnect();

    $buttons = $dom->getElementById("contentButtons");
    $buttons->parentNode->removeChild($buttons);

    domSetTitle(toDisplayText(PAGE));

    domPopFeedback();
}

echo $dom->saveHTML();
