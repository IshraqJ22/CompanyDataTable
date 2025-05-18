<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $empID = $data['EmpID'];
    unset($data['EmpID']);

    $setClause = [];
    foreach ($data as $key => $value) {
        $columnName = '`' . str_replace('_', ' ', $key) . '`';
        $setClause[] = "$columnName = :$key";
    }
    $setClause = implode(', ', $setClause);

    $query = "UPDATE employee_data SET $setClause WHERE EmpID = :EmpID";
    $stmt = $pdo->prepare($query);

    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    $stmt->bindValue(":EmpID", $empID);

    if ($stmt->execute()) {
        echo "Record updated successfully.";
    } else {
        echo "Failed to update record.";
    }
}
