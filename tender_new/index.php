<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "tender_new");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();

if(!auth(false, false, true)){
    redirectTo(ROOT, "home");
}

domHandleMissingPage();

domHandleAction();

$dom = new DOMDocument();
if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    domMakeToolbarLoggedIn();

    domAppendTemplateTo("content", "./view.htm");

    $conn = sqlConnect();

    // Fill Topic Select
    $topicSelect = $dom->getElementById("topic");
    $stmt = sqlPrepareExecute(
        "SELECT `id`, `title` FROM TOPIC", 
        __FUNCTION__
    );
    $topicResult = $stmt->get_result();
    if(!$topicResult) {
        pushFeedbackToLog("No topics found.");
    }
    else {
        while($topic = $topicResult->fetch_assoc()){
            $t_id = $topic["id"];
            $t_title = $topic["title"];    
            $opt = $dom->createElement("option");
            $opt->setAttribute("value", $t_id);
            $opt->textContent = $t_title;
            $topicSelect->appendChild($opt);
        }
    }

    // Fill Manager Select
    $manSelect = $dom->getElementById("manager");
    $stmt = sqlPrepareExecute(
        "SELECT `email`, `name` FROM USER WHERE `is_admin`=FALSE", 
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

echo $dom->saveHTML();
