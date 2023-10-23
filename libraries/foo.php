<?php

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


function getRank() {
	if (!isset($_SESSION["user"])) {
		return "guest";
	}
	else {
		$isAdmin = sqlIsAdmin($_SESSION["user"]);
		return $isAdmin ? "admin" : "manager";
	}
}


// SQL QUERIES

function sqlIsAdmin($user) {
	
}