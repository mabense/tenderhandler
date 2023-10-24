<?php

// STRING

function pageTitle($page)
{
	return ucwords(str_replace("_", " ", $page));
}

// SESSION

function haveSession($defaultPage)
{
	if (!session_id()) {
		session_start();
	}
	if (!isset($_SESSION["page"])) {
		$_SESSION["page"] = $defaultPage;
	}
}

function getPage()
{
	return $_SESSION["page"];
}

function setPage($defaultPage)
{
	$_SESSION["page"] = $defaultPage;
}

function getFeedbackLog()
{
	if (isset($_SESSION["log"])) {
		return $_SESSION["log"];
	} else {
		return false;
	}
}

function pushFeedbackToLog($message, $isError = false)
{
	if (!isset($_SESSION["log"])) {
		$_SESSION["log"] = [];
	}
	array_push($_SESSION["log"], [$message, $isError]);
}

function resetFeedbackLog()
{
	if (isset($_SESSION["log"])) {
		$_SESSION["log"] = [];
		unset($_SESSION["log"]);
	}
}

// DOM

function domStart()
{
	$dom = new DOMDocument();
	if ($dom->loadHTMLFile(BASE_TEMPLATE)) {
		$titleTag = $dom->getElementsByTagName("title")[0];
		$titleTag->textContent .= " - " . $GLOBALS["pageTitle"];
		return $dom;
	} else {
		return false;
	}
}

function domMakeToolbar($pages)
{
	$str = '';
	if (is_array($pages)) {
		foreach ($pages as $page) {
			$route = './routes/' . $page . '.php';
			if(file_exists($route)){
				$str .= '<a href="' . $route . '">';
			}
			else {
				$str .= '<a href="./routes/not_found.php?r=' . $page . '">';
			}
			$str .= pageTitle($page) . '</a>';
		}
	}
	return $str;
}

function domSetInnerHTML($element, $html)
{
	$frag = $element->ownerDocument->createDocumentFragment();
	// $frag = new DOMDocumentFragment();
	$frag->appendXML($html);
	while ($element->hasChildNodes()) {
		$element->removeChild($element->firstChild);
	}
	$element->appendChild($frag);
	return $frag;
}
