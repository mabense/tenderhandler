<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "schedule");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");
require_once(LIB_DIR . "sql_dom.php");

haveSession();

if (!auth(false, true, true)) {
    redirectTo(ROOT, "log_in");
}

handleMissingPage();

handleAction();

if (newDOMDocument(BASE_TEMPLATE)) {

    domAddStyle("../_styles/query_page.css");
    // domAddStyle(STYLE_DIR . "query_page.css");

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", TEMPLATE_DIR . "sql_result.htm");

    // $sql = "SELECT * FROM milestone";
    // $sql = "SELECT `date`, `number` FROM milestone";
    $sql = "SELECT YEAR(`date`) AS year, MONTH(`date`) AS month, COUNT(`number`) AS due 
    FROM milestone GROUP BY year, month";

    sqlConnect();
    sqlQueryContent(
        $sql,
        [
            "year",
            "month",
            "milestones due"
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
