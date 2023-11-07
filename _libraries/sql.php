<?php


// Tender


function sqlNewTopic($title, $purpose)
{
    $fields = "(`title`, `purpose`)";
    $sql = "INSERT INTO TOPIC $fields VALUES (?, ?)";
    $success = sqlPrepareBindExecute(
        $sql,
        "ss",
        [$title, $purpose],
        __FUNCTION__
    );
    return $success;
}


function sqlNewTender($code, $begin, $end, $asked, $granted, $topic, $manager)
{
    $fields = "(`code`, `begins`, `ends`, `sum_asked`, `sum_granted`, `topic_id`, `manager`)";
    $sql = "INSERT INTO TENDER $fields VALUES (?, ?, ?, ?, ?, ?, ?)";
    $success = sqlPrepareBindExecute(
        $sql,
        "sssiiis",
        [$code, $begin, $end, $asked, $granted, $topic, $manager],
        __FUNCTION__
    );
    return $success;
}


function sqlNewMilestone($tender, $name, $date, $description)
{
    $number = 1;
    $numStmt = sqlPrepareBindExecute(
        "SELECT MAX(`number`) AS max FROM MILESTONE WHERE `tender`=?", 
        "s", 
        [$tender], 
        __FUNCTION__
    );
    $numResult = $numStmt->get_result();
    if($numRow = $numResult->fetch_assoc()) {
        $number = $numRow["max"] + 1;
    }
    $fields = "(`tender`, `number`, `name`, `date`, `description`)";
    $sql = "INSERT INTO MILESTONE $fields VALUES (?, ?, ?, ?, ?)";
    $success = sqlPrepareBindExecute(
        $sql,
        "sisss",
        [$tender, $number, $name, $date, $description],
        __FUNCTION__
    );
    return $success;
}


function sqlNewDocument($_)
{
    $fields = "(`title`, `purpose`)";
    $sql = "INSERT INTO DOCUMENT $fields VALUES (?, ?)";
    $success = sqlPrepareBindExecute(
        $sql,
        "s",
        [$_],
        __FUNCTION__
    );
    return $success;
}


// Query page


function sqlQueryContentParam($sqlQuery, $sqlTypes, $sqlParams, $tabelColumns = [], $onClickRoute = "", $keyAttributes = [])
{
    $dom = new DOMDocument();
    global $dom;
    $contentTag = $dom->getElementById("content");
    /* */
    $stmt = sqlPrepareBindExecute(
        $sqlQuery, 
        $sqlTypes, 
        $sqlParams, 
        __FUNCTION__
    );
    $result = $stmt->get_result();
    if (!$result) {
        return $contentTag;
    }

    $tableTag = sqlQueryTable($result, $tabelColumns, $onClickRoute, $keyAttributes);
    $contentTag->appendChild($tableTag);

    // return $contentTag;
}


function sqlQueryContent($sql, $tabelColumns = [], $onClickRoute = "", $keyAttributes = [])
{
    $dom = new DOMDocument();
    global $dom;
    $contentTag = $dom->getElementById("content");
    /* */
    $stmt = sqlPrepareExecute(
        $sql,
        __FUNCTION__
    );
    $result = $stmt->get_result();
    if (!$result) {
        return $contentTag;
    }

    $tableTag = sqlQueryTable($result, $tabelColumns, $onClickRoute, $keyAttributes);
    $contentTag->appendChild($tableTag);

    // return $contentTag;
}


function sqlQueryTable($sqlResult, $tabelColumns = [], $onClickRoute = "", $keyAttributes = [])
{
    $dom = new DOMDocument();
    global $dom;
    $tableTag = $dom->getElementById("contentTable");

    $tableHead = sqlQueryTableHead($tabelColumns);
    $tableTag->appendChild($tableHead);

    $tableBody = $dom->createElement("tbody");

    if ($sqlResult->num_rows == 0) {
        $tr = sqlQueryTableEmptyRow(count($tabelColumns));
        $tableBody->appendChild($tr);
    } else {
        $tableKeys = [];
        $i = 0;
        while ($row = $sqlResult->fetch_assoc()) {
            $tr = sqlQueryTableRow($row, $onClickRoute, $i);

            $rowKey = [];
            foreach ($keyAttributes as $key) {
                $rowKey[$key] = $row[$key];
                // array_push($rowKey, $row[$key]);
                // $trRoute .= $row[$key];
            }
            $tableKeys[$i] = $rowKey;

            $tableBody->appendChild($tr);
            $i++;
        }
        setTableAllKeys($tableKeys);
    }
    $tableTag->appendChild($tableBody);
    return $tableTag;
}

function sqlQueryTableHead($tabelColumns)
{
    $dom = new DOMDocument();
    global $dom;
    $tableHead = $dom->createElement("thead");

    $thRow = $dom->createElement("tr");
    foreach ($tabelColumns as $header) {
        $th = $dom->createElement("th");
        $th->textContent = $header;
        $thRow->appendChild($th);
    }
    $tableHead->appendChild($thRow);

    return $tableHead;
}

function sqlQueryTableEmptyRow($columnCount)
{
    $dom = new DOMDocument();
    global $dom;
    $tr = $dom->createElement("tr");
    $tr->setAttribute(
        "class",
        "none_row"
    );
    for ($i = 0; $i < $columnCount; $i++) {
        $td = $dom->createElement("td");
        $nbsp = $dom->createElement("pre");
        $td->appendChild($nbsp);
        $tr->appendChild($td);
    }
    return $tr;
}

function sqlQueryTableRow($sqlResultRow, $onClickRoute, $rowIndex)
{
    $dom = new DOMDocument();
    global $dom;
    $trRoute = ($onClickRoute == "") 
    ? "./" 
    : "../" . findPage($onClickRoute) . "/?row=" . $rowIndex;

    $tr = $dom->createElement("tr");
    $tr->setAttribute(
        "class",
        ($rowIndex % 2 == 0) ? "even_row" : "odd_row"
    );

    $tr->setAttribute("onclick", "window.location='" . $trRoute . "';");

    foreach ($sqlResultRow as $attr) {
        $td = $dom->createElement("td");
        $td->textContent = $attr;
        $tr->appendChild($td);
    }
    return $tr;
}


// User


function passwordStrong($password)
{
    $isStrong = false;
    if (
        strlen($password) > 0
    ) {
        $isStrong = true;
    }
    return $isStrong;
}


function passwordCompare($one, $other)
{
    return $one == $other;
}


function sqlLogout($email)
{
    $changes = "`now_active`=FALSE, `last_active`=CURRENT_DATE";
    $setInactive = "UPDATE USER SET $changes WHERE `email`=?";
    $success = sqlPrepareBindExecute(
        $setInactive,
        "s",
        [$email],
        __FUNCTION__
    );
    if (!resetUser()) {
        pushFeedbackToLog("Session error. Please, delete cookies and refresh page.", true);
        $success = false;
    }
    return $success;
}


function sqlLogin($email, $password)
{
    $fields = "`email`, `password`, `name`, `is_admin`";
    $sql = "SELECT $fields FROM USER WHERE `email`=?";
    $stmt = sqlPrepareBindExecute(
        $sql,
        "s",
        [$email],
        __FUNCTION__
    );
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $uExists = ($user !== null);
    $pwdMatch = passwordCompare($password, $user["password"]);
    if (!$uExists || !$pwdMatch) {
        pushFeedbackToLog("Incorrect email address or password.", true);
        return false;
    }

    $changes = "`now_active`=TRUE, `last_active`=CURRENT_DATE";
    $setActive = "UPDATE USER SET $changes WHERE `email`=?";
    $stmt2 = sqlPrepareBindExecute(
        $setActive,
        "s",
        [$email],
        __FUNCTION__
    );

    return ($uExists && $pwdMatch && $stmt2) ? $user : false;
}


function sqlSignup($email, $password, $passwordAgain, $name, $isAdmin)
{
    if (!passwordStrong($password)) {
        pushFeedbackToLog("Password is too weak.", true);
        return false;
    }
    if (!passwordCompare($password, $passwordAgain)) {
        pushFeedbackToLog("The passwords don't match.", true);
        return false;
    }
    $fields = "(`email`, `password`, `name`, `is_admin`)";
    $sql = "INSERT INTO USER $fields VALUES (?, ?, ?, ?)";
    $stmt = sqlPrepareBindExecute(
        $sql,
        "sssi",
        [$email, $password, $name, $isAdmin],
        __FUNCTION__
    );
    return $stmt;
}


// Query


function sqlConnFound($__FUNCTION__)
{
    global $conn;
    if (!$conn) {
        pushFeedbackToLog($__FUNCTION__ . ": " . "Connection lost.", true);
        return false;
    }
    return true;
}


function sqlPrepareExecute($sql, $__FUNCTION__)
{
    global $conn;
    if (!sqlConnFound($__FUNCTION__)) {
        return false;
    }
    $stmt = $conn->prepare($sql);
    if (!$stmt->execute()) {
        pushFeedbackToLog($__FUNCTION__ . ": " . $stmt->error, true);
        return false;
    }
    return $stmt;
}


function sqlPrepareBindExecute($sql, $types, $params, $__FUNCTION__)
{
    global $conn;
    if (!sqlConnFound($__FUNCTION__)) {
        return false;
    }
    $stmt = $conn->prepare($sql);
    // array_push($params, "HIBA");
    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        pushFeedbackToLog($__FUNCTION__ . ": " . $stmt->error, true);
        return false;
    }
    return $stmt;
}


// Connection


function sqlConnect()
{
    $conn = new mysqli("127.0.0.1", "root", "", "tenderdb") or die("failed to establish sql connection");
    $conn->query("SET NAMES UTF-8");
    $conn->query("SET character_set_results=utf-8");
    $conn->set_charset("utf-8");
    return $conn;
}


function sqlDisconnect()
{
    global $conn;
    $conn->close();
}


// Debug


function _sqlRowDump($sqlAssoc)
{
    $str = "";
    foreach ($sqlAssoc as $attribute) {
        $str .= $attribute . " . . . . . ";
    }
    return $str;
}


function _sqlDump($sqlResult, $rowSeparator)
{
    $str = "";
    while ($result = $sqlResult->fetch_assoc()) {
        $str .= _sqlRowDump($result) . $rowSeparator;
    }
    return $str;
}


function _sqlTest()
{
    global $conn;
    /* */
    $sql = "SELECT * FROM USER";
    /*/
    $sql = "SELECT * FROM USER WHERE `name`='Kis Pista'";
    /* */
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
