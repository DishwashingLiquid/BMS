<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body> 
    <div class="sidebar" id="sidebar">
        <nav class="navbar-dark flex-column text-center">                  
            <ul class="navbar-nav fw-bold"> 
                <div class="navbar-logo">
                    <img class="img-fluid" src="../img/barangay_logo.png" alt="barangay logo">
                    <div class="text-success mx-2 fw-bold fs-4">KamiasConnect</div> 
                </div>
                <li class="nav-item mx-4 my-1">
                    <a class="nav-link" href="../admin_officials.php">Barangay Officials</a>
                </li> 
                <li class="nav-item dropdown px-1">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-display="static">Barangay Users</a>
                    <ul class="dropdown-menu bg-success text-center p-1 border-0">
                        <li><a class="dropdown-item link-light" href="../admin_forapproval.php">For Approval</a></li>
                        <li><a class="dropdown-item link-light" href="../admin_users.php">List of Users</a></li>
                    </ul>
                </li>
                <li class="nav-item mx-4 my-1">
                    <a class="nav-link" href="../admin_announcements.php">Announcements</a>
                </li>
                <li class="nav-item mx-4 my-1">
                    <a class="nav-link" href="../admin_budgettracker.php">Budget Tracker</a>
                </li>
                <li class="nav-item mx-4 my-1">
                    <a class="nav-link" href="../admin_e-services.php">e-Services</a>
                </li>
                <li class="nav-item mx-4 my-1">
                    <a class="nav-link" href="../admin_jobposting.php">Job Posting</a>
                </li> 
                <li class="nav-item mx-4 my-1 mt-auto">
                    <a class="nav-link" href="../admin_account.php">Account Settings</a>
                </li>
            </ul> 
        </nav>
    </div>
    <!-- TOGGLE BUTTON FOR RESPONSIVE SIDEBAR -->
    <div class="section">
        <button class="btn btn-primary d-sm-none" onclick="toggleSidebar()"></button> 
    </div>

    <script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle('show');
    } 
    </script> 
</body>
</html>

