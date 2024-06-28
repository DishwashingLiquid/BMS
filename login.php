<?php 
    require "db_conn.php";
    session_start();

    // Retrieve data from login form
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL injection prevention
    $email = mysqli_real_escape_string($conn, $email);
    
    // Query to fetch the hashed password from Database
    $sql = "SELECT * FROM `users-table` WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];
        
        /* Verify the hashed password */
        if (password_verify($password, $hashed_password)) {
            /* Password is correct proceed with login */
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['id'] = $row['id'];

            /* Redirect based on user type */
            if ($_SESSION['user_type'] == 'user') {
                header("Location: user_announcements.php");                
            } elseif ($_SESSION['user_type'] == 'admin') {
                header("Location: admin_announcements.php");
            } elseif ($_SESSION['user_type'] == 'superadmin') {
                header("Location: superadmin_admins.php");
            }
            exit();
        } else {
            /* password is incorrect */
            $login_error = "Invalid Email or Password";
        }   
    } else {
        /* user with provided email not found */
        $login_error = "Invalid Email or Password";
    } 
    $conn->close();
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
<body class="body-bg">  
    <section class="main-vh d-flex">
        <section class="container text-start align-items-center m-auto">
            <div class="row g-0">
                <div class="col-xl-5 bg-light p-3 rounded-start">
                    <div class="text-center my-3">
                        <img src="/img/barangay_logo.png" class="img-fluid rounded-circle bg-light" width="300px">
                        <p class="text-success fw-bold p-2">Sign in to your account</p>
                    </div> 
                    <form action="" method="post"> 
                        <div class="px-3 mb-3">
                            <label class="form-label text-success fw-bold" for="email">Email: </label>
                            <input type="email" name="email" id="email" class="form-control" />
                        </div>
                        <div class="px-3 mb-3">
                            <label class="form-label text-success fw-bold" for="password">Password: </label>
                            <input type="password" name="password" id="password" class="form-control" />
                        </div>
                        <div class="px-3 mb-3">
                            <p class="text-success">No account yet? <a href="/register.php" class="text-success">Register here.</a></p> 
                        </div>
                        <div class="p-3">
                            <button type="submit" class="btn btn-outline-success w-100">Sign in</button>
                        </div>
                    </form>
                </div>
                <div class="col-xl-7 d-none d-xl-block bg-success rounded-end">
                    <img src="/img/KCONNECT.png" style="width: 100%; height:100%;" class="p-3" alt="Responsive image">
                </div>
            </div> 
        </section>
    </section>  
    <!-- ALERT NOTIFICATION -->
    <div class="alert-fixed">
        <?php if(isset($login_error)): ?> 
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo $login_error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
