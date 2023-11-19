<?php
require_once(LIB_DIR . "foo.php");
require_once(LIB_DIR . "session.php");
require_once(LIB_DIR . "feedback_log.php");

function domHandleMissingPage()
{
    haveSession();
    $missingPage = fromGET("missing");
    if (isset($missingPage)) {
        pushFeedbackToLog("\"" . $missingPage . "\" not found.", true);

        redirectTo(ROOT, PAGE);
    }
}

function domHandleAction()
{
    haveSession();
    $action = fromGET("action");
    if (isset($action)) {
        $actionPath = "./" . $action . ".php";
        if (file_exists($actionPath)) {
            include_once($actionPath);
            pushFeedbackToLog("\"" . $action . "\" failed.", true);

            redirectTo(ROOT, PAGE);
        } else {
            pushFeedbackToLog("\"" . $action . "\" not found.", true);

            redirectTo(ROOT, PAGE);
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
        switch (PAGE) {
            case 'tender':
                setTender($keys["code"]);
                break;
            case 'milestone':
                setMilestone($keys["number"]);
                break;
            case 'document':
                setDocument($keys["requirement"]);
                break;
            default:
                break;
        }

        resetTableAllKeys();

        redirectTo(ROOT, PAGE);
    }
}


function newDOMDocument($baseTemplatePath)
{
    $GLOBALS["dom"] = new DOMDocument();
    global $dom;
    libxml_use_internal_errors(true);
    return $dom->loadHTMLFile($baseTemplatePath);
}


function domGetElementByTagName($name)
{
    global $dom;
    if ($list = $dom->getElementsByTagName($name)) {
        return $list->item(0);
    }
    return false;
}


function domSetTitle($pageTitle)
{
    global $dom;
    if ($contentTitle = $dom->getElementById("contentTitle")) {
        $contentTitle->textContent = $pageTitle;
    }
    if ($titleTag = domGetElementByTagName("title")) {
        $titleTag->textContent .= " - " . $pageTitle;
    }
}


function domAddStyle($stylesheet)
{
    global $dom;
    if ($head = domGetElementByTagName("head")) {
        $cssLink = $dom->createElement("link");
        $cssLink->setAttribute("rel", "stylesheet");
        $cssLink->setAttribute("href", $stylesheet);
        $head->appendChild($cssLink);
    }
}


function domMakeToolbar($pages)
{
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
