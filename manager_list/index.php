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

    sqlConnect();
    $tUser = USER_TABLE;
    $tTender = TENDER_TABLE;

    // $sql = "SELECT `email`, `name`, `now_active`, `last_active` FROM user WHERE `is_admin`=FALSE";
    $sql = "SELECT $tUser.`name` AS name, $tTender.`manager` AS email, COUNT($tTender.`code`) AS projects " .
        "FROM $tTender LEFT JOIN $tUser ON $tTender.`manager`=$tUser.`email` " .
        "GROUP BY email";
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
