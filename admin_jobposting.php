<?php
    session_start();

// Check if user is logged in
    if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'admin') { 
    require "db_conn.php";

    $email_loggedin = $_SESSION['email'];

    if(isset($_POST["job_post"])) {
        $job_title = $_POST['job_title'];
        $job_description = $_POST['job_description'];
        $company_name = $_POST['company_name'];
        $company_number = $_POST['company_number'];
        $company_email = $_POST['company_email'];
        $company_address = $_POST['company_address'];
        $salary = $_POST['salary'];
        $job_type = $_POST['job_type'];
        $posted_by = $email_loggedin;

        $sql = "INSERT INTO `jobposting-table`
                    (`id`, `job_title`, `job_description`, `company_name`, `company_number`, `company_email`, `company_address`, `salary`, `job_type`, `posted_by`)
                VALUES (NULL, '$job_title', '$job_description', '$company_name', '$company_number', '$company_email', '$company_address', '$salary', '$job_type', '$posted_by')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
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
        <div class="display-5">JOB POSTING</div>
        <div class="px-lg-5">
            <!-- DIV FOR THE BUTTON -->
            <div class="d-flex flex-row text-start p-2">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#jobpost-modal">Post a Job</button>
            </div>
            <!-- DIV FOR THE TABLE -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">DATE POSTED</th>
                            <th scope="col">POSTED BY</th>
                            <th scope="col" class="w-50">JOB TITLE</th>
                            <th scope="col"># OF APPLICANT</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $sql = "SELECT a.date_posted, a.posted_by, a.job_title, 
                                    (SELECT COUNT(*) FROM `applicants-table` WHERE job_id = a.id) AS applicant_count
                                    FROM `jobposting-table` a" ;
                            $result = $conn->query($sql);
                            while ($row = mysqli_fetch_assoc($result)) {                       
                        ?>               
                            <tr>
                                <td><?php echo $row["date_posted"] ?></td>
                                <td><?php echo $row["posted_by"] ?></td>
                                <td><?php echo $row["job_title"] ?></td>
                                <td><?php echo $row["applicant_count"] ?></td>
                            </tr>
                        <?php } ?> 
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- POST A JOB MODAL -->
    <section class="container-fluid text-center">
        <div class="modal fade" id="jobpost-modal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title fs-3">JOB INFORMATION</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center">
                        <form action="" method="POST" class="row text-start">
                            <div class="form-group mb-3 col-md-12">
                                <label for="job_title" class="form-label">Job Title:</label>
                                <input type="text" class="form-control" id="job_title" name="job_title">
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="job_description" class="form-label">Job Description:</label>
                                <textarea class="form-control" placeholder="Please provide information about the job. (e.g., qualifications, requirements, start date)" id="job_description" name="job_description" rows="8"></textarea>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="company_name" class="form-label">Company Name:</label>
                                <input type="text" class="form-control" id="company_name" name="company_name">
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="company_number" class="form-label">Company Contact Number:</label>
                                <input type="text" class="form-control" id="company_number" name="company_number">
                            </div>
                            <div class="form-group mb-3 col-md-8">
                                <label for="company_email" class="form-label">Company Email:</label>
                                <input type="text" class="form-control" id="company_email" name="company_email">
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="company_address" class="form-label">Company Address:</label>
                                <input type="text" class="form-control" id="company_address" name="company_address">
                            </div>                                                    
                            <div class="form-group mb-3 col-md-6">
                                <label for="salary" class="form-label">Salary:</label>
                                <input type="text" class="form-control" id="salary" name="salary">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="job_type" class="form-label">Job Type:</label>
                                <select class="form-select" aria-label="Default select example" id="job_type" name="job_type">
                                    <option disabled selected>Please select...</option>
                                    <option value="Full-time">Full-time Employee</option>
                                    <option value="Part-time">Part-time Employee</option>
                                    <option value="Contractual">Contractual</option>                                   
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-outline-success w-100" name="job_post" id="job_post">Submit</button>
                            </div> 
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