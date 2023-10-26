<?php

function dbConn()
{
    global $conn;
    if (!isset($conn)) {
        $conn = new mysqli("127.0.0.1", "root", "", "tenderdb")
            or die("sql kapcsolat létrehozása nem sikerült");
        $conn->query("SET NAMES UTF-8"); 
        $conn->query("SET character_set_results=utf-8");
        $conn->set_charset("utf-8");
    }
    return $conn;
}

function dbQuery($sql)
{
    $conn = dbConn();
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

function dbSignup($email, $password, $name, $isAdmin)
{
    $success = false;
    $conn = dbConn();

    $fields = "(`email`, `password`, `name`, `is_admin`)";
    $adminBool = ($isAdmin ? "TRUE" : "FALSE");
    $sql = "INSERT INTO USER $fields VALUES (?, ?, ?, $adminBool)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $email, $password, $name);
    $success = $stmt->execute();
    $conn->close(); 
    return $success;
}

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

function _sqlTest($tableName)
{
    $conn = dbConn();
    $sql = "SELECT * FROM USER WHERE `name`='Kis Pista'";
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}
