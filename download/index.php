<?php
define("ROOT", ".." . DIRECTORY_SEPARATOR);
define("PAGE", "download");

require_once(ROOT . "const.php");
require_once(ROOT . "requirements.php");

require_once(LIB_DIR . "sql.php");

haveSession();
$page = PAGE;

if (!auth(false, true, true)) {
    redirectTo(ROOT, "home");
}

$tender = getTender();
$ms = getMilestone();
$doc = getDocument();

$success = false;


if (
    isset($tender) &&
    isset($ms) &&
    isset($doc)
) {
    sqlConnect();

    $sql = "SELECT `document`, `file_name` FROM document WHERE `tender`=? AND `milestone`=? AND `requirement`=?";
    $stmt = sqlPrepareBindExecute(
        $sql,
        "sis",
        [$tender, $ms, $doc],
        __FUNCTION__
    );

    $result = $stmt->get_result();

    sqlDisconnect();

    if ($row = $result->fetch_assoc()) {
        $fileCont = $row['document'];
        $fileName = $row['file_name'];

        // header('Content-Description: File Transfer');
        // header("Content-Type: application/octet-stream;");
        // header("Content-Disposition: attachment; filename=$fileName");
        // header('Cache-Control: must-revalidate');
        // header('Pragma: public');
        // header('Expires: 0');
        // readfile($file);

        if (!$fileCont) {
            pushFeedbackToLog("Nothing to download.", true);
            redirectTo(ROOT, "document");
        } else {
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=$fileName");
            echo $fileCont;
        }
    }
}
