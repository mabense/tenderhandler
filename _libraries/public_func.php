<?php
// DON'T require: dom.php


function toDisplayText($page)
{
    return ucwords(str_replace("_", " ", $page));
}


function findPage($nextPage) {
	// TODO: GET params
	$route = ROOT . $nextPage . DIRECTORY_SEPARATOR;
	if (file_exists($route . "index.php")) {
		return $nextPage;
	}
	return PAGE . "?missing=" . $nextPage;
}


function redirectTo($root, $pageRoute) {
	header("Location: " . $root . findPage($pageRoute));
	exit;
}

function fromGET($nameInGET) {
	if(isset($_GET[$nameInGET])) {
		return $_GET[$nameInGET];
	}
	return null;
}

function fromPOST($nameInPOST) {
	if(isset($_POST[$nameInPOST])) {
		return $_POST[$nameInPOST];
	}
	return null;
}

function handleMissingPage()
{
    haveSession();
    $missingPage = fromGET("missing");
    if (isset($missingPage)) {
        pushFeedbackToLog("\"" . $missingPage . "\" not found.", true);

        redirectTo(ROOT, PAGE);
    }
}

function handleAction()
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


function handleTableRow()
{
    haveSession();
    $rowIndex = fromGET("row");
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