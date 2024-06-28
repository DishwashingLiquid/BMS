<?php
    session_start();

// Check if user is logged in
    if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'admin') { 
    require "db_conn.php";

    $email_loggedin = $_SESSION['email'];

    if(isset($_POST["add_button"])) {
        $title = $_POST['title'];
        $subtitle = $_POST['subtitle'];
        $description = $_POST['description'];
        $posted_by = $email_loggedin;

        $sql = "INSERT INTO `announcements-table`
                    (`id`, `title`, `subtitle`, `description`, `posted_by`)
                VALUES (NULL, '$title', '$subtitle', '$description', '$posted_by')";
        
        if ($conn->query($sql) === TRUE) {
            $notification = "Successfully added." ;
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
    <link rel="stylesheet" href="CSS/admin.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/barangayicon.ico" />
    <title>Kamias Connect</title>
</head>
<body> 
    <?php require "includes/admin_navbar.php"?>
    <section class="section text-center">
        <div class="display-5">ANNOUNCEMENTS</div>
        <div class="px-lg-5"> 
            <!-- DIV FOR THE BUTTON -->
            <div class="text-start p-2">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-modal">Add New</button> 
            </div>
            <!-- DIV FOR THE TABLE -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">DATE CREATED</th>
                            <th scope="col">TITLE</th>
                            <th scope="col" class="w-20">SUBTITLE</th>
                            <th scope="col" class="w-50">DESCRIPTION</th> 
                            <th scope="col">DATE END</th> 
                            <th scope="col">POSTED BY</th> 
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                            $sql = "SELECT *
                                    FROM `announcements-table`";
                            $result = $conn->query($sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                            $date_created = $row['date_created'];
                            $date_end = date('Y-m-d, H:i:s', strtotime($date_created . ' + 7 days'));
                        ?>
                        <tr>
                            <td><?php echo $row['date_created'] ?></td>            
                            <td><?php echo $row['title'] ?></td>            
                            <td><?php echo $row['subtitle'] ?></td>            
                            <td><?php echo $row['description'] ?></td>
                            <td><?php echo $date_end ?></td>
                            <td><?php echo $row['posted_by'] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>                
            </div>
        </div> 
    </section>
    <section>
        <div class="modal fade" id="add-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title fs-3">ADD NEW</p>       
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="form-group mb-3 col-md-12">
                                <label for="title" class="form-label">Announcements Title:</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="subtitle" class="form-label">Announcements Subtitle:</label>
                                <textarea class="form-control" id="subtitle" name="subtitle" rows="3"></textarea>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="description" class="form-label">Announcements Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="10"></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" id="add_button" name="add_button" class="btn btn-outline-success">Submit</button>
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            </div>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
    </section>
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
</body>
</html>
<?php 
} else {
    header("Location: login.php");
    exit();
}
?>