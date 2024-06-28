<?php 
    session_start();

    // Check if user is logged in
        if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'user') { 
        require "db_conn.php";
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
        <!-- <div class="display-5">BARANGAY PROFILE</div> -->
        <div class="row">
            <div class="col-1">
                <div id="scroll-list" class="position-fixed d-flex flex-column gap-2 scroll-list-scrollspy text-center">
                    <a class="p-1 rounded" href="#scroll-item-1">Item 1</a>
                    <a class="p-1 rounded" href="#scroll-item-2">Item 2</a>
                    <a class="p-1 rounded" href="#scroll-item-3">Item 3</a>
                    <a class="p-1 rounded" href="#scroll-item-4">Item 4</a>
                    <a class="p-1 rounded" href="#scroll-item-5">Item 5</a>
                </div>
            </div>
            <div class="col-11">
                <div data-bs-spy="scroll" data-bs-target="#scroll-list" data-bs-offset="0" data-bs-smooth-scroll="true" class="scrollspy-example" tabindex="0">
                    <div id="scroll-item-1">asd</div>
                        <div class="bg-success" style="height: 98vh;"></div>
                    <div id="scroll-item-2">asd</div>
                        <div class="bg-danger" style="height: 98vh;"></div>
                    <div id="scroll-item-3">asd</div>
                        <div class="bg-secondary" style="height: 98vh;"></div>
                    <div id="scroll-item-4">asd</div>
                        <div class="bg-primary" style="height: 98vh;"></div>
                    <div id="scroll-item-5">asd</div>
                        <div class="bg-success" style="height: 98vh;"></div>
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