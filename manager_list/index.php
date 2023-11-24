<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "manager_list");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");
require_once(LIB_DIR . "sql_dom.php");

haveSession();

if (!auth(false, false, true)) {
    redirectTo(ROOT, "home");
}

handleMissingPage();

handleAction();

if (newDOMDocument(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");
    // domAddStyle(STYLE_DIR . "query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    // $sql = "SELECT `email`, `name`, `now_active`, `last_active` FROM user WHERE `is_admin`=FALSE";
    $sql = "SELECT user.`name` AS name, tender.`manager` AS email, COUNT(tender.`code`) AS projects " .
        "FROM tender LEFT JOIN user ON tender.`manager`=user.`email` " .
        "GROUP BY email";

    sqlConnect();
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

global $dom;
echo $dom->saveHTML();
