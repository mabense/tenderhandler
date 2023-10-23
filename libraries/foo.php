<?php

// SESSION


function haveSession($defaultPage){
	if (!session_id()) {
		return successBool(session_start());
	}
	if (!isset($_SESSION["p"])) {
		return successBool($_SESSION["p"] = $defaultPage);
		return successBool($_SESSION["p0"] = $defaultPage);
	}
}


function getPage(){
	return $_SESSION["p"];
}


function setPage($defaultPage){
	return successBool($_SESSION["p"] = $defaultPage);
}


function resetPage(){
	return successBool($_SESSION["p"] = $_SESSION["p0"]);
}



?>