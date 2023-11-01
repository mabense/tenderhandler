<?php


// Logged in


function sqlNewTender($code, $begin, $end, $asked, $granted, $topic, $manager)
{
    global $conn;
    if (!sqlConnFound(__FUNCTION__)) {
        return false;
    }
    $success = true;

    // $sql = "INSERT INTO TENDER (`code`, `begins`, `ends`, `sum_asked`, `sum_granted`, `manager`) VALUES ('proba_5','2020-02-02','2023-12-12',100,10,'q')";

    $fields = "(`code`, `begins`, `ends`, `sum_asked`, `sum_granted`, `manager`)";
    $sql = "INSERT INTO TENDER $fields VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiis", $code, $begin, $end, $asked, $granted, $manager);
    if (!$stmt->execute()) {
        pushFeedbackToLog("Sorry, the connection failed.", true);
        $success = false;
    }
    return $success;
}


function sqlQueryPage($title, $sql, $tabelColumns = [])
{
    global $dom;
    global $conn;

    $head = ($dom->getElementsByTagName("head"))->item(0);
    $cssLink = $dom->createElement("link");
    $cssLink->setAttribute("rel", "stylesheet");
    $cssLink->setAttribute("href", "styles/query_page.css");
    $head->appendChild($cssLink);

    // Add toolbar
    $toolbarTag = $dom->getElementById("toolbar");
    domElementFillWithString($toolbarTag, domMakeToolbarLoggedIn());

    // Add content
    $contentTag = $dom->getElementById("content");
    domElementFillWithTemplate($contentTag, ELEM_DIR . "sql_result.htm");

    if (!$conn) {
        pushFeedbackToLog('Connection missing in sqlQueryPage($title, $sql).', true);
        return $contentTag;
    }

    // Set title
    $titleTag = $dom->getElementById("contentTitle");
    $titleTag->textContent = $title;

    // Fill table with results
    $tableTag = $dom->getElementById("contentTable");
    $table = "";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        foreach ($tabelColumns as $header) {
            $table .= "<th>" . $header . "</th>";
        }
        $isOddRow = true;
        while ($row = $result->fetch_assoc()) {
            $table .= $isOddRow ? "<tr class='odd_row'>" : "<tr class='even_row'>";
            foreach ($row as $attr) {
                $table .= "<td>";
                $table .= $attr;
                $table .= "</td>";
            }
            $table .= "</tr>";
            $isOddRow = !$isOddRow;
        }
    }
    domElementFillWithString($tableTag, $table);

    return $contentTag;
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
    /* */
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
    /*/
    $changes = "`now_active`=FALSE, `last_active`=CURRENT_DATE";
    $setInactive = "UPDATE USER SET $changes WHERE `email`=?";
    $success = sqlPrepareBindExecute(
        $setInactive,
        "s", 
        [$email], 
        __FUNCTION__
    );
    if (!$success) {
        return false;
    }
    return true;
    /* */

    /* * /
    global $conn;
    if (!sqlConnFound(__FUNCTION__)) {
        return false;
    }

    $success = true;

    $changes = "`now_active`=FALSE, `last_active`=CURRENT_DATE WHERE `email`=?";
    $setActive = "UPDATE USER SET $changes";
    $stmt = $conn->prepare($setActive);
    $stmt->bind_param('s', $email);
    if (!$stmt->execute()) {
        pushFeedbackToLog("Sorry, the connection failed.", true);
        $success = false;
    }
    if (!resetUser()) {
        pushFeedbackToLog("Session error. Please, delete cookies and refresh page.", true);
        $success = false;
    }

    return $success;
    /* */
}


function sqlLogin($email, $password)
{
    /* */
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
    /*/
    global $conn;

    $fields = "`email`, `password`, `name`, `is_admin`";
    $sql = "SELECT $fields FROM USER WHERE `email`=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    if (!$stmt->execute()) {
        pushFeedbackToLog("Sorry, the connection failed.", true);
        return false;
    }

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $uExists = ($user !== null);

    $pwdMatch = passwordCompare($password, $user["password"]);

    $changes = "`now_active`=TRUE, `last_active`=CURRENT_DATE WHERE `email`=?";
    $setActive = "UPDATE USER SET $changes";
    $stmt2 = $conn->prepare($setActive);
    $stmt2->bind_param('s', $email);
    if (!$stmt2->execute()) {
        pushFeedbackToLog("Sorry, the connection failed.", true);
        return false;
    }

    if (!$uExists || !$pwdMatch) {
        pushFeedbackToLog("Incorrect email address or password.", true);
        return false;
    }

    return ($uExists && $pwdMatch) ? $user : false;
    /* */
}


function sqlSignup($email, $password, $passwordAgain, $name, $isAdmin)
{
    /* */
    if (!passwordStrong($password)) {
        pushFeedbackToLog("Password is too weak.", true);
        return false;
    }
    if (!passwordCompare($password, $passwordAgain)) {
        pushFeedbackToLog("The passwords don't match.", true);
        return false;
    }
    $fields = "(`email`, `password`, `name`, `is_admin`)";
    $adminBool = ($isAdmin ? "TRUE" : "FALSE");
    $sql = "INSERT INTO USER $fields VALUES (?, ?, ?, $adminBool)";
    $stmt = sqlPrepareBindExecute(
        $sql, 
        "sss", 
        [$email, $password, $name], 
        __FUNCTION__
    );
    return $stmt;
    /*/
    global $conn;
    $success = true;

    if (!passwordStrong($password)) {
        pushFeedbackToLog("Password is too weak.", true);
        $success = false;
    }
    if (!passwordCompare($password, $passwordAgain)) {
        pushFeedbackToLog("The passwords don't match.", true);
        $success = false;
    }
    if ($success) {
        $fields = "(`email`, `password`, `name`, `is_admin`)";
        $adminBool = ($isAdmin ? "TRUE" : "FALSE");
        $sql = "INSERT INTO USER $fields VALUES (?, ?, ?, $adminBool)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $password, $name);
        if (!$stmt->execute()) {
            pushFeedbackToLog("Sorry, the connection failed.", true);
            $success = false;
        }
    }
    return $success;
    /* */
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


// function sqlPrepareExecute($sql, $callerFunctionName)
// {
//     global $conn;
//     if (!sqlConnFound($callerFunctionName . ": " . __FUNCTION__)) {
//         return false;
//     }
//     $stmt = $conn->prepare($sql);
//     if (!$stmt->execute()) {
//         pushFeedbackToLog($callerFunctionName . ": " . $stmt->error, true);
//         return false;
//     }
//     return $stmt->get_result();
// }


// function sqlPrepareBindExecute($sql, $paramTypes, $paramArray, $callerFunctionName)
// {
//     global $conn;
//     if (!sqlConnFound($callerFunctionName . ": " . __FUNCTION__)) {
//         return false;
//     }
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param($paramTypes, ...$paramArray);
//     if (!$stmt->execute()) {
//         pushFeedbackToLog($callerFunctionName . ": " . $stmt->error, true);
//         return false;
//     }
//     $result =  $stmt->get_result();
//     return $result;
// }


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
