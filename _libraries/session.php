<?php
// DON'T require: dom.php

function haveSession()
{
    if (!session_id()) {
        session_start();
    }
}

function fromSESSION($nameInSESSION) {
	if(isset($_SESSION[$nameInSESSION])) {
		return $_SESSION[$nameInSESSION];
	}
	return null;
}


function auth($acceptGuest, $acceptManager, $acceptAdmin) {
    $isGuest = !getUserEmail();
    $isAdmin = isUserAdmin();
    $isManager = !$isGuest && !$isAdmin;
    if(!$acceptGuest && $isGuest) {
        return false;
    }
    if(!$acceptManager && $isManager) {
        return false;
    }
    if(!$acceptAdmin && $isAdmin) {
        return false;
    }
    return true;
}


function getUserEmail()
{
    return fromSESSION("uEmail");
}

function getUserName()
{
    return fromSESSION("uName");
}

function isUserAdmin()
{
    return fromSESSION("uAdmin");
}

function setUser($userEmail, $userName, $isAdmin)
{
    $_SESSION["uEmail"] = $userEmail;
    $_SESSION["uName"] = $userName;
    $_SESSION["uAdmin"] = $isAdmin;
}

function resetUser()
{
    setUser("", "", false);
    unset($_SESSION["uEmail"]);
    unset($_SESSION["uName"]);
    unset($_SESSION["uAdmin"]);
    resetTender();
    resetMilestone();
    resetMilestoneTitle();
    resetDocument();
    resetTableAllKeys();
    return !isset($_SESSION["uEmail"]);
}


function getTableAllKeys()
{
    return fromSESSION("tableAllKeys");
}


function setTableAllKeys($keys)
{
    $_SESSION["tableAllKeys"] = $keys;
}

function resetTableAllKeys()
{
    setTableAllKeys(false);
    unset($_SESSION["tableAllKeys"]);
    return !isset($_SESSION["tableAllKeys"]);
}


function getTender()
{
    return fromSESSION("tenderCode");
}


function setTender($code)
{
    $_SESSION["tenderCode"] = $code;
}

function resetTender()
{
    setTender(false);
    unset($_SESSION["tenderCode"]);
    return !isset($_SESSION["tenderCode"]);
}


function getMilestone()
{
    return fromSESSION("milestone");
}


function setMilestone($number)
{
    $_SESSION["milestone"] = $number;
}

function resetMilestone()
{
    setMilestone(false);
    unset($_SESSION["milestone"]);
    return !isset($_SESSION["milestone"]);
}


function getMilestoneTitle()
{
    return fromSESSION("ms_title");
}


function setMilestoneTitle($str)
{
    $_SESSION["ms_title"] = $str;
}

function resetMilestoneTitle()
{
    setMilestone(false);
    unset($_SESSION["ms_title"]);
    return !isset($_SESSION["ms_title"]);
}


function getDocument()
{
    return fromSESSION("document");
}


function setDocument($requirement)
{
    $_SESSION["document"] = $requirement;
}

function resetDocument()
{
    setDocument(false);
    unset($_SESSION["document"]);
    return !isset($_SESSION["document"]);
}


// function getPage()
// {
//     return fromSESSION("page");
// }


// function setPage($page)
// {
//     $_SESSION["page"] = $page;
//     return isset($_SESSION["page"]);
// }
