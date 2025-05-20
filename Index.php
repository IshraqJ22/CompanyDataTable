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
    <?php
    include 'db_config.php';
    $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'employee_data'";
    $columns = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN);
    ?>
    <h1>Company Data</h1>
    <table id="companyData" class="display table-responsive" style="width:100%">
        <thead>
            <tr>
                <th>EmpID</th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>Title</th>
                <th>ADEmail</th>
                <th>DOB</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT EmpID, FirstName, LastName, Title, ADEmail, DOB FROM employee_data";
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


    <div id="deleteModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete this record?</p>
            <button id="confirmDelete">Yes</button>
            <button id="cancelDelete">No</button>
        </div>
    </div>


    <div id="messageModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h2 id="messageText"></h2>
            <button id="closeMessageModal">Close</button>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#companyData').DataTable({
                dom: '<"top"lf>rt<"bottom"ip><"clear">',
                responsive: true
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

            let deleteId = null;

            $(document).on('click', '.delete-btn', function() {
                deleteId = $(this).attr('data-id');
                $('#deleteModal').css('display', 'flex');
            });

            $('#confirmDelete').on('click', function() {
                if (deleteId) {
                    $.post('delete.php', { id: deleteId })
                        .done(function(response) {
                            $('#messageText').text(response);
                            $('#messageModal').css('display', 'flex');
                            location.reload(); // Reload only after the request completes
                        })
                        .fail(function() {
                            alert('Failed to delete the record. Please try again.');
                        });
                }
                $('#deleteModal').css('display', 'none');
            });

            $('#cancelDelete').on('click', function() {
                $('#deleteModal').css('display', 'none');
            });

            $('#closeMessageModal').on('click', function() {
                $('#messageModal').css('display', 'none');
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.post('update.php', formData)
                    .done(function(response) {
                        $('#messageText').text(response);
                        $('#messageModal').css('display', 'flex');
                    })
                    .fail(function() {
                        $('#messageText').text('Failed to update the record. Please try again.');
                        $('#messageModal').css('display', 'flex');
                    });
            });
        });
    </script>
</body>

</html>