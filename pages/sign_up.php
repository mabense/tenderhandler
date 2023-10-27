<?php

$dom = $GLOBALS["dom"];
if ($dom) {

    // Add toolbar
    $toolbarTag = $dom->getElementById("toolbar");
    domElementFillWithString($toolbarTag, domMakeToolbar([
        "log_in",
        "jobs"
    ]));

    // Add content
    $contentTag = $dom->getElementById("content");
    domElementFillWithTemplate($contentTag, ELEM_DIR . "sign_up.htm");
}