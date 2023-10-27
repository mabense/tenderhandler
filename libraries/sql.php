<?php

function sqlLogin($email, $password)
{
    global $conn;

    $fields = "`password`, `name`";
    $sql = "SELECT $fields FROM USER WHERE `email`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();
    $userPwd = $user["password"];

    if($password != $userPwd) {
        return false;
    }
    return $user;
}

function sqlSignup($email, $password, $name, $isAdmin)
{
    global $conn;
    $success = false;
    $fields = "(`email`, `password`, `name`, `is_admin`)";
    $adminBool = ($isAdmin ? "TRUE" : "FALSE");
    $sql = "INSERT INTO USER $fields VALUES (?, ?, ?, $adminBool)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $password, $name);
    $success = $stmt->execute();
    return $success;
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

function sqlQuery($sql)
{
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
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
