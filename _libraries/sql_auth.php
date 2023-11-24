<?php
require_once(LIB_DIR . "sql.php");


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
    $tUser = USER_TABLE;
    $changes = "`now_active`=FALSE, `last_active`=CURRENT_DATE";
    $setInactive = "UPDATE $tUser SET $changes WHERE `email`=?";
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
    $tUser = USER_TABLE;
    $fields = "`email`, `password`, `name`, `is_admin`";
    $sql = "SELECT $fields FROM $tUser WHERE `email`=?";
    // pushFeedbackToLog($sql);
    // pushFeedbackToLog(__FUNCTION__);
    $stmt = sqlPrepareBindExecute(
        $sql,
        "s",
        [$email],
        __FUNCTION__
    );
    $user = $stmt ? $stmt->get_result()->fetch_assoc() : null;
    // $user = $stmt->get_result()->fetch_assoc();
    // $stmt->store_result();

    $uExists = ($user !== null);
    $pwdMatch = $uExists ? password_verify($password, $user["password"]) : false;

    // pushFeedbackToLog($sql);
    // pushFeedbackToLog($user . " \"" . $email . "\" \"" . $password . "\"");
    // pushFeedbackToLog("uE = " . ($uExists ? "+" : "-"));
    // pushFeedbackToLog("pM = " . ($pwdMatch ? "+" : "-"));

    if (!$uExists || !$pwdMatch) {
        pushFeedbackToLog("Incorrect email address or password.", true);
        return false;
    }

    $changes = "`now_active`=TRUE, `last_active`=CURRENT_DATE";
    $setActive = "UPDATE $tUser SET $changes WHERE `email`=?";
    $stmt2 = sqlPrepareBindExecute(
        $setActive,
        "s",
        [$email],
        __FUNCTION__
    );

    // pushFeedbackToLog($setActive);

    return ($uExists && $pwdMatch && $stmt2) ? $user : false;
    // return false;
}


function sqlSignup($email, $password, $passwordAgain, $name, $isAdmin)
{
    $tUser = USER_TABLE;
    if (!passwordStrong($password)) {
        pushFeedbackToLog("Password is too weak.", true);
        return false;
    }
    if (!passwordCompare($password, $passwordAgain)) {
        pushFeedbackToLog("The passwords don't match.", true);
        return false;
    }
    $password = password_hash($password, PASSWORD_BCRYPT);
    $fields = "(`email`, `password`, `name`, `is_admin`)";
    $sql = "INSERT INTO $tUser $fields VALUES (?, ?, ?, ?)";
    $stmt = sqlPrepareBindExecute(
        $sql,
        "sssi",
        [$email, $password, $name, $isAdmin],
        __FUNCTION__
    );
    return $stmt;
}

