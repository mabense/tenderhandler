<?php
// DON'T require: dom.php

function haveSession()
{
    if (!session_id()) {
        session_start();
    }
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

function resetUser()
{
    setUser("", "", false);
    unset($_SESSION["uEmail"]);
    unset($_SESSION["uName"]);
    unset($_SESSION["uAdmin"]);
    resetTender();
    resetMilestone();
    resetDocument();
    resetTableAllKeys();
    return !isset($_SESSION["uEmail"]);
}


function getTableAllKeys()
{
    if (!isset($_SESSION["tableAllKeys"])) {
        return false;
    }
    return $_SESSION["tableAllKeys"];
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
    if (!isset($_SESSION["tenderCode"])) {
        return false;
    }
    return $_SESSION["tenderCode"];
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
    if (!isset($_SESSION["milestone"])) {
        return false;
    }
    return $_SESSION["milestone"];
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


function getDocument()
{
    if (!isset($_SESSION["document"])) {
        return false;
    }
    return $_SESSION["document"];
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
//     if (!isset($_SESSION["page"])) {
//         return false;
//     }
//     return $_SESSION["page"];
// }


// function setPage($page)
// {
//     $_SESSION["page"] = $page;
//     return isset($_SESSION["page"]);
// }
