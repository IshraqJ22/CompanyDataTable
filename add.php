<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empID = $_POST['EmpID'];
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $title = $_POST['Title'];
    $adEmail = $_POST['ADEmail'];
    $dob = $_POST['DOB'];

    try {
        $query = "INSERT INTO employee_data (EmpID, FirstName, LastName, Title, ADEmail, DOB) VALUES (:EmpID, :FirstName, :LastName, :Title, :ADEmail, :DOB)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':EmpID', $empID);
        $stmt->bindParam(':FirstName', $firstName);
        $stmt->bindParam(':LastName', $lastName);
        $stmt->bindParam(':Title', $title);
        $stmt->bindParam(':ADEmail', $adEmail);
        $stmt->bindParam(':DOB', $dob);

        if ($stmt->execute()) {
            echo "Record added successfully.";
        } else {
            echo "Failed to add record.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
