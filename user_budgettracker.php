<?php
    session_start();

    // Check if user is logged in
        if (isset($_SESSION['email']) && $_SESSION['user_type'] == 'user') { 
        require "db_conn.php";

    // Fetch monthly income and expense data
    $monthlyDataQuery = "
        SELECT 
            DATE_FORMAT(date_posted, '%M') as month, 
            SUM(IF(type_of_data IN ('loc_revenue', 'gov_allocation', 'grant_donation'), amount, 0)) AS total_income, 
            SUM(IF(type_of_data IN ('cap_outlay', 'per_services', 'maintenance', 'spec_purpose'), amount, 0)) AS total_expense 
            FROM `budgettracker-table` 
            GROUP BY DATE_FORMAT(date_posted, '%Y-%m') 
            ORDER BY DATE_FORMAT(date_posted, '%Y-%m')
        ";
    $monthlyDataResult = $conn->query($monthlyDataQuery);
    $monthlyDataPoints = array();
    while ($row = mysqli_fetch_assoc($monthlyDataResult)) {
        $month = $row['month'];
        $net_budget = $row['total_income'] - $row['total_expense'];
        $monthlyDataPoints[] = array("label" => $month, "y" => $net_budget);
    }

    // Prepare income and expense data points
    $totalIncomeQuery = "
        SELECT 
            SUM(IF(`type_of_data` = 'loc_revenue', amount, 0)) AS loc_revenue, 
            SUM(IF(`type_of_data` = 'gov_allocation', amount, 0)) AS gov_allocation, 
            SUM(IF(`type_of_data` = 'grant_donation', amount, 0)) AS grant_donation, 
            SUM(amount) AS total_income 
            FROM `budgettracker-table` 
            WHERE `type_of_data` IN ('loc_revenue', 'gov_allocation', 'grant_donation')
        ";
    $totalIncomeResult = $conn->query($totalIncomeQuery);
    $totalIncomeRow = mysqli_fetch_assoc($totalIncomeResult);
    $totalIncome = $totalIncomeRow['total_income'];

    $incomeQuery = "
            SELECT `type_of_data`, SUM(amount) AS total_amount 
            FROM `budgettracker-table` 
            WHERE `type_of_data` IN ('loc_revenue', 'gov_allocation', 'grant_donation') 
            GROUP BY `type_of_data`
        ";
    $incomeResult = $conn->query($incomeQuery);
    $incomeDataPoints = array();
    while ($row = mysqli_fetch_assoc($incomeResult)) {
        switch($row['type_of_data']) {
            case 'loc_revenue':
                $label = 'Local Revenue';
                break;
            case 'gov_allocation':
                $label = 'Government Allocation';
                break;
            case 'grant_donation':
                $label = 'Grants and Donations';
                break;
        }
        $percentage = ($row['total_amount'] / $totalIncome) * 100;
        $incomeDataPoints[] = array("label" => $label, "y" => $percentage);
    }

    $totalExpenseQuery = "
        SELECT 
            SUM(IF(`type_of_data` = 'cap_outlay', amount, 0)) AS cap_outlay, 
            SUM(IF(`type_of_data` = 'per_services', amount, 0)) AS per_services, 
            SUM(IF(`type_of_data` = 'maintenance', amount, 0)) AS maintenance, 
            SUM(IF(`type_of_data` = 'spec_purpose', amount, 0)) AS spec_purpose,
            SUM(amount) AS total_expense 
            FROM `budgettracker-table` 
            WHERE `type_of_data` IN ('cap_outlay', 'per_services', 'maintenance', 'spec_purpose')
    ";
    $totalExpenseResult = $conn->query($totalExpenseQuery);
    $totalExpenseRow = mysqli_fetch_assoc($totalExpenseResult);
    $totalExpense = $totalExpenseRow['total_expense'];

    $expenseQuery = "
        SELECT `type_of_data`, SUM(amount) AS total_amount 
            FROM `budgettracker-table` 
            WHERE `type_of_data` IN ('cap_outlay', 'per_services', 'maintenance', 'spec_purpose') 
            GROUP BY `type_of_data`
    ";
    $expenseResult = $conn->query($expenseQuery);
    $expenseDataPoints = array();
    while ($row = mysqli_fetch_assoc($expenseResult)) {
        switch($row['type_of_data']) {
            case 'cap_outlay':
                $label = 'Capital Outlay';
                break;
            case 'per_services':
                $label = 'Personnel Services';
                break;
            case 'maintenance':
                $label = 'Maintenance and Other Operating Expenses';
                break;
            case 'spec_purpose':
                $label = 'Special Purpose Appropriations';
                break;
        }
        $percentage = ($row['total_amount'] / $totalExpense) * 100;
        $expenseDataPoints[] = array("label" => $label, "y" => $percentage);
    }

    // Fetch detailed data for income types
    $incomeDetailsQuery = "
    SELECT `type_of_data`, `date_posted`, `amount`, `description`
    FROM `budgettracker-table`
    WHERE `type_of_data` IN ('loc_revenue', 'gov_allocation', 'grant_donation')
    ORDER BY `type_of_data`, `date_posted`
    ";
    $incomeDetailsResult = $conn->query($incomeDetailsQuery);
    $incomeDetails = array();
    while ($row = mysqli_fetch_assoc($incomeDetailsResult)) {
        $incomeDetails[$row['type_of_data']][] = $row;
    }

    // Fetch detailed data for expense types
    $expenseDetailsQuery = "
        SELECT `type_of_data`, `date_posted`, `amount`, `description`
        FROM `budgettracker-table`
        WHERE `type_of_data` IN ('cap_outlay', 'per_services', 'maintenance', 'spec_purpose')
        ORDER BY `type_of_data`, `date_posted`
    ";
    $expenseDetailsResult = $conn->query($expenseDetailsQuery);
    $expenseDetails = array();
    while ($row = mysqli_fetch_assoc($expenseDetailsResult)) {
        $expenseDetails[$row['type_of_data']][] = $row;
    }

    $type_of_data_sums = array();

// Calculate the sum of each type_of_data
    $type_of_data_query = "
    SELECT type_of_data, SUM(amount) AS sum_amount
    FROM `budgettracker-table`
    GROUP BY type_of_data
";
    $type_of_data_result = $conn->query($type_of_data_query);
    while ($row = mysqli_fetch_assoc($type_of_data_result)) {
    $type_of_data_sums[$row['type_of_data']] = $row['sum_amount'];
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
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

      <!-- Include the JSON data for the JavaScript to use -->
    <script>
        const incomeDetails = <?php echo json_encode($incomeDetails, JSON_HEX_TAG); ?>;
        const expenseDetails = <?php echo json_encode($expenseDetails, JSON_HEX_TAG); ?>;
    </script>
    
    <script>
    window.onload = function() {
        CanvasJS.addColorSet("greenShades", ["#2F4F4F", "#008080", "#2E8B57", "#3CB371"]);

        // Net Budget (Monthly)
        var chart1 = new CanvasJS.Chart("balance_chart", {
            colorSet: "greenShades",
            animationEnabled: true,
            title: { text: "Net Budget (Monthly)", fontFamily: "Arial", fontSize: 20, fontStyle: "Bold" },
            axisY: { title: "Net Budget (in PHP)", includeZero: true, labelFontSize: 20, prefix: "₱" },
            axisX: { labelFontSize: 16, interval: 1 },
            data: [{ type: "bar", yValueFormatString: "₱#,##0", indexLabel: "{y}", indexLabelPlacement: "inside", indexLabelFontWeight: "bolder", indexLabelFontColor: "white", indexLabelFontSize: 16, dataPoints: <?php echo json_encode($monthlyDataPoints, JSON_NUMERIC_CHECK); ?> }]
        });

        // Income Chart
        var chart3 = new CanvasJS.Chart("income_chart", {
            theme: "light2",
            colorSet: "greenShades",
            animationEnabled: true,
            title: { text: "Monthly Income", fontFamily: "Arial", fontSize: 20, fontStyle: "bold" },
            data: [{ 
                type: "doughnut",
                innerRadius: 70,
                indexLabel: "{label} - {y}",
                yValueFormatString: "#,##0.0\"%\"",
                showInLegend: true,
                legendText: "{label} : {y}",
                dataPoints: <?php echo json_encode($incomeDataPoints, JSON_NUMERIC_CHECK); ?>,
                click: function(e) {
                    var modalId;
                    var modalContentId;
                    var details;

                    switch(e.dataPoint.label) {
                        case "Local Revenue":
                            modalId = "chartModal_local";
                            modalContentId = "modalContent_local";
                            details = incomeDetails['loc_revenue'];
                            break;
                        case "Government Allocation":
                            modalId = "chartModal_government";
                            modalContentId = "modalContent_government";
                            details = incomeDetails['gov_allocation'];
                            break;
                        case "Grants and Donations":
                            modalId = "chartModal_grants";
                            modalContentId = "modalContent_grants";
                            details = incomeDetails['grant_donation'];
                            break;
                    }

                    if (modalId) {
                        var modalContent = document.getElementById(modalContentId);
                        modalContent.innerHTML = generateTableContent(details);
                        var chartModal = new bootstrap.Modal(document.getElementById(modalId));
                        chartModal.show();
                    }
                }
            }]
        });

        // Expense Chart
        var chart2 = new CanvasJS.Chart("expense_chart", {
            theme: "light2",
            colorSet: "greenShades",
            animationEnabled: true,
            title: { text: "Monthly Expenses", fontFamily: "Arial", fontSize: 20, fontStyle: "Bold" },
            data: [{ type: "doughnut", innerRadius: 70, indexLabel: "{label} - {y}", yValueFormatString: "#,##0.0\"%\"", showInLegend: true, legendText: "{label} : {y}", dataPoints: <?php echo json_encode($expenseDataPoints, JSON_NUMERIC_CHECK); ?>,
                click: function(e) {
                    var modalId;
                    var modalContentId;
                    var details;

                    switch(e.dataPoint.label) {
                        case "Capital Outlay":
                            modalId = "chartModal_capital";
                            modalContentId = "modalContent_capital";
                            details = expenseDetails['cap_outlay'];
                            break;
                        case "Personnel Services":
                            modalId = "chartModal_personnel";
                            modalContentId = "modalContent_personnel";
                            details = expenseDetails['per_services'];
                            break;
                        case "Maintenance and Other Operating Expenses":
                            modalId = "chartModal_maintenance";
                            modalContentId = "modalContent_maintenance";
                            details = expenseDetails['maintenance'];
                            break;
                        case "Special Purpose Appropriations":
                            modalId = "chartModal_special";
                            modalContentId = "modalContent_special";
                            details = expenseDetails['spec_purpose'];
                            break;
                    }

                    if (modalId) {
                        var modalContent = document.getElementById(modalContentId);
                        modalContent.innerHTML = generateTableContent(details);
                        var chartModal = new bootstrap.Modal(document.getElementById(modalId));
                        chartModal.show();
                    }
                }
            }]
        });

        chart1.render();
        chart2.render();
        chart3.render();
    }

    function generateTableContent(details) {
        if (!details || details.length === 0) return "<p>No data available.</p>";

        var table = '<table class="table table-borderless"><thead><tr><th>Description</th><th>Amount</th></tr></thead><tbody>';
        details.forEach(function(detail) {
            table += `<tr><td class="p-0">${detail.description}</td><td class="fw-bold p-0">₱${detail.amount}.00</td></tr>`;
        });
        table += '</tbody></table>';

        return table;
    }
</script>

</head>
<body>
<?php require "includes/user_navbar.php"?>
    <section class="container-fluid text-center">
        <div class="display-5">BUDGET TRACKER</div>
            <div class="row px-2">
                <div class="col-xl-8 p-2 rounded chart-container"> 
                    <div id="balance_chart"></div>           
                </div>
            <div class="col-xl-4">
                <div class="mb-2 p-2 rounded chart-container">
                    <div id="income_chart"></div> 
                </div>
                <div class="p-2 rounded chart-container">
                    <div id="expense_chart"></div> 
                </div>
            </div>
        </div>
    </section> 
    <!-- Modals for Income Chart -->
<!-- Modals for Income Chart -->
<div class="modal fade" id="chartModal_local" tabindex="-1" aria-labelledby="chartModalLabel_local" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chartModalLabel_local">Local Revenue Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent_local">Loading...</div>
            <div class="modal-footer">
                <div>Total: ₱<?= isset($type_of_data_sums['loc_revenue']) ? number_format($type_of_data_sums['loc_revenue'], 2) : '0.00' ?></div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="chartModal_government" tabindex="-1" aria-labelledby="chartModalLabel_government" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chartModalLabel_government">Government Allocation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent_government">Loading...</div>
            <div class="modal-footer">
                <div>Total: ₱<?= isset($type_of_data_sums['gov_allocation']) ? number_format($type_of_data_sums['gov_allocation'], 2) : '0.00' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chartModal_grants" tabindex="-1" aria-labelledby="chartModalLabel_grants" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chartModalLabel_grants">Grants and Donations Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent_grants">Loading...</div>
            <div class="modal-footer">
                <div>Total: ₱<?= isset($type_of_data_sums['grant_donation']) ? number_format($type_of_data_sums['grant_donation'], 2) : '0.00' ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Expense Chart -->
<div class="modal fade" id="chartModal_capital" tabindex="-1" aria-labelledby="chartModalLabel_capital" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chartModalLabel_capital">Capital Outlay Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent_capital">Loading...</div>
            <div class="modal-footer">
                <div>Total: ₱<?= isset($type_of_data_sums['cap_outlay']) ? number_format($type_of_data_sums['cap_outlay'], 2) : '0.00' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chartModal_personnel" tabindex="-1" aria-labelledby="chartModalLabel_personnel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chartModalLabel_personnel">Personnel Services Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent_personnel">Loading...</div>
            <div class="modal-footer">
                <div>Total: ₱<?= isset($type_of_data_sums['per_services']) ? number_format($type_of_data_sums['per_services'], 2) : '0.00' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chartModal_maintenance" tabindex="-1" aria-labelledby="chartModalLabel_maintenance" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chartModalLabel_maintenance">Maintenance and Other Operating Expenses Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent_maintenance">Loading...</div>
            <div class="modal-footer">
                <div>Total: ₱<?= isset($type_of_data_sums['maintenance']) ? number_format($type_of_data_sums['maintenance'], 2) : '0.00' ?></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chartModal_special" tabindex="-1" aria-labelledby="chartModalLabel_special" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chartModalLabel_special">Special Purpose Appropriations Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent_special">Loading...</div>
            <div class="modal-footer">
                <div>Total: ₱<?= isset($type_of_data_sums['spec_purpose']) ? number_format($type_of_data_sums['spec_purpose'], 2) : '0.00' ?></div>
            </div>
        </div>
    </div>
</div>

    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('chartModal_local'));
        var myModal2 = new bootstrap.Modal(document.getElementById('chartModal_government'));
        var myModal3 = new bootstrap.Modal(document.getElementById('chartModal_grants'));
        var myModal4 = new bootstrap.Modal(document.getElementById('chartModal_capital'));
        var myModal5 = new bootstrap.Modal(document.getElementById('chartModal_personnel'));
        var myModal6 = new bootstrap.Modal(document.getElementById('chartModal_maintenance'));
        var myModal7 = new bootstrap.Modal(document.getElementById('chartModal_special'));
    </script>

</body>
</html>
<?php 
} else {
    header("Location: login.php");
    exit();
}
?>