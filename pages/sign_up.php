<?php

$dom = $GLOBALS["dom"];
if ($dom) {

    // Add toolbar
    $toolbarTag = $dom->getElementById("toolbar");
    domSetInnerHTML($toolbarTag, domMakeToolbar([
        "log_in",
        "sign_up", 
        "car_keys"
    ]));

    // Add content
    $contentTag = $dom->getElementById("content");
    $contentTag->textContent = "signup works";
}
