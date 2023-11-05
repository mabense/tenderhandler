<?php
require_once(LIB_DIR . "foo.php");
require_once(LIB_DIR . "session.php");
require_once(LIB_DIR . "feedback_log.php");

function domHandleMissingPage()
{
    haveSession();
    $missingPage = $_GET["missing"];
    if (isset($missingPage)) {
        pushFeedbackToLog("\"" . $missingPage . "\" not found.", true);
        header("Location: " . ROOT . PAGE);
        exit;
    }
}

function domHandleAction()
{
    haveSession();
    $action = $_GET["action"];
    if (isset($action)) {
        $actionPath = "./" . $action . ".php";
        if (file_exists($actionPath)) {
            require_once($actionPath);
            pushFeedbackToLog("\"" . $action . "\" failed.", true);
            header("Location: " . ROOT . PAGE);
            exit;
        } else {
            pushFeedbackToLog("\"" . $action . "\" not found.", true);
            header("Location: " . ROOT . PAGE);
            exit;
        }
    }
}


function domHandleTableRow()
{
    haveSession();
    $rowIndex = $_GET["row"];
    if (isset($rowIndex)) {
        $allKeys = getTableAllKeys();
        $keys = $allKeys[$rowIndex];
        if (is_array($keys)) {
            setTableKeys($keys);
        }
        resetTableAllKeys();
        header("Location: " . ROOT . PAGE);
        exit;
    }
}


function domSetTitle($pageTitle)
{
    $dom = new DOMDocument();
    global $dom;
    $contentTitle = $dom->getElementById("contentTitle");
    $contentTitle->textContent = $pageTitle;
    $titleTag = ($dom->getElementsByTagName("title"))->item(0);
    $titleTag->textContent .= " - " . $pageTitle;
}


function domAddStyle($stylesheet)
{
    $dom = new DOMDocument();
    global $dom;
    $head = ($dom->getElementsByTagName("head"))->item(0);
    $cssLink = $dom->createElement("link");
    $cssLink->setAttribute("rel", "stylesheet");
    $cssLink->setAttribute("href", $stylesheet);
    $head->appendChild($cssLink);
}


function domMakeToolbar($pages)
{
    $dom = new DOMDocument();
    global $dom;
    if (is_array($pages)) {
        $toolbar = $dom->getElementById("toolbar");
        foreach ($pages as $page) {
            $route = "../" . findPage($page);
            $title = toDisplayText($page);
            $aTag = $dom->createElement("a");
            $aTag->setAttribute("href", $route);
            $aTag->textContent = $title;
            $toolbar->appendChild($aTag);
        }
    }
}


function domMakeToolbarLoggedIn()
{
    if (isUserAdmin()) {
        domMakeToolbar([
            "log_out",
            "schedule", // list months >> list milestones >> view milestone >> view document >> download
            "tender_list", // list tenders >> list milestones >> view milestone >> view document >> download
            "manager_list" // list managers >> view manager >> list milestones >> view milestone >> view document >> download
        ]);
    } else {
        domMakeToolbar([
            "log_out",
            "schedule", // list months >> list milestones >> view milestone >> view document >> upload/download
            "tender_list" // list tenders >> list milestones >> view milestone >> view document >> upload/download
        ]);
    }
}


function domContentTableFrom($assocArray)
{
    $dom = new DOMDocument();
    global $dom;
    $table = $dom->getElementById("contentTable");
    // $table = $dom->createElement("table");
    $table->setAttribute("id", "detailedTable");

    $isOddRow = true;
    foreach ($assocArray as $key => $val) {
        $tr = $dom->createElement("tr");
        $tr->setAttribute("class", $isOddRow ? "odd_row" : "even_row");
        $tdKey = $dom->createElement("td");
        $tdKey->textContent = toDisplayText($key);
        $tdVal = $dom->createElement("td");
        $tdVal->textContent = toDisplayText($val);
        $tr->appendChild($tdKey);
        $tr->appendChild($tdVal);
        $table->appendChild($tr);
        $isOddRow = !$isOddRow;
    }

    // $contentTag->appendChild($table);
}


function domAppendTemplateTo($elementID, $template, $clear = false)
{
    $dom = new DOMDocument();
    global $dom;
    $element = $dom->getElementById($elementID);
    $tmpNode = new DOMDocument();
    $tmpNode->loadHtmlFile($template);
    if ($clear) {
        while ($element->hasChildNodes()) {
            $element->removeChild($element->firstChild);
        }
    }
    $tmpContent = $element->ownerDocument->importNode($tmpNode->documentElement, true);
    $element->appendChild($tmpContent);
}


function domPopFeedback()
{
    $dom = new DOMDocument();
    global $dom;
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
