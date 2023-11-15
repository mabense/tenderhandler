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
    $fileName = $_FILES['docfile']['name'];
    // pushFeedbackToLog($fileSize);
    $isTooBig = $fileSize > 1048576;
    // 64 * 1024 = 65 536
    // 1024 * 1024 = 1 048 576

    if ($isTooBig) {
        pushFeedbackToLog(
            ($fileSize > 1024
                ? ($fileSize > 1048576
                    ? number_format($fileSize / 1048576, 2) . " MB"
                    : number_format($fileSize / 1024, 2) . " KB"
                )
                : number_format($fileSize, 2) . " bytes"
            ) . " is too big.",
            true
        );
    } else {
        // $conn = sqlConnect();
        $GLOBALS["conn"] = sqlConnect();
        global $conn;

        $sql = "UPDATE DOCUMENT SET `document`=?, `file_name`=? WHERE `tender`=? AND `milestone`=? AND `requirement`=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('bssis', $file, $fileName, $tender, $ms, $doc);
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
