<?php

global $dom;

// Add toolbar
$toolbarTag = $dom->getElementById("toolbar");
$toolbarTag->textContent = "TOOLBAR";

// Add content
$contentTag = $dom->getElementById("content");
$contentTag->textContent = "[ login ]";

// Add feedback (if feedback value is set)
$feedbackTag = $dom->getElementById("feedback");
$feedbackTag->textContent = "FEEDBACK";

?>