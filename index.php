<?php
require_once("./__prologue.php");

haveSession(DEFAULT_PAGE);
$page = getPage();
$pageTitle = pageTitle($page);
$pagePath = PAGE_DIR . $page . ".php";

$dom = new DOMDocument();

if ($dom->loadHTMLFile(BASE_TEMPLATE)) {

    // Set title

    $titleTag = $dom->getElementsByTagName("title")[0];
    $titleTag->textContent .= " - " . $pageTitle;

    // Fetch page

    require_once($pagePath);

    // Pop feedback

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
            $div->setAttribute("class", $classList . " " . "row" . " " . $class);
            $div->textContent = $message;
            $feedbackTag->appendChild($div);
        }
    }
}

echo $dom->saveHTML();
