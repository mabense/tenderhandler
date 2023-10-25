<?php

function dbConn()
{
    $conn = new mysqli("localhost", "root", "", "tenderdb")
        or die("sql kapcsolat létrehozása nem sikerült");
    $conn->query("SET NAMES UTF-8");
    $conn->query("SET character_set_results=utf-8");
    $conn->set_charset("utf-8");
    return $conn;
}

function dbTest($conn=null)
{
    if ($conn === null) {
        $conn = dbConn();
    }
    $sql = "SELECT * FROM `user` WHERE `name`='Kis Pista'`";
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}
