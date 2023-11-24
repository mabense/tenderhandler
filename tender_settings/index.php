<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "tender_settings");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if(!auth(false, false, true)){
    redirectTo(ROOT, "home");
}

handleMissingPage();

handleAction();

if (newDOMDocument(BASE_TEMPLATE)) {

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", "./view.htm");

    sqlConnect();

    // Fill Manager Select
    $manSelect = $dom->getElementById("manager");
    $stmt = sqlPrepareExecute(
        "SELECT `email`, `name` FROM user WHERE `is_admin`=FALSE", 
        __FUNCTION__
    );
    $manResult = $stmt->get_result();
    if(!$manResult) {
        pushFeedbackToLog("No managers found.");
    }
    else {
        while($manager = $manResult->fetch_assoc()){
            $m_email = $manager["email"];
            $m_name = $manager["name"];    
            $opt = $dom->createElement("option");
            $opt->setAttribute("value", $m_email);
            $opt->textContent = $m_name . "(" . $m_email . ")";
            $manSelect->appendChild($opt);
        }
    }

    sqlDisconnect();
    
    domSetTitle(toDisplayText(PAGE));

    domPopFeedback();
}

global $dom;
echo $dom->saveHTML();
