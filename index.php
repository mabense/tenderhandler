<?php
require_once("./__prologue.php");

// Session setup

haveSession(DEFAULT_PAGE);
$page = getPage();
$pageTitle = pageTitle($page);
$pagePath = PAGE_DIR . $page . ".php";
// pushFeedbackToLog("Page=" . $page);

// DOM start

$dom = domStart();

// Fetch page

require_once($pagePath);

// DOM finish

$feedback = getFeedbackLog();
resetFeedbackLog();
if ($feedback !== false) {
    $feedbackTag = $dom->getElementById("feedback");
    foreach ($feedback as $line) {
        $message = $line[0];
        $isError = $line[1];
        $div = $dom->createElement("div");
        $classList = $div->getAttribute("class");
        $class = $isError ? "errorMsg" : "feedbackMsg";
        $div->setAttribute("class", $classList . " " . $class);
        $div->textContent = $message;
        $feedbackTag->appendChild($div);
    }
}
echo $dom->saveHTML();

// Session cleanup

// session_destroy();
