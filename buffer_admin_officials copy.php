<?php
    session_start();

// Check if user is logged in
    if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'admin') { 
    require "db_conn.php";

    // Define a function to calculate age
    function calculateAge($birthdate) {
        // Convert birthdate to a DateTime object
        $birthDate = new DateTime($birthdate);
        // Get the current date
        $currentDate = new DateTime();
        // Calculate the difference between the birthdate and the current date
        $ageInterval = $currentDate->diff($birthDate);
        // Extract the years from the interval
        $age = $ageInterval->y;
        // Return the age
        return $age;
    }


    if(isset($_POST["save_changes"])) {
        $full_name = $_POST['full_name'];
        $gender = $_POST['gender'];
        $civil_status = $_POST['civil_status'];
        $birthdate = $_POST['birthdate'];
        $birthplace = $_POST['birthplace'];
        $education = $_POST['education'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $id = $_POST['id'];

        $sql = "UPDATE `officials-table`
                SET `full_name` = '$full_name',
                    `gender` = '$gender',
                    `civil_status` = '$civil_status',
                    `birthdate` = '$birthdate',
                    `birthplace` = '$birthplace',
                    `education` = '$education',
                    `contact_number` = '$contact_number',
                    `email` = '$email'
                WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            $notification = "Changes saved.";
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
        <div class="display-5">BARANGAY OFFICIALS</div>
        <div class="px-lg-5"> 
            <div class="accordion pt-5" id="officials_accordion">
                <!-- DIV FOR BARANGAY CAPTAIN -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '1'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_captain">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_captain" aria-expanded="true" aria-controls="collapse_captain">
                        <?php echo $row['title']; ?>
                    </button>
                    </h2>
                    <div id="collapse_captain" class="accordion-collapse collapse show" aria-labelledby="heading_captain" data-bs-parent="#officials_accordion">
                        <div class="accordion-body"> 
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="1">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- DIV FOR SECRETARY -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '2'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_secretary">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_secretary" aria-expanded="false" aria-controls="collapse_secretary">
                        <?php echo $row['title']; ?>
                    </button>
                    </h2>
                    <div id="collapse_secretary" class="accordion-collapse collapse" aria-labelledby="heading_secretary" data-bs-parent="#officials_accordion">
                        <div class="accordion-body"> 
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="2">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- DIV FOR TREASURER -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '3'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_treasurer">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_treasurer" aria-expanded="false" aria-controls="collapse_treasurer">
                        Treasurer
                    </button>
                    </h2>
                    <div id="collapse_treasurer" class="accordion-collapse collapse" aria-labelledby="heading_treasurer" data-bs-parent="#officials_accordion">
                        <div class="accordion-body">
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="3">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- DIV FOR FIRST KAGAWAD -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '4'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_kagawad1">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_kagawad1" aria-expanded="false" aria-controls="collapse_kagawad1">
                        Kagawad
                    </button>
                    </h2>
                    <div id="collapse_kagawad1" class="accordion-collapse collapse" aria-labelledby="heading_kagawad1" data-bs-parent="#officials_accordion">
                        <div class="accordion-body">
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="4">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- DIV FOR SECOND KAGAWAD -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '5'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_kagawad2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_kagawad2" aria-expanded="false" aria-controls="collapse_kagawad2">
                        Kagawad
                    </button>
                    </h2>
                    <div id="collapse_kagawad2" class="accordion-collapse collapse" aria-labelledby="heading_kagawad2" data-bs-parent="#officials_accordion">
                        <div class="accordion-body">
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="5">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- DIV FOR THIRD KAGAWAD -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '6'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_kagawad3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_kagawad3" aria-expanded="false" aria-controls="collapse_kagawad3">
                        Kagawad
                    </button>
                    </h2>
                    <div id="collapse_kagawad3" class="accordion-collapse collapse" aria-labelledby="heading_kagawad3" data-bs-parent="#officials_accordion">
                        <div class="accordion-body">
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="6">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- DIV FOR FOURTH KAGAWAD -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '7'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_kagawad4">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_kagawad4" aria-expanded="false" aria-controls="collapse_kagawad4">
                        Kagawad
                    </button>
                    </h2>
                    <div id="collapse_kagawad4" class="accordion-collapse collapse" aria-labelledby="heading_kagawad4" data-bs-parent="#officials_accordion">
                        <div class="accordion-body">
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="7">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- DIV FOR FIFTH KAGAWAD -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '8'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_kagawad5">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_kagawad5" aria-expanded="false" aria-controls="collapse_kagawad5">
                        Kagawad
                    </button>
                    </h2>
                    <div id="collapse_kagawad5" class="accordion-collapse collapse" aria-labelledby="heading_kagawad5" data-bs-parent="#officials_accordion">
                        <div class="accordion-body">
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="8">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- DIV FOR SIXTH KAGAWAD -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '9'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_kagawad6">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_kagawad6" aria-expanded="false" aria-controls="collapse_kagawad6">
                        Kagawad
                    </button>
                    </h2>
                    <div id="collapse_kagawad6" class="accordion-collapse collapse" aria-labelledby="heading_kagawad6" data-bs-parent="#officials_accordion">
                        <div class="accordion-body">
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="9">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- DIV FOR SEVENTH KAGAWAD -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '10'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_kagawad7">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_kagawad7" aria-expanded="false" aria-controls="collapse_kagawad7">
                        Kagawad
                    </button>
                    </h2>
                    <div id="collapse_kagawad7" class="accordion-collapse collapse" aria-labelledby="heading_kagawad7" data-bs-parent="#officials_accordion">
                        <div class="accordion-body">
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="10">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!-- DIV FOR SK CHAIRMAN -->
                <?php 
                    $sql = "SELECT * FROM `officials-table`
                            WHERE id = '11'";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_skchairman">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_skchairman" aria-expanded="false" aria-controls="collapse_skchairman">
                        SK Chairman
                    </button>
                    </h2>
                    <div id="collapse_skchairman" class="accordion-collapse collapse" aria-labelledby="heading_skchairman" data-bs-parent="#officials_accordion">
                        <div class="accordion-body">
                            <form action="" class="d-sm-flex flex-row" method="POST" enctype="multipart/form-data">
                                <!-- DIV FOR OFFICIALS DATA -->
                                <div class="officials-data container-fluid row text-start m-lg-4">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="full_name" class="form-label">Full Name:</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="gender" class="form-label">Gender:</label>
                                        <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                        <option value="<?php echo $row ["gender"] ?>" selected hidden><?php echo $row ["gender"] ?> </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="civil_status" class="form-label">Civil Status:</label>
                                        <select class="form-select" id="civil_status" name="civil_status">
                                        <option value="<?php echo $row ["civil_status"] ?>" selected hidden><?php echo $row ["civil_status"] ?> </option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="age" class="form-label">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" value="<?php echo calculateAge($row['birthdate']); ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="birthdate" class="form-label">Birthdate:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('Y-m-d', strtotime($row["birthdate"])) ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label for="birthplace" class="form-label">Birthplace:</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-4">
                                        <label for="education" class="form-label">Education:</label>
                                        <input type="text" class="form-control" id="education" name="education" value="<?php echo $row['education']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-3">
                                        <label for="contact_number" class="form-label">Contact Number:</label>
                                        <input type="text" class="form-control" id="contact" name="contact_number" value="<?php echo $row['contact_number']; ?>">
                                    </div>
                                    <div class="form-group mb-3 col-lg-5">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div> 
                                    <div class="form-group text-end">
                                        <input type="hidden" id="id" name="id" value="11">
                                        <button type="submit" id="save_changes" name="save_changes" class="btn btn-outline-success">Save Changes</button>
                                    </div>
                                </div>
                                <!-- DIV FOR OFFICIALS IMAGE -->
                                <div class="officials-image ">
                                    <div class="form-group image-preview">
                                        <!-- this is where the image will preview -->
                                    </div> 
                                    <div class="form-group"> 
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
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