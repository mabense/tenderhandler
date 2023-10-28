<?php

$dom = $GLOBALS["dom"];
if ($dom) {

    // Add toolbar
    $toolbarTag = $dom->getElementById("toolbar");
    domElementFillWithString($toolbarTag, domMakeToolbar([
        "log_out"
    ]));

    // Add content
    $contentTag = $dom->getElementById("content");
    domElementFillWithTemplate($contentTag, ELEM_DIR . "home.htm"); 
}
