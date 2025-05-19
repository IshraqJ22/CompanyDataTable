<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        $query = "DELETE FROM employee_data WHERE EmpID = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Record deleted successfully.";
        } else {
            echo "Failed to delete record.";
        }
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Invalid request method.";
}
