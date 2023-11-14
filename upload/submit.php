<?php
require_once(LIB_DIR . "sql.php");

haveSession();
$user = false;
$page = PAGE;

$tender = getTender();
$ms = getMilestone();
$doc = getDocument();

$success = false;

if ((count($_FILES) > 0) && (is_uploaded_file($_FILES['docfile']['tmp_name']))) {

    $file = file_get_contents($_FILES['docfile']['tmp_name']);
    $fileSize = $_FILES['docfile']['size'];
    pushFeedbackToLog($fileSize);
    $isTooBig = $fileSize > 40000000; // 40 * 1024 * 1024 = 41 943 040

    if ($isTooBig) {
        pushFeedbackToLog($fileSize . " bytes is too big.", true);
    } else {
        // $conn = sqlConnect();
        $GLOBALS["conn"] = sqlConnect();
        global $conn;

        $sql = "UPDATE DOCUMENT SET `document`=? WHERE `tender`=? AND `milestone`=? AND `requirement`=?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('bsis', $file, $tender, $ms, $doc);
        $stmt->send_long_data(0, $file);
        $success = $stmt->execute();

        sqlDisconnect();
    }
} else {
    pushFeedbackToLog("No file was selected.", true);
}

if ($success != false) {
    pushFeedbackToLog("Document added successfully.");
} elseif (!isThereFeedback()) {
    pushFeedbackToLog("Failed to add document.", true);
}

$page = "document";
redirectTo(ROOT, $page);
