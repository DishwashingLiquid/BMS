<?php
    session_start();

// Check if user is logged in
    if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'admin') { 
    require "db_conn.php";
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
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#">Add New</button> 
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
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            $sql = "
                                    (SELECT 
                                        'barangayclearance' AS document_type,
                                        a.id,
                                        CONCAT ( b.first_name,' ', b.middle_name,' ', b.last_name) AS requested_by,
                                        a.purpose,
                                        a.type_docu AS type,
                                        a.status,
                                        a.date_requested AS date
                                    FROM 
                                        `barangayclearance-table` a
                                    JOIN
                                        `users-table` b 
                                    ON
                                        a.requested_by = b.id
                                    )
                                    UNION
                                    (SELECT 
                                        'cedula' AS document_type,
                                        a.id,
                                        CONCAT ( b.first_name,' ', b.middle_name,' ', b.last_name) AS requested_by,
                                        NULL AS purpose,
                                        a.type_docu AS type,
                                        a.status,
                                        a.date_requested AS date
                                    FROM 
                                        `cedula-table` a
                                    JOIN
                                        `users-table` b 
                                    ON
                                        a.requested_by = b.id
                                    )
                                    UNION
                                    (SELECT 
                                        'indigency' AS document_type,
                                        a.id,
                                        CONCAT ( b.first_name,' ', b.middle_name,' ', b.last_name) AS requested_by,
                                        a.purpose,
                                        a.type_docu AS type,
                                        a.status,
                                        a.date_requested AS date
                                    FROM 
                                        `indigency-table` a
                                    JOIN
                                        `users-table` b 
                                    ON
                                        a.requested_by = b.id
                                    )
                                    UNION
                                    (SELECT 
                                        'residency' AS document_type,
                                        a.id,
                                        CONCAT ( b.first_name,' ', b.middle_name,' ', b.last_name) AS requested_by,
                                        a.purpose,
                                        a.type_docu AS type,
                                        a.status,
                                        a.date_requested AS date
                                    FROM 
                                        `residency-table` a
                                    JOIN
                                        `users-table` b 
                                    ON
                                        a.requested_by = b.id
                                    )
                                    ";
                                    $result = $conn->query($sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row["date"] ?></th>
                            <td><?php echo $row["document_type"] ?></td>
                            <td><?php echo $row["requested_by"] ?></td>
                            <td></td>
                            <td><?php echo $row["status"] ?></td>                       
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>                
            </div>
        </div> 
    </section>
    
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