<?php
    session_start();

 // Check if user is logged in
    if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'user') { 
    require "db_conn.php";
    
    $email_loggedin = $_SESSION['email'];
    $id_loggedin = $_SESSION['id'];

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
                    (`id`, `job_title`, `job_description`, `company_name`, `company_number`, `company_email`, `company_address`, `salary`, `job_type`, `date_posted`, `posted_by`)
                VALUES (NULL, '$job_title', '$job_description', '$company_name', '$company_number', '$company_email', '$company_address', '$salary', '$job_type', NULL, '$posted_by')";
        
        if ($conn->query($sql) === TRUE) {
            $notification = "Successfully added." ;
        } else {
            $error = "Error: " . $sql . $conn->error ;
            /* echo "Error: " . $sql . "<br>" . $conn->error; */
        }
    }

    if(isset($_POST["job_apply"])) {
        $job_id = $_POST['job_id'];
        $applicant_id = $id_loggedin;

        $sql = "INSERT INTO `applicants-table`
                    (`id`, `job_id`, `applicant_id`)
                VALUES (NULL, '$job_id', '$applicant_id')";
                
        if ($conn->query($sql) === TRUE) {
            echo "Update successful.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

// ChartJS
$sql = "SELECT 
                CASE 
                    WHEN occupation IN ('Accountant', 'Architect', 'Call center agent', 'Chef/Cook', 'Freelancer', 'Engineer', 'Doctor', 'Driver', 'Electrician', 'Farmer', 'Government Employee', 'IT Professional', 'Lawyer', 'Nurse', 'OFW (Overseas Filipino Worker)', 'Police', 'Salesperson', 'Teacher') THEN 'Employed'
                    WHEN occupation = 'Student' THEN 'Student'
                    WHEN occupation = 'Retired' THEN 'Retired'
                    WHEN occupation = 'Unemployed' THEN 'Unemployed'
                    WHEN occupation = 'Bussinessman' THEN 'Business Owner'
                    ELSE 'Other'
                END AS employment_status
            FROM 
                `users-table`";

    $result = $conn->query($sql);
//CHARTJS
    
    // Get total number of users
        $total_users_sql = "SELECT COUNT(*) as total_users FROM `users-table`";
        $total_users_result = $conn->query($total_users_sql);
        $total_users_row = $total_users_result->fetch_assoc();
        $total_users = $total_users_row['total_users'];

    // Initialize an array to store the data points
    $dataPoints = array(
        array("label" => "Employed", "y" => 0),
        array("label" => "Student", "y" => 0),
        array("label" => "Retired", "y" => 0),
        array("label" => "Unemployed", "y" => 0),
        array("label" => "Business Owner", "y" => 0)
    );

    // Iterate over the result set and update the data points
    while ($row = $result->fetch_assoc()) {
        switch ($row['employment_status']) {
            case 'Employed':
                $dataPoints[0]['y']++;
                break;
            case 'Student':
                $dataPoints[1]['y']++;
                break;
            case 'Retired':
                $dataPoints[2]['y']++;
                break;
            case 'Unemployed':
                $dataPoints[3]['y']++;
                break;
            default:
                $dataPoints[4]['y']++;
                break;
        }
    }

    // Calculate the percentage for each category
    foreach ($dataPoints as &$dataPoint) {
        $dataPoint['y'] = ($dataPoint['y'] / $total_users) * 100;
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
    <script>
        window.onload = function() {    
            CanvasJS.addColorSet("greenShades",
                [//colorSet Array
                "#2F4F4F",
                "#008080",
                "#2E8B57",
                "#3CB371",
                "#90EE90", 
                "#d0ffd0"               
                ]);
            var chart = new CanvasJS.Chart("job_analytics", {
                animationEnabled: true,
                colorSet: "greenShades",
                title: {
                    text: "Employment Statistics of Barangay West Kamias",
                    fontFamily: "Montserrat, sans-serif"
                },
                subtitles: [{
                    text: "April 2024"
                }],
                data: [{
                    type: "pie",
                    yValueFormatString: "#,##0.00\"%\"",
                    indexLabel: "{label} ({y})",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();
            
            }
    </script>
</head>
<body>
    <?php require "includes/user_navbar.php"?>
    <section class="container-fluid text-center">
        <div class="display-5">JOB POSTING</div>
        <div class="row">
            <!-- JOB ANALYTICS COLUMN -->
            <div class="col-xl-3 my-auto">
                <div class="jobpost-chart p-2" id="job_analytics"></div>
                <div class="jobpost-ads mt-2 p-xl-4 p-2">
                    <p class="fs-1 fw-bold lh-1">The simplest way to post your jobs</p>
                    <p class="fs-5">Attract candidates and start hiring</p>
                    <p class="fs-6">Dive into the growing talent pool, and connect with people of the Barangay. We strive to make the posting process simple so you can stay focused on finding the right candidate for the job.</p>
                    <button type="button" class="btn btn-outline-light btn-lg fw-bold m-2" data-bs-toggle="modal" data-bs-target="#jobpost-modal">Post a job <i class="fa-solid fa-chevron-right"></i></button> <br>
                    <button type="button" class="btn btn-outline-light fw-bold border-0" data-bs-toggle="modal" data-bs-target="#viewposted-modal">view your posts <i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
            <!-- JOB POSTING COLUMN -->
            <div class="col-xl-9 overflow-auto bg-light">
                <div class="row jobs-container mx-auto" id="jobs_container">
                    <?php
                        $sql = "SELECT * FROM `jobposting-table`";
                        $result = $conn->query($sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="col-xl-3 mb-4">
                        <div class="card">                                
                            <div class="card-body">
                                <div class="card-title fs-4 fw-bold"><?php echo $row ['job_title']; ?></div>
                                <div class="card-subtitle fst-italic" id="company_name"><?php echo $row ['company_name']; ?></div>
                                <div class="card-text text-muted p-2">
                                    <div id="salary"><i class="fa-solid fa-peso-sign px-1"></i><?php echo $row ['salary']; ?></div>
                                    <div id="job_type"><?php echo $row ['job_type']; ?></div>
                                </div>
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#jobapply-modal-<?php echo $row ['id']; ?>">view details</button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <!-- POST A JOB MODAL -->
    <section class="container-fluid text-center">
        <div class="modal fade" id="jobpost-modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
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
                                    <option value="full_time">Full-time Employee</option>
                                    <option value="part_time">Part-time Employee</option>
                                    <option value="contractual">Contractual</option>                                   
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
    <!-- POSTED JOB MODAL -->
    <section class="container-fluid text-center">
        <div class="modal fade" id="viewposted-modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title fs-3">JOB POSTED</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">DATE POSTED</th>
                                        <th scope="col">JOB TITLE</th>
                                        <th scope="col"># OF APPLICANTS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $email_loggedin = $_SESSION['email'];
                                        // Prepare the SQL query
                                        $sql = "SELECT a.id, a.job_title, a.date_posted, a.posted_by,
                                                (SELECT COUNT(*) FROM `applicants-table` WHERE job_id = a.id) AS applicant_count
                                                FROM `jobposting-table` a 
                                                WHERE a.posted_by = ?";
                                        
                                        // Prepare the statement
                                        $stmt = $conn->prepare($sql);
                                        // Bind the value to the placeholder
                                        $stmt->bind_param("s", $email_loggedin); // Assuming 'posted_by' is a string
                                        // Execute the query
                                        $stmt->execute();
                                        // Get the result
                                        $result = $stmt->get_result();
                                        // Fetch data
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['date_posted'];?></td>
                                        <td><?php echo $row['job_title'];?></td>
                                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#viewdetails-modal-<?php echo $row['id'] ?>" data-bs-dismiss="modal"><?php echo $row["applicant_count"]; ?></a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            $sql_jobs = "SELECT * FROM `jobposting-table`";
            $result_jobs = $conn->query($sql_jobs);
            while ($row_jobs = mysqli_fetch_assoc($result_jobs)) {
        ?>
        <div class="modal fade" id="viewdetails-modal-<?php echo $row_jobs['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="btn btn-outline-success" data-bs-target="#viewposted-modal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="fa-solid fa-arrow-left"></i></button>
                        <p class="modal-title fs-3 mx-2">LIST OF APPLICANTS</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name of Applicant</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Contact #</th>
                                        <th scope="col">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $jobid_selected = $row_jobs['id'];
                                        // Prepare the SQL query
                                        $sql = "SELECT CONCAT(a.first_name, ' ', a.middle_name,' ', a.last_name) AS full_name, a.address, a.contact_number, a.email
                                                FROM `users-table` a
                                                INNER JOIN `applicants-table` b
                                                    ON a.id = b.applicant_id
                                                WHERE b.job_id = ?";

                                        // Prepare the statement
                                        $stmt = $conn->prepare($sql);
                                        // Bind the value to the placeholder
                                        $stmt->bind_param("s", $jobid_selected); // Assuming 'posted_by' is a string
                                        // Execute the query
                                        $stmt->execute();
                                        // Get the result
                                        $result = $stmt->get_result();
                                        // Fetch data
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['full_name'] ?></td>
                                        <td><?php echo $row['address'] ?></td>
                                        <td><?php echo $row['contact_number'] ?></td>
                                        <td><?php echo $row['email'] ?></td>
                                    </tr> 
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </section>
    <!-- APPLY FOR A JOB MODAL -->
    <?php 
        $sql = "SELECT * FROM `jobposting-table`";
        $result = $conn->query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <section class="container-fluid text-center">
        <div class="modal fade" id="jobapply-modal-<?php echo $row ['id'];?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title fs-3">JOB DETAILS</p> 
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="fs-4 fw-bold"><?php echo $row ['job_title']; ?></div>
                            <div class="d-flex justify-content-center">
                                <div class="text-muted p-2"><i class="fa-solid fa-peso-sign px-1"></i><?php echo $row ['salary']; ?></div>
                                <div class="text-muted p-2"><i class="fa-solid fa-user-clock px-1"></i><?php echo $row ['job_type']; ?></div>
                            </div>
                            <div class="d-flex text-start">
                                <i class="fa-solid fa-people-roof my-auto p-1"></i>
                                <div class="fst-italic"><?php echo $row ['company_name']; ?></div>
                            </div>
                            <div class="d-flex text-start">
                                <i class="fa-solid fa-location-dot my-auto p-1"></i>
                                <div class="fst-italic"><?php echo $row ['company_address']; ?></div>
                            </div>
                            <div class="d-flex text-start">
                                <i class="fa-solid fa-phone my-auto p-1"></i>
                                <div class="fst-italic"><?php echo $row ['company_number']; ?></div>
                            </div>
                            <div class="d-flex text-start">
                                <i class="fa-solid fa-envelope my-auto p-1"></i>
                                <div class="fst-italic"><?php echo $row ['company_email']; ?></div>
                            </div>  
                            <textarea class="form-control" name="job_description" id="job_description" readonly><?php echo $row ['job_description']; ?></textarea>
                            <div class="mt-2">
                                <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-outline-success w-100" name="job_apply" id="job_apply">Apply</button>
                            </div>
                        </form>
                    </div> 
                </div>
            </div> 
        </div>
    </section>
    <?php } ?>

    <!-- ALERT NOTIFICATION -->
    <div class="alert-fixed">
        <?php if(isset($notification)): ?> 
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $notification; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" area-label="Close"></button>
            </div>  
        <?php endif; ?>        
        <?php if(isset($error)): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" area-label="Close"></button>
            </div>  
        <?php endif; ?>
    </div>
    
    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- PIE CHART JS -->
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <!-- MASONRY JS -->
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"></script>
    <script>
        var masonry = new Masonry('#jobs_container', {
            itemSelector: '.col-xl-3',
            columnWidth: '.col-xl-3',
            gutter: 0
        });
        </script>
    <script>
        window.addEventListener('resize', function() {
            masonry.layout();
        });
    </script>
</body>
</html>
<?php 
} else {
    header("Location: login.php");
    exit();
}
?>