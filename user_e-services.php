<?php
    session_start();

// Check if user is logged in
    if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'user') { 
    require "db_conn.php";
    
    $email_loggedin = $_SESSION['id'];

    /* BARANGAY CLEARANCE SQL */
    if(isset($_POST["barangayclearance_submit"])) {
        $purpose = $_POST['purpose'];
        $type_docu = $_POST['type_docu'];
        $status = $_POST['status'];

    $sql = "INSERT INTO `barangayclearance-table`
            (`id`,`requested_by`,`purpose`,`type_docu`,`status`)
            VALUES 
            (NULL, '$email_loggedin','$purpose','$type_docu','$status')";
    
    if ($conn->query($sql) === TRUE) {
        $notification = "Successfully requested." ;
    } else {
        $error = "Error: " . $sql . $conn->error ;
        /* echo "Error: " . $sql . "<br>" . $conn->error; */
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
    
    }



    /* CEDULA SQL */
    if(isset($_POST["cedula_submit"])) {
        $type_docu = $_POST['type_docu'];
        $height = $_POST['height'];
        $weight = $_POST['weight'];
        $tin = $_POST['tin'];
        $total_gross = $_POST['total_gross'];
        $total_earnings = $_POST['total_earnings'];
        $total_income = $_POST['total_income'];
        $status = $_POST['status'];

        $sql = "INSERT INTO `cedula-table` 
                (`id`,`requested_by`,`type_docu`,`height`,`weight`,`tin`,`total_gross`,`total_earnings`,`total_income`,`status`)
                VALUES 
                (NULL, '$email_loggedin','$type_docu', '$height','$weight','$tin','$total_gross','$total_earnings','$total_income','$status')";
        
        if ($conn->query($sql) === TRUE) {
            $notification = "Successfully requested." ;
        } else {
            $error = "Error: " . $sql . $conn->error ;
            /* echo "Error: " . $sql . "<br>" . $conn->error; */
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    }

    /* CERTIFICATE OF INDIGENCY  SQL */
    if(isset($_POST["indigency_submit"])) {
        $purpose = $_POST['purpose'];
        $type_docu = $_POST['type_docu'];
        $status = $_POST['status'];

    $sql = "INSERT INTO `indigency-table`
            (`id`,`requested_by`,`purpose`,`type_docu`,`status`)
            VALUES 
            (NULL, '$email_loggedin','$purpose','$type_docu','$status')";
    
    if ($conn->query($sql) === TRUE) {
        $notification = "Successfully requested." ;
    } else {
        $error = "Error: " . $sql . $conn->error ;
        /* echo "Error: " . $sql . "<br>" . $conn->error; */
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
    
    }

    /* CERTIFICATE OF RESIDENCY  SQL */
    if(isset($_POST["residency_submit"])) {
        $purpose = $_POST['purpose'];
        $type_docu = $_POST['type_docu'];
        $status = $_POST['status'];

    $sql = "INSERT INTO `residency-table`
            (`id`,`requested_by`,`purpose`,`type_docu`,`status`)
            VALUES 
            (NULL, '$email_loggedin','$purpose','$type_docu','$status')";
    
    if ($conn->query($sql) === TRUE) {
        $notification = "Successfully requested." ;
    } else {
        $error = "Error: " . $sql . $conn->error ;
        /* echo "Error: " . $sql . "<br>" . $conn->error; */
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
    
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="CSS/user.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/barangayicon.ico" />
    <title>Kamias Connect</title>
</head>
<body>
    <?php require "includes/user_navbar.php"?>
    <section class="container-fluid text-center">
        <div class="display-5">DOCUMENT ISSUANCE</div>
        <div class="container">Welcome to our online document issuance platform, where convenience meets efficiency. Experience fast, secure, and hassle-free transactions, ensuring your documents are delivered promptly to your doorstep or inbox. Simplify your paperwork today!</div>
        <div class="px-lg-5"> 
            <!-- DIV FOR THE BUTTON -->
            <div class="text-start p-2">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#document-modal">Request a new Document</button>
            </div>
            <!-- DIV FOR THE TABLE -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">DATE OF REQUEST</th>
                            <th scope="col">TYPE OF DOCUMENT</th>
                            <th scope="col">VALIDATED BY</th>
                            <th scope="col">STATUS</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "(SELECT 
                                        'barangayclearance' AS document_type,
                                        id,
                                        requested_by,
                                        purpose,
                                        type_docu AS type,
                                        status,
                                        date_requested AS date
                                    FROM 
                                        `barangayclearance-table`
                                    WHERE 
                                        requested_by = '$email_loggedin')
                                    UNION
                                    (SELECT 
                                        'cedula' AS document_type,
                                        id,
                                        requested_by,
                                        NULL AS purpose,
                                        type_docu AS type,
                                        status,
                                        date_requested AS date
                                    FROM 
                                        `cedula-table`
                                    WHERE 
                                        requested_by = '$email_loggedin')
                                    UNION
                                    (SELECT 
                                        'indigency' AS document_type,
                                        id,
                                        requested_by,
                                        purpose,
                                        type_docu AS type,
                                        status,
                                        date_requested AS date
                                    FROM 
                                        `indigency-table`
                                    WHERE 
                                        requested_by = '$email_loggedin')
                                    UNION
                                    (SELECT 
                                        'residency' AS document_type,
                                        id,
                                        requested_by,
                                        purpose,
                                        type_docu AS type,
                                        status,
                                        date_requested AS date
                                    FROM 
                                        `residency-table`
                                    WHERE 
                                        requested_by = '$email_loggedin')
                                    ORDER BY date DESC";
                                    $result = $conn->query($sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row["date"] ?></td>
                            <td><?php echo $row["document_type"] ?></td>
                            <td></td>
                            <td><?php echo $row["status"] ?></td>                        
                        </tr>
                    <?php } ?>
                        
                    </tbody>
                </table>                
            </div>
        </div>
    </section>
    <!-- MODALS FOR DOCUMENTS & ISSUANCES -->
    <section class="container-fluid text-center">
        <!-- MODAL FOR SELECTING TYPE OF DOCUMENT -->
        <div class="modal fade" id="document-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title fs-3">SELECT TYPE OF DOCUMENT</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>           
                    </div>
                    <div class="modal-body">
                        <div class="btn-group-vertical m-2">
                            <button type="button" class="btn btn-lg btn-outline-success" data-bs-target="#brgyid-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Barangay ID</button>
                            <button type="button" class="btn btn-lg btn-outline-success" data-bs-target="#clearance-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Barangay Clearance</button>
                            <button type="button" class="btn btn-lg btn-outline-success" data-bs-target="#cedula-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Cedula</button>
                            <button type="button" class="btn btn-lg btn-outline-success" data-bs-target="#indigency-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Certificate of Indigency</button>
                            <button type="button" class="btn btn-lg btn-outline-success" data-bs-target="#residency-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Certificate of Residency</button>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <!-- BARANGAY ID FORM MODAL -->
        <div class="modal fade" id="brgyid-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="btn btn-outline-success" data-bs-target="#document-modal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="fa-solid fa-arrow-left"></i></button>
                        <p class="modal-title fs-3 mx-2">BARANGAY ID</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="row text-start">
                            <div class="form-group mb-3 col-md-6">
                                <label for="last_name" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" id="last_name">
                            </div>   
                            <div class="form-group mb-3 col-md-6">                        
                                <label for="first_name" class="form-label">First Name:</label>
                                <input type="text" class="form-control" id="first_name">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="middle_name" class="form-label">Middle Name:</label>
                                <input type="text" class="form-control" id="middle_name">
                             </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="suffix" class="form-label">Suffix:</label>
                                <input type="text" class="form-control" id="suffix">
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="address" class="form-label">Address:</label>
                                <input type="textarea" class="form-control" id="address">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="bday" class="form-label">Birthday:</label>
                                <input type="date" class="form-control" id="bday">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="gender" class="form-label">Gender:</label>
                                <select class="form-select" id="gender" aria-label="Default select example">
                                    <option disabled selected>Please select...</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-outline-success w-100">Submit</button>
                            </div>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
        <!-- BARANGAY CLEARANCE FORM MODAL -->
        <div class="modal fade" id="clearance-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="btn btn-outline-success" data-bs-target="#document-modal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="fa-solid fa-arrow-left"></i></button>
                        <p class="modal-title fs-3 mx-2">BARANGAY CLEARANCE</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="row text-start" method="POST">
                        <?php 
                            $id = $_SESSION['id'];
                            $sql = "SELECT * 
                                    FROM `users-table`
                                    WHERE id = $id";
                            $result = $conn->query($sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <div class="form-group mb-3 col-md-6">
                                <label for="last_name" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $row ["last_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="first_name" class="form-label">First Name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $row ["first_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="middle_name" class="form-label">Middle Name:</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo $row ["middle_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="suffix" class="form-label">Suffix:</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="<?php echo $row ["suffix"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="address" class="form-label">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo $row ["address"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="purpose" class="form-label">Purpose:</label>
                                <select class="form-select" id="purpose" name="purpose" aria-label="Default select example">
                                    <option disabled selected>Please select...</option>
                                    <option value="application_employment">Application for Employment</option>
                                    <option value="overseas_travel">Overseas Travel Papers</option>
                                    <option value="transaction_bank">Transaction with a Bank of Leading Institution</option>
                                    <option value="travel">Travel Requirement</option>
                                    <option value="school">School Reference</option>
                                    <option value="calamity_aid">Processing of Calamity Loan / Disaster Aid</option>
                                    <option value="medicalfinancial_assist">Medical / Financial Assistance</option>
                                    <option value="sss_tin_postal">Application for SSS / TIN No. / Postal ID</option>
                                    <option value="senior_pwd">Application for Senior Citizen ID / Person with Disability ID</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <input type="hidden" id="status" name="status" value="pending">
                                <input type="hidden" id="type_docu" name="type_docu" value="Barangay Clearance">
                                <button type="submit" id="barangayclearance_submit" name="barangayclearance_submit" class="btn btn-outline-success w-100">Submit</button>
                            </div>
                            <?php } ?>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
        <!-- CEDULA FORM MODAL -->
        <div class="modal fade" id="cedula-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="btn btn-outline-success" data-bs-target="#document-modal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="fa-solid fa-arrow-left"></i></button>
                        <p class="modal-title fs-3 mx-2">CEDULA</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="row text-start" method="POST">
                        <?php 
                            $id = $_SESSION['id'];
                            $sql = "SELECT * 
                                    FROM `users-table`
                                    WHERE id = $id";
                            $result = $conn->query($sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <div class="form-group mb-3 col-md-6">
                                <label for="last_name" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $row ["last_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="first_name" class="form-label">First Name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $row ["first_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="middle_name" class="form-label">Middle Name:</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo $row ["middle_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="suffix" class="form-label">Suffix:</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="<?php echo $row ["suffix"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="address" class="form-label">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo $row ["address"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="civil_status" class="form-label">Civil Status:</label>
                                <input type="text" class="form-control" id="civil_status" name="civil_status" value="<?php echo $row ["civil_status"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="gender" class="form-label">Gender:</label>
                                <input type="text" class="form-control" id="gender" name="gender" value="<?php echo $row ["gender"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="birthplace" class="form-label">Birthplace:</label>
                                <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row ["birthplace"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="birthdate" class="form-label">Birthday:</label>
                                <input type="text" class="form-control" id="birthdate" name="birthdate" value="<?php echo $row ["birthdate"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="citizenship" class="form-label">Citizenship:</label>
                                <input type="text" class="form-control" id="citizenship" name="citizenship" value="<?php echo $row ["citizenship"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="occupation" class="form-label">Occupation:</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo $row ["occupation"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="height" class="form-label">Height:</label>
                                <input type="text" class="form-control" id="height" name="height">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="weight" class="form-label">Weight:</label>
                                <input type="text" class="form-control" id="weight" name="weight">
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="tin" class="form-label">TIN:</label>
                                <input type="text" class="form-control" id="tin" name="tin">
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="total_gross" class="form-label">TOTAL GROSS RECEIPTS BUSINESS:</label>
                                <input type="textarea" class="form-control" id="total_gross" name="total_gross">
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="total_earnings" class="form-label">TOTAL EARNINGS SALARIES/PROF:</label>
                                <input type="textarea" class="form-control" id="total_earnings" name="total_earnings">
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="total_income" class="form-label">TOTAL INCOME FROM REAL PROPERTY:</label>
                                <input type="textarea" class="form-control" id="total_income" name="total_income">
                            </div>
                            <div class="col-md-12 mt-2">
                                <input type="hidden" id="status" name="status" value="pending">
                                <input type="hidden" id="type_docu" name="type_docu" value="Cedula">
                                <button type="submit" class="btn btn-outline-success w-100" id="cedula_submit" name="cedula_submit">Submit</button>
                            </div>
                        <?php } ?>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
        <!-- CERTIFICATE OF INDIGENCY FORM MODAL -->
        <div class="modal fade" id="indigency-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="btn btn-outline-success" data-bs-target="#document-modal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="fa-solid fa-arrow-left"></i></button>
                        <p class="modal-title fs-3 mx-2">CERTIFICATE OF INDIGENCY</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="row text-start" method="POST">
                        <?php 
                            $id = $_SESSION['id'];
                            $sql = "SELECT * 
                                    FROM `users-table`
                                    WHERE id = $id";
                            $result = $conn->query($sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <div class="form-group mb-3 col-md-6">
                                <label for="last_name" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $row ["last_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="first_name" class="form-label">First Name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $row ["first_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="middle_name" class="form-label">Middle Name:</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo $row ["middle_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="suffix" class="form-label">Suffix:</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="<?php echo $row ["suffix"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="address" class="form-label">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo $row ["address"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="purpose" class="form-label">Purpose:</label>
                                <select class="form-select" id="purpose" name="purpose"aria-label="Default select example">
                                    <option disabled selected>Please select...</option>
                                    <option value="application_employment">Application for Employment</option>
                                    <option value="overseas_travel">Overseas Travel Papers</option>
                                    <option value="transaction_bank">Transaction with a Bank of Leading Institution</option>
                                    <option value="travel">Travel Requirement</option>
                                    <option value="school">School Reference</option>
                                    <option value="calamity_aid">Processing of Calamity Loan / Disaster Aid</option>
                                    <option value="medicalfinancial_assist">Medical / Financial Assistance</option>
                                    <option value="sss_tin_postal">Application for SSS / TIN No. / Postal ID</option>
                                    <option value="senior_pwd">Application for Senior Citizen ID / Person with Disability ID</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <input type="hidden" id="status" name="status" value="pending">
                                <input type="hidden" id="type_docu" name="type_docu" value="Certificate of Indigency">
                                <button type="submit" class="btn btn-outline-success w-100" id="indigency_submit" name="indigency_submit">Submit</button>
                            </div>
                        <?php } ?>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
        <!-- CERTIFICATE OF RESIDENCY FORM MODAL -->
        <div class="modal fade" id="residency-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="btn btn-outline-success" data-bs-target="#document-modal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="fa-solid fa-arrow-left"></i></button>
                        <p class="modal-title fs-3 mx-2">CERTIFICATE OF RESIDENCY</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="row text-start" method="POST">
                        <?php 
                            $id = $_SESSION['id'];
                            $sql = "SELECT * 
                                    FROM `users-table`
                                    WHERE id = $id";
                            $result = $conn->query($sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <div class="form-group mb-3 col-md-6">
                                <label for="last_name" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $row ["last_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="first_name" class="form-label">First Name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $row ["first_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="middle_name" class="form-label">Middle Name:</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo $row ["middle_name"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="suffix" class="form-label">Suffix:</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="<?php echo $row ["suffix"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="address" class="form-label">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo $row ["address"] ?>" disabled>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="purpose" class="form-label">Purpose:</label>
                                <select class="form-select" id="purpose" name="purpose"aria-label="Default select example">
                                    <option disabled selected>Please select...</option>
                                    <option value="application_employment">Application for Employment</option>
                                    <option value="overseas_travel">Overseas Travel Papers</option>
                                    <option value="transaction_bank">Transaction with a Bank of Leading Institution</option>
                                    <option value="travel">Travel Requirement</option>
                                    <option value="school">School Reference</option>
                                    <option value="calamity_aid">Processing of Calamity Loan / Disaster Aid</option>
                                    <option value="medicalfinancial_assist">Medical / Financial Assistance</option>
                                    <option value="sss_tin_postal">Application for SSS / TIN No. / Postal ID</option>
                                    <option value="senior_pwd">Application for Senior Citizen ID / Person with Disability ID</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <input type="hidden" id="status" name="status" value="pending">
                                <input type="hidden" id="type_docu" name="type_docu" value="Certificate of Residency">
                                <button type="submit" class="btn btn-outline-success w-100" id="residency_submit" name="residency_submit">Submit</button>
                            </div>
                        <?php } ?>
                        </form>
                    </div> 
                </div>
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