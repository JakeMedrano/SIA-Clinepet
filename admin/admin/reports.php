<?php
    session_start();
    
    include '../db_config.php';
    

    if (isset($_SESSION['user'])) {  

        $isLogged = true;
        $userData = $_SESSION['user'];

    } else {

        $isLogged = false;
        $userData = [];

        header("location:index.php");

    }

    $activeNav = 'reports';

    $stmt = $conn->prepare('SELECT transaction_date FROM transactions GROUP BY YEAR(transaction_date) ORDER BY transaction_date DESC');
    $stmt->execute();
    $transaction_years = $stmt->fetchAll();

    $day = $month = $year = '';
    $transactions = [];

    if (isset($_GET['day']) && isset($_GET['month']) && isset($_GET['year'])) {

        $day = $_GET['day'];
        $month = $_GET['month'];
        $year = $_GET['year'];

        if ($month == 'all') {

            $query = $conn->prepare("SELECT * FROM transactions WHERE YEAR(transaction_date)=?  ORDER BY transaction_date DESC");
            $query->execute([$year]);
            $transactions = $query->fetchAll();

        } else {

            if (in_array($month, range(1, 12))) {

                $query = $conn->prepare("SELECT * FROM transactions WHERE DAY(transaction_date)=? AND MONTH(transaction_date)=? AND YEAR(transaction_date)=?  ORDER BY transaction_date DESC");
                $query->execute([$day, $month, $year]);
                $transactions = $query->fetchAll();

            } else {

                header("location=reports.php");

            }
            
        }

    } else {

        $query = $conn->prepare("SELECT * FROM transactions WHERE YEAR(transaction_date) = YEAR(now())");
        $query->execute();
        $transactions = $query->fetchAll();

    }


    $filter_year = $filter_month = $filter_day = '';
    if (isset($_GET['filter_year']) && isset($_GET['filter_month']) && isset($_GET['filter_day'])) { 

        $filter_year = $_GET['filter_year'];
        $filter_month = $_GET['filter_month'];
        $filter_day = $_GET['filter_day'];


        $conditions = [];


        if ($filter_year != 'all') {
            $conditions['YEAR(transaction_date)'] = $filter_year;
        }

        if ($filter_month != 'all') {
            $conditions['MONTH(transaction_date)'] = $filter_month;
        }

        if ($filter_day != 'all') {
            $conditions['DAY(transaction_date)'] = $filter_day;
        }

        $where = '';
        foreach ($conditions as $column => $value) {
            $where .= " AND {$column} = {$value}";
        }
        
        $stmt = $conn->prepare("SELECT * FROM transactions WHERE (ref_number <> '') {$where} ORDER BY id DESC");
        $stmt->execute(); 
        $transactions = $stmt->fetchAll();

    } else {

        $query = $conn->prepare("SELECT * FROM transactions WHERE YEAR(transaction_date) = YEAR(now())");
        $query->execute();
        $transactions = $query->fetchAll();

    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clinipets POS and Reservation</title>
    <?php include 'theme-css.php'; ?>
</head>
<body>

    <?php include 'nav-top.php'; ?>


    <div class="container-fluid">
        <div class="row">
            
            <?php include 'nav-side.php'; ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="pt-3">
                    <h1 class="h2">Sales Reports</h1>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date Time</th>
                                            <th>Customer</th>
                                            <th>Items</th>
                                            <th>Profit</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($transactions as $key => $transaction): ?>
                                        <tr>
                                            <td><?=$transaction['id']; ?></td>
                                            <td><?=date("Y-M-d (g:i:s a)", strtotime($transaction['transaction_date'])); ?></td>
                                            <td><?=$transaction['customer_name']==null?'Walk-in':$transaction['customer_name']; ?></td>
                                            <td>
                                                <?php 
                                                    $transaction_id = $transaction['id'];
                                                    $items = $conn->prepare("SELECT * FROM transaction_item WHERE transaction_id = ?");
                                                    $items->execute([$transaction_id]);
                                                    echo $items->rowCount(); 
                                                ?>
                                            </td>
                                            <td><?=$transaction['profit']; ?></td>
                                            <td><?=$transaction['revenue']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    

    <?php include 'theme-js.php'; ?>
    <script type="text/javascript">
    $(function() {

        $('#export-report').on('click', function() {
            var fyear = $('select.filter-year').val();
            var fmonth = $('select.filter-month').val();
            var fday = $('select.filter-day').val();
            window.location="reports-pdf.php?filter_year="+fyear+"&filter_month="+fmonth+"&filter_day="+fday;
        });

        $('button#filter-report').on('click', function() {
            var fyear = $('select.filter-year').val();
            var fmonth = $('select.filter-month').val();
            var fday = $('select.filter-day').val();

            window.location="reports.php?filter_year="+fyear+"&filter_month="+fmonth+"&filter_day="+fday;
        });

        $('button#filter-clear').on('click', function() {
            window.location="reports.php";
        });
        
    });
    </script>
</body>
</html>
