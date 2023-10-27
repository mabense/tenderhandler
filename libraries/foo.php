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

function getUserEmail()
{
	return $_SESSION["uEmail"];
}

function getUserName()
{
	return $_SESSION["uName"];
}

function isUserAdmin()
{
	return $_SESSION["uAdmin"];
}

function setUser($userEmail, $userName, $isAdmin)
{
	$_SESSION["uEmail"] = $userEmail;
	$_SESSION["uName"] = $userName;
	$_SESSION["uAdmin"] = $isAdmin;
}

function getPage()
{
	return $_SESSION["page"];
}

function setPage($page)
{
	$_SESSION["page"] = $page;
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

function domMakeToolbar($pages)
{
	$str = '';
	if (is_array($pages)) {
		foreach ($pages as $page) {
			$route = './routes/' . $page . '.php';
			if (file_exists($route)) {
				$str .= '<a href="' . $route . '">';
			} else {
				$str .= '<a href="./routes/not_found.php?r=' . $page . '">';
			}
			$str .= pageTitle($page) . '</a>';
		}
	}
	return $str;
}

function domElementFillWithString($element, $string)
{
	$frag = $element->ownerDocument->createDocumentFragment();
	// $frag = new DOMDocumentFragment();
	$frag->appendXML($string);
	while ($element->hasChildNodes()) {
		$element->removeChild($element->firstChild);
	}
	$element->appendChild($frag);
	return $frag;
}

function domElementFillWithTemplate($element, $template)
{
	// $element->textContent = file_get_contents($template);
	$tmpNode = new DOMDocument();
	$tmpNode->loadHtmlFile($template);
	while ($element->hasChildNodes()) {
		$element->removeChild($element->firstChild);
	}
	$tmpContent = $element->ownerDocument->importNode($tmpNode->documentElement, true);
	$element->appendChild($tmpContent);
	return $tmpContent;
}
