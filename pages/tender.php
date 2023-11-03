<?php
require_once(LIB_DIR . "sql.php");

$dom = $GLOBALS["dom"];
if ($dom) {

    // Add toolbar
    $toolbarTag = $dom->getElementById("toolbar");
    domElementFillWithString($toolbarTag, domMakeToolbarLoggedIn());

    // Add content
    $contentTag = $dom->getElementById("content");
    // domElementFillWithTemplate($contentTag, ELEM_DIR . "home.htm"); 
    domElementFillWithString($contentTag, "getTenderCode()");
}

// if ($dom) {
//     haveSession(DEFAULT_PAGE);
//     $dom = new DOMDocument();
//     $tender = getTenderCode();
//     $p = $dom->createElement("p");
//     $p->textContent = $tender;
//     $contentTag = $dom->getElementById("content");
//     $contentTag->appendChild($p);
// }
