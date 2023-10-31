<?php

$dom = $GLOBALS["dom"];
if ($dom) {

    // Add toolbar
    $toolbarTag = $dom->getElementById("toolbar");
    domElementFillWithString($toolbarTag, domMakeToolbarLoggedIn());

    // Add content
    $contentTag = $dom->getElementById("content");
    domElementFillWithTemplate($contentTag, ELEM_DIR . "new_tender.htm"); 
}
