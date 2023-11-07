<?php
if(!defined("ROOT")) {
    echo "Error: root directory not found!";
    exit;
}
if(!defined("PAGE")) {
    echo "Error: page directory not found!";
    exit;
}

// Directory paths

define("LIB_DIR", ROOT . "_libraries" . DIRECTORY_SEPARATOR);
define("TEMPLATE_DIR", ROOT . "_templates" . DIRECTORY_SEPARATOR);
define("STYLE_DIR", ROOT . "_styles" . DIRECTORY_SEPARATOR);

// File paths

define("BASE_TEMPLATE", TEMPLATE_DIR . "base.htm");

// Pages

define("DEFAULT_PAGE", "home");
define("ALT_ROUTES", [
    
    "new_document" => "document_new", 

    "new_milestone" => "milestone_new", 

    "new_tender" => "tender_new", 
    "set_manager" => "tender_edit", 

    "manager_list" => "user_manager_list", 
    "schedule" => "user_schedule", 
    "home" => "user_home", 
    "log_out" => "user_log_out", 

    "sign_up" => "guest_sign_up", 
    "jobs" => "guest_jobs", 
    "log_in" => "guest_log_in"
]);