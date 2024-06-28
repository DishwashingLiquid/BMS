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
        <section class="container-fluid text-center text-lg-start">
            <div class="row">
                <!-- DIV FOR MAIN PAGE -->
                <div class="col-lg-8">
                    <div class="text-center">
                        <img src="./img/kapitan.jpg" alt="" class="img-fluid" style="height: 40vh;">
                    </div>
                    <div class="">
                        <div id="carouselExampleRide" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-bs-interval="5000">
                                    <img src="./img/default-img.png" class="d-block w-100 pb-5" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Second slide label</h5>
                                        <p>Some representative placeholder content for the second slide.</p>
                                    </div>
                                </div>
                                <div class="carousel-item" data-bs-interval="5000">
                                    <img src="./img/barangay_grp.jpg" class="d-block w-100 pb-5" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Second slide label</h5>
                                        <p>Some representative placeholder content for the second slide.</p>
                                    </div>
                                </div>
                                <div class="carousel-item" data-bs-interval="5000">
                                    <img src="./img/KCONNECT.PNG" class="d-block w-100" alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- DIV FOR THE ANNOUNCEMENTS -->
                <div class="col-lg-4">
                    <div class="fs-4 fw-bold border-bottom pb-3 mt-3 mb-0 text-center">ANNOUNCEMENTS</div>
                    <div class="position-relative mb-0 px-lg-3 pt-lg-1 announcements overflow-auto">
                        <?php
                            $sql = "SELECT * FROM `announcements-table`";
                            $result = $conn->query($sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="border-bottom pb-3 mt-3">
                            <button type="button" class="btn btn-light w-100" data-bs-toggle="modal" data-bs-target="#announcement-modal-<?php echo $row["id"]; ?>">
                                <p><?php echo $row ["date_created"] ?></p>
                                <h3 class="font-weight-bold mt-0"><?php echo $row ["title"] ?></h3>
                                <p><?php echo $row ["subtitle"] ?></p>
                            </button>
                        </div>
                        <?php } ?>
                    </div>                 
                </div>
            </div>
        </section>
        <section>
            <?php
                $sql = "SELECT * FROM `announcements-table`";
                $result = $conn->query($sql);
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="modal fade" id="announcement-modal-<?php echo $row["id"]; ?>" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    
                    <div class="modal-content">
                        <div class="modal-header"> 
                            <h1 class="modal-title fs-3" id="exampleid"><?php echo $row ["title"] ?></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body"><?php echo $row ["description"] ?></div>
                        <div class="modal-footer"><?php echo $row ["date_created"] ?></div>
                    </div>
                </div> 
            </div>
            <?php } ?>
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