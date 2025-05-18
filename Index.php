<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Data</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <table id="companyData" class="display" style="width:100%">
        <thead>
            <tr>
                <th>EmpID</th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>StartDate</th>
                <th>ExitDate</th>
                <th>Title</th>
                <th>Supervisor</th>
                <th>ADEmail</th>
                <th>BusinessUnit</th>
                <th>EmployeeStatus</th>
                <th>EmployeeType</th>
                <th>PayZone</th>
                <th>EmployeeClassificationType</th>
                <th>TerminationType</th>
                <th>TerminationDescription</th>
                <th>DepartmentType</th>
                <th>Division</th>
                <th>DOB</th>
                <th>State</th>
                <th>JobFunctionDescription</th>
                <th>GenderCode</th>
                <th>LocationCode</th>
                <th>RaceDesc</th>
                <th>MaritalDesc</th>
                <th>PerformanceScore</th>
                <th>CurrentEmployeeRating</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'db_config.php';
            $query = "SELECT * FROM employee_data";
            $result = $pdo->query($query);

            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>" . htmlspecialchars($cell) . "</td>";
                    }
                    echo "<td>";
                    echo "<button class='edit-btn' data-row='" . htmlspecialchars(json_encode($row)) . "'>Edit</button> ";
                    echo "<button class='delete-btn' data-id='" . htmlspecialchars($row['EmpID']) . "'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>


    <div id="editModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Edit Data</h2>
            <form id="editForm">
                <div id="formFields"></div>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#companyData').DataTable({
                dom: '<"top"l>rt<"bottom"ip><"clear">'
            });


            $(document).on('click', '.edit-btn', function() {
                const rowData = JSON.parse($(this).attr('data-row'));
                let formFields = '';
                for (const [key, value] of Object.entries(rowData)) {
                    formFields += `<label>${key}</label><input type='text' name='${key}' value='${value}'><br>`;
                }
                $('#formFields').html(formFields);
                $('#editModal').css('display', 'flex');
            });


            $('.close-btn').on('click', function() {
                $('#editModal').css('display', 'none');
            });

            // Handle Delete Button Click
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).attr('data-id');
                if (confirm('Are you sure you want to delete this record?')) {

                    $.post('delete.php', {
                        id: id
                    }, function(response) {
                        alert(response);
                        location.reload();
                    });
                }
            });


            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.post('update.php', formData, function(response) {
                    alert(response);
                    location.reload();
                });
            });
        });
    </script>
</body>

</html>