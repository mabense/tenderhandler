<?php
// DON'T require: dom.php

function haveSession()
{
    if (!session_id()) {
        session_start();
    }
}


// function auth() {

// }


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


function getTableKeys()
{
    if (!isset($_SESSION["tableKeys"])) {
        return false;
    }
    return $_SESSION["tableKeys"];
}


function setTableKeys($keys)
{
    $_SESSION["tableKeys"] = $keys;
}

function resetTableKeys()
{
    setTableKeys(false);
    unset($_SESSION["tableKeys"]);
    return !isset($_SESSION["tableKeys"]);
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
