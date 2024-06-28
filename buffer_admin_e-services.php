<?php
    session_start();

    // Check if user is logged in
    if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'admin') { 
        require "db_conn.php";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_id'])) {
            $document_id = $_POST['document_id'];
            $document_type = $_POST['document_type'];
            $admin_email = $_SESSION['email']; 

            // Update the document status and validated_by field
            $sql = "UPDATE `{$document_type}-table` 
                    SET status = 'Validated', validated_by = '{$admin_email}' 
                    WHERE id = {$document_id} ";
            
            if ($conn->query($sql) === TRUE) {
                $notification = "Changes saved.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Add New button
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_type'], $_POST['user_id'])) {
            $document_type = $_POST['document_type'];
            $user_id = $_POST['user_id'];
            $admin_email = $_SESSION['email']; 

            // Prepare and execute the SQL query to create the new document
            $sql = "INSERT INTO `{$document_type}-table` (requested_by, validated_by, status, date_requested)
                    VALUES ('{$user_id}', '{$admin_email}', 'Pending', NOW())";

            if ($conn->query($sql) === TRUE) {
                $notification = "New document created successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="CSS/admin.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/barangayicon.ico" />
    <title>Kamias Connect</title>
</head>
<body>
    <?php require "includes/admin_navbar.php"?>
    <section class="section text-center">
        <div class="display-5">DOCUMENT ISSUANCE</div> 
        <div class="px-lg-5"> 
            <!-- DIV FOR THE BUTTON -->
            <div class="text-start p-2">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#documentTypeModal">Add New</button> 
            </div>
            <!-- DIV FOR THE TABLE -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">DATE OF REQUEST</th>
                            <th scope="col">TYPE OF DOCUMENT</th>
                            <th scope="col">REQUESTED BY</th>
                            <th scope="col">VALIDATED BY</th>
                            <th scope="col">STATUS</th> 
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $sql = "(SELECT 'barangayclearance' AS document_type, a.id, CONCAT ( b.first_name,' ', b.middle_name,' ', b.last_name) AS requested_by, a.purpose,
                                a.type_docu AS type, a.status, a.date_requested AS date, a.validated_by
                                FROM `barangayclearance-table` a
                                JOIN `users-table` b 
                                ON a.requested_by = b.id
                                LEFT JOIN `users-table` c
                                ON a.validated_by = c.id)
                            UNION
                                (SELECT 'cedula' AS document_type, a.id, CONCAT ( b.first_name,' ', b.middle_name,' ', b.last_name) AS requested_by,
                                NULL AS purpose, a.type_docu AS type, a.status, a.date_requested AS date, a.validated_by
                                FROM `cedula-table` a
                                JOIN `users-table` b 
                                ON a.requested_by = b.id
                                LEFT JOIN `users-table` c
                                ON a.validated_by = c.id)
                            UNION
                                (SELECT 'indigency' AS document_type, a.id, CONCAT ( b.first_name,' ', b.middle_name,' ', b.last_name) AS requested_by, a.purpose, a.type_docu AS type, a.status, a.date_requested AS date, a.validated_by
                                FROM `indigency-table` a
                                JOIN `users-table` b 
                                ON a.requested_by = b.id 
                                LEFT JOIN `users-table` c
                                ON a.validated_by = c.id)
                            UNION
                                (SELECT 'residency' AS document_type, a.id, CONCAT ( b.first_name,' ', b.middle_name,' ', b.last_name) AS requested_by, a.purpose, a.type_docu AS type, a.status, a.date_requested AS date, a.validated_by
                                FROM `residency-table` a
                                JOIN `users-table` b 
                                ON a.requested_by = b.id
                                LEFT JOIN `users-table` c
                                ON a.validated_by = c.id)
                                ";
                        $result = $conn->query($sql);                   
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row["date"] ?></td>
                            <td><?php echo $row["document_type"] ?></td>
                            <td><?php echo $row["requested_by"] ?></td>
                            <td><?php echo $row["validated_by"] ?></td>
                            <td><?php echo $row["status"] ?></td>  
                            <td>
                                <?php if ($row["status"] != 'Validated') { ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="document_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="document_type" value="<?php echo $row['document_type']; ?>">
                                        <button type="submit" class="btn btn-primary">Validate</button>
                                    </form>
                                <?php } ?>
                            </td>                     
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>                
            </div>
        </div> 
    </section>

    <section class="container-fluid text-center">
     <!-- First Modal: Select Document Type -->
     <div class="modal fade" id="documentTypeModal" tabindex="-1" aria-labelledby="documentTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title fs-3" id="documentTypeModalLabel">Select Document Type</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="btn-group-vertical m-2">
                        <button type="button" class="btn btn-outline-success" onclick="openUserModal('barangayclearance')">Barangay Clearance</button>
                        <button type="button" class="btn btn-outline-success" onclick="openUserModal('cedula')">Cedula</button>
                        <button type="button" class="btn btn-outline-success" onclick="openUserModal('indigency')">Certificate of Indigency</button>
                        <button type="button" class="btn btn-outline-success" onclick="openUserModal('residency')">Certificate of Residency</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Modal: Select User -->
    <div class="modal fade" id="userSelectModal" tabindex="-1" aria-labelledby="userSelectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userSelectModalLabel">Select User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userSelectForm" method="POST" action="">
                        <input type="hidden" name="document_type" id="selectedDocumentType">
                        <div class="form-group">
                            <label for="userSelect">Choose User</label>
                            <select class="form-select" id="userSelect" name="user_id" required>
                                <?php
                                $sql = "SELECT id, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name FROM `users-table`";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['full_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Create Document</button>
                    </form>
                </div>
            </div>
        </div>
    </div>                       

    </section>

    <script>
        function openUserModal(documentType) {
            document.getElementById('selectedDocumentType').value = documentType;
            var userSelectModal = new bootstrap.Modal(document.getElementById('userSelectModal'));
            userSelectModal.show();
        }
    </script>
    
    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</body>
</html>
<?php 
    } else {
        header("Location: login.php");
        exit();
    }
?>
