<?php
    session_start();

    // Check if user is logged in
    if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'admin') { 
    require "db_conn.php";

    if(isset($_POST["approve_button"])) {
        $selected_ids = $_POST['selected_ids'];

        // Split the selected IDs into an array
        $extract_id = explode(',', $selected_ids);

        // iterate over each ID and update the database
        foreach ($extract_id as $id) {
            $sql = "UPDATE `users-table` SET
                    `approval` = 'yes'
                    WHERE id = $id";
                if ($conn->query($sql) === TRUE) {
                    $notification = "Approved successfuly.";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
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
    <form action="" method="POST">
        <section class="section text-center">
            <div class="display-5">BARANGAY USERS - FOR APPROVAL</div> 
            <div class="px-lg-5">                 
                <!-- DIV FOR THE BUTTON -->
                <div class="text-start p-2">
                    <button type="submit" class="btn btn-success" onclick="approvalModal()">Approve</button>
                </div>
                <!-- DIV FOR THE TABLE -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>                
                                <th scope="col"></th>
                                <th scope="col">DATE REGISTERED</th>
                                <th scope="col">USER TYPE</th>
                                <th scope="col">LAST NAME</th>
                                <th scope="col">FIRST NAME</th>
                                <th scope="col">MIDDLE NAME</th>
                                <th scope="col">BIRTHDAY</th>
                                <th scope="col">AGE</th>
                                <th scope="col">GENDER</th>
                                <th scope="col">ADDRESS</th>
                                <th scope="col">OCCUPATION</th>
                                <th scope="col">CIVIL STATUS</th>
                                <th scope="col">CITIZENSHIP</th>
                                <th scope="col">CONTACT #</th>
                                <th scope="col">EMAIL</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
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
                                $sql = "SELECT * FROM `users-table`
                                        WHERE approval = 'no'";
                                $result = $conn->query($sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><input type="checkbox" name="select_ids[]" value="<?php echo $row['id']; ?>"></td>   
                                <td><?php echo $row["date_registered"]?></th>
                                <td><?php echo $row["user_type"]?></th>
                                <td><?php echo $row["last_name"]?></td>
                                <td><?php echo $row["first_name"]?></td>
                                <td><?php echo $row["middle_name"]?></td>
                                <td><?php echo $row["birthdate"]?></th>
                                <td><?php echo calculateAge($row['birthdate']); ?></td>
                                <td><?php echo $row["gender"]?></td>
                                <td><?php echo $row["address"]?></td>
                                <td><?php echo $row["occupation"]?></th>
                                <td><?php echo $row["civil_status"]?></td>
                                <td><?php echo $row["citizenship"]?></td>
                                <td><?php echo $row["contact_number"]?></td>
                                <td><?php echo $row["email"]?></td>                            
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>                
                </div>
            </div> 
        </section>
        <section>
            <div class="modal fade" id="approval-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content p-3"> 
                        <div class="modal-header">
                            <p class="modal-title fs-3">PLEASE CONFIRM</p>
                        </div>
                        <div class="modal-body text-center">
                            <?php 
                                $select_ids = $_POST['select_ids'];
                                $extract_id = implode(',', $select_ids);
                                
                                $sql = "SELECT CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name, id 
                                        FROM `users-table`
                                        WHERE id IN ($extract_id)";
                                $result = $conn->query($sql);
                            ?>
                            <div class="fw-bold mb-2">Are you sure you want to approve the following users?</div>
                            <?php
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <div><?php echo $row ['full_name']; ?></div>
                            <?php } ?>                       
                            <div class="text-center mt-3">
                                <input type="hidden" id="selected_ids" name="selected_ids" value="<?php echo $extract_id; ?>">
                                <button type="submit" id="approve_button" name="approve_button" class="btn btn-success">Approve</button>
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </section>
    </form>
    
    <script>
        function approvalModal() {
            var myModal = new bootstrap.Modal(document.getElementById('approval-modal'));
            myModal.show();
        }

        // Wait for the document to finish loading, then call the function
        document.addEventListener("DOMContentLoaded", function() {
            approvalModal(); // Call the function to open the modal
        });
    </script>
    
    
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