<?php 
session_start();

// Check if user is logged in
if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'admin') { 
    require "db_conn.php";

    $email_loggedin = $_SESSION['email'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $income_expense = $_POST['income_expense'];
        $type_of_data = $_POST['type_of_data'];
        $description = $_POST['description'];
        $amount = $_POST['amount'];
        $posted_by = $email_loggedin;

        $sql = "INSERT INTO `budgettracker-table` 
                (`id`,`income_expense`, `type_of_data`, `description`, `amount`, `posted_by`) 
                VALUES 
                (NULL, '$income_expense', '$type_of_data', '$description', '$amount', '$posted_by')";

        if ($conn->query($sql) === TRUE) {
            echo "Update successful.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Calculate total income
    $sql_monthly_income = "SELECT SUM(amount) AS total_monthly_income 
                      FROM `budgettracker-table` 
                      WHERE income_expense='income' 
                      AND MONTH(date_posted) = MONTH(CURDATE()) 
                      AND YEAR(date_posted) = YEAR(CURDATE())";
    $result_monthly_income = $conn->query($sql_monthly_income);
    $total_monthly_income = $result_monthly_income->fetch_assoc()['total_monthly_income'];
    /* $sql_income = "SELECT SUM(amount) AS total_income FROM `budgettracker-table` WHERE income_expense='income'";
    $result_income = $conn->query($sql_income);
    $total_income = $result_income->fetch_assoc()['total_income'];
 */
    // Calculate total expense
    $sql_monthly_expense = "SELECT SUM(amount) AS total_monthly_expense 
                       FROM `budgettracker-table` 
                       WHERE income_expense='expense' 
                       AND MONTH(date_posted) = MONTH(CURDATE()) 
                       AND YEAR(date_posted) = YEAR(CURDATE())";
    $result_monthly_expense = $conn->query($sql_monthly_expense);
     $total_monthly_expense = $result_monthly_expense->fetch_assoc()['total_monthly_expense'];
    /* $sql_expense = "SELECT SUM(amount) AS total_expense FROM `budgettracker-table` WHERE income_expense='expense'";
    $result_expense = $conn->query($sql_expense);
    $total_expense = $result_expense->fetch_assoc()['total_expense']; */
    $total_monthly_balance = $total_monthly_income - $total_monthly_expense;
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
        <div class="display-5">BUDGET TRACKER</div> 
        <div class="px-lg-5">
            <div class="row pt-5">
                <!-- DIV FOR TABLE -->
                <div class="col-lg-7">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">DATE POSTED</th>
                                    <th scope="col">TYPE OF DATA</th>
                                    <th scope="col">DESCRIPTION</th>
                                    <th scope="col">AMOUNT</th>
                                    <th scope="col">POSTED BY</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = "SELECT *
                                            FROM `budgettracker-table`";
                                    $result = $conn->query($sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $row["date_posted"] ?></td>
                                    <td><?php echo $row["type_of_data"] ?></td>
                                    <td><?php echo $row["description"] ?></td>
                                    <td><?php echo $row["amount"] ?></td>
                                    <td><?php echo $row["posted_by"] ?></td>                                   
                                </tr>   
                                <?php } ?>                            
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- DIV FOR ACCORDION -->               
                <div class="col-lg-5 accordion" id="budgettracker_accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading_balance">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_balance" aria-expanded="true" aria-controls="collapse_balance">
                            Monthly Balance
                        </button>
                        </h2>
                        <div id="collapse_balance" class="accordion-collapse collapse show" aria-labelledby="collapse_balance">
                            <div class="accordion-body">
                                <!-- ACTUAL AMOUNT OF MONTHLY BALANCE -->
                                <div class="display-5 mb-2"><?php echo number_format($total_monthly_balance, 2); ?></div> 
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading_income">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_income" aria-expanded="false" aria-controls="collapse_income">
                            Monthly Income
                        </button>
                        </h2>
                        <div id="collapse_income" class="accordion-collapse collapse" aria-labelledby="collapse_income">
                            <div class="accordion-body">
                                 <!-- ACTUAL AMOUNT OF MONTHLY INCOME -->
                                <div class="display-5 mb-2" id="total-income"><?php echo number_format($total_monthly_income, 2); ?></div>
                                <!-- FORM FOR ENTERING NEW DATA -->
                                <div class="">
                                    <form action="" method="post">
                                        <div class="row text-start" id="income-container">
                                            <div class="form-group mb-3 col-md-4">
                                                <label for="type_of_data" class="form-label">Type of Data:</label>
                                                <select class="form-select" name="type_of_data" id="type_of_data">
                                                    <option disabled selected>Please select...</option>
                                                    <option value="loc_revenue">Local Revenue</option>
                                                    <option value="gov_allocation">Government Allocation</option>
                                                    <option value="grant_donation">Grants and Donations</option> 
                                                </select>
                                            </div>
                                            <div class="form-group mb-3 col-md-5">
                                                <label for="description" class="form-label">Description:</label>
                                                <input type="text" class="form-control" name="description" id="description">
                                            </div>
                                            <div class="form-group mb-3 col-md-2">
                                                <label for="amount" class="form-label">Amount:</label>
                                                <input type="text" class="form-control" name="amount" id="amount">
                                            </div>
                                            <div class="form-group mb-3 col-md-1 d-flex align-items-end text-center"> 
                                                <button type="button" class="btn btn-success" id="add-income"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <button type="submit" class="btn btn-outline-success w-100">Submit</button>
                                        </div>
                                        <div class="d-none">
                                            <input type="text" name="income_expense" id="income_expense" value="income">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading_expense">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_expense" aria-expanded="false" aria-controls="collapse_expense">
                            Monthly Expense
                        </button>
                        </h2>
                        <div id="collapse_expense" class="accordion-collapse collapse" aria-labelledby="collapse_expense">
                            <div class="accordion-body">
                                 <!-- ACTUAL AMOUNT OF MONTHLY EXPENSE -->
                                <div class="display-5 mb-2" id="total-expense"><?php echo number_format($total_monthly_expense, 2); ?></div>
                                <!-- FORM FOR ENTERING NEW DATA -->
                                <div class="">
                                    <form action="" method="post">
                                        <div class="row text-start" id="expense-container">
                                            <div class="form-group mb-3 col-md-4">
                                                <label for="type_of_data" class="form-label">Type of Data:</label>
                                                <select class="form-select" name="type_of_data" id="type_of_data">
                                                    <option disabled selected>Please select...</option>
                                                    <option value="per_services">Personnel Services</option>
                                                    <option value="maintenance">Maintenance and other operating expenses</option>
                                                    <option value="cap_outlay">Capital Outlay</option>
                                                    <option value="spec_purpose">Special Purpose Appropriations</option> 
                                                </select>
                                            </div>
                                            <div class="form-group mb-3 col-md-5">
                                                <label for="description" class="form-label">Description:</label>
                                                <input type="text" class="form-control" name="description" id="description">
                                            </div>
                                            <div class="form-group mb-3 col-md-2">
                                                <label for="amount" class="form-label">Amount:</label>
                                                <input type="text" class="form-control" name="amount" id="amount">
                                            </div>
                                            <div class="form-group mb-3 col-md-1 d-flex align-items-end text-center"> 
                                                <button type="button" class="btn btn-success" id="add-expense"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <button type="submit" class="btn btn-outline-success w-100">Submit</button>
                                        </div>
                                        <div class="d-none">
                                            <input type="text" name="income_expense" id="income_expense" value="expense">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
    <!-- FOR ADDITIONAL INPUT BOX ON LIVE BUDGET TRACKER -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#add-income").click(function(){
                $("#income-container").append('<div class="form-group mb-3 col-md-4"><select class="form-select" id="type_of_data"><option disabled selected>Please select...</option><option value="loc_revenue">Local Revenue</option><option value="gov_allocation">Government Allocation</option><option value="grant_donation">Grants and Donations</option></select></div><div class="form-group mb-3 col-md-5"><input type="text" class="form-control" id="description"></div><div class="form-group mb-3 col-md-2"><input type="text" class="form-control" id="amount"></div><div class="form-group mb-3 col-md-1 d-flex align-items-end"><button type="button" class="btn btn-light"><i class="fa-solid fa-minus"></i></button><div>');
            });
        });
        $(document).ready(function(){
            $("#add-expense").click(function(){
                $("#expense-container").append('<div class="form-group mb-3 col-md-4"><select class="form-select" id="type_of_data"><option disabled selected>Please select...</option><option value="per_services">Personnel Services</option><option value="maintenance">Maintenance and other operating expenses</option><option value="cap_outlay">Capital Outlay</option><option value="spec_purpose">Special Purpose Appropriations</option></select></div><div class="form-group mb-3 col-md-5"><input type="text" class="form-control" id="description"></div><div class="form-group mb-3 col-md-2"><input type="text" class="form-control" id="amount"></div><div class="form-group mb-3 col-md-1 d-flex align-items-end"><button type="button" class="btn btn-light"><i class="fa-solid fa-minus"></i></button><div>');
            });
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
