<?php
require_once(LIB_DIR . "sql.php");


function sqlQueryContentParam(
    $sqlQuery,
    $sqlTypes,
    $sqlParams,
    $tabelColumns = [],
    $onClickRoute = "",
    $keyAttributes = [],
    $isSpecialMS = false
) {
    global $dom;
    $contentTag = $dom->getElementById("content");

    $stmt = sqlPrepareBindExecute(
        $sqlQuery,
        $sqlTypes,
        $sqlParams,
        __FUNCTION__
    );
    $result = $stmt->get_result();
    if ($result) {
        $tableTag = sqlQueryTable($result, $tabelColumns, $onClickRoute, $keyAttributes, $isSpecialMS);
        $contentTag->appendChild($tableTag);
    }
}


function sqlQueryContent($sql, $tabelColumns = [], $onClickRoute = "", $keyAttributes = [])
{
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


function sqlQueryTable($sqlResult, $tabelColumns = [], $onClickRoute = "", $keyAttributes = [], $isSpecialMS = false)
{
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
            if ($isSpecialMS) {
                $tr = sqlQueryMilestoneRow($row, $onClickRoute, $i);
            } else {
                $tr = sqlQueryTableRow($row, $onClickRoute, $i);
            }

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


function sqlQueryMilestoneRow($sqlResultRow, $onClickRoute, $rowIndex = 0)
{
    global $dom;
    $trRoute = ($onClickRoute == "")
        ? "./"
        : "../" . findPage($onClickRoute) . "/index.php?row=" . $rowIndex;

    $tr = $dom->createElement("tr");
    $tr->setAttribute(
        "class",
        ($rowIndex % 2 == 0) ? "even_row" : "odd_row"
    );

    $tr->setAttribute("onclick", "window.location='" . $trRoute . "';");

    $td_MS = $dom->createElement("td");
    $td_MS->textContent = $sqlResultRow["number"];
    $tr->appendChild($td_MS);

    $td_name = $dom->createElement("td");
    $td_name->textContent = $sqlResultRow["name"];
    $tr->appendChild($td_name);

    $td_date = $dom->createElement("td");
    $td_date->textContent = $sqlResultRow["date"];
    $tr->appendChild($td_date);

    $td_progress = $dom->createElement("td");
    $td_progress->textContent = $sqlResultRow["files"] . "/" . $sqlResultRow["reqs"];
    $tr->appendChild($td_progress);

    $td_founds = $dom->createElement("td");
    $td_founds->textContent = (isset($sqlResultRow["paid"]) ? $sqlResultRow["paid"] : 0);
    $tr->appendChild($td_founds);

    return $tr;
}


function sqlQueryTableRow($sqlResultRow, $onClickRoute, $rowIndex)
{
    global $dom;
    $trRoute = ($onClickRoute == "")
        ? "./"
        : "../" . findPage($onClickRoute) . "/index.php?row=" . $rowIndex;

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
