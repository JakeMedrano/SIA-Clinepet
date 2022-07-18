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

    $activeNav = 'dashboard';

    $stmt = $conn->prepare('SELECT SUM(revenue) AS revenue, SUM(profit) AS profit FROM transactions WHERE status = ?');
    $stmt->execute([1]);
    $sales = $stmt->fetch();


    $stmt = $conn->prepare("SELECT SUM(revenue) AS revenue, SUM(profit) AS profit FROM transactions WHERE day(transaction_date)=day(now()) AND status = ?");
    $stmt->execute([1]);
    $todaySales = $stmt->fetch();


    $stmt = $conn->prepare('SELECT SUM(current_stock) AS stocks, SUM(current_stock * cost) AS cost FROM products');
    $stmt->execute();
    $inventory = $stmt->fetch();


    $stmt = $conn->prepare('SELECT sum(revenue) as total_revenue, sum(profit) as total_profit, DAYNAME(transaction_date) as day FROM transactions WHERE week(transaction_date)=week(now()) AND status = "1" GROUP BY day');
    $stmt->execute();
    $weekSales = $stmt->fetchAll();


    $stmt = $conn->prepare('SELECT product_name, sum(quantity) as sold_qty FROM transaction_item WHERE type = "item" AND STATUS = "1" GROUP BY product_name ORDER BY sold_qty DESC LIMIT 20');
    $stmt->execute();
    $topSolds = $stmt->fetchAll();


    $stmt = $conn->prepare('SELECT * FROM products WHERE type = "item" AND current_stock >= 20');
    $stmt->execute();
    $highStocks = $stmt->fetchAll();


    $stmt = $conn->prepare('SELECT * FROM products WHERE type = "item" AND current_stock <= 5 AND current_stock > 0 ');
    $stmt->execute();
    $lowStocks = $stmt->fetchAll();


    $stmt = $conn->prepare('SELECT * FROM products WHERE type = "item" AND current_stock <= 0 ');
    $stmt->execute();
    $zeroStocks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clinipets POS and Reservation</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/dashboard.css">
</head>
<body>

    <?php include 'nav-top.php'; ?>

    <div class="container-fluid">
        <div class="row">
            
            <?php include 'nav-side.php'; ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="card card-body">
                            <strong>Today Revenue</strong>
                            <h6><?=number_format($todaySales['revenue'],2); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body">
                            <strong>Today Profit</strong>
                            <h6><?=number_format($todaySales['profit'],2); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body">
                            <strong>Total Revenue</strong>
                            <h6><?=number_format($sales['revenue'],2); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body">
                            <strong>Total Profit</strong>
                            <h6><?=number_format($sales['profit'],2); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body">
                            <strong>Inventory Items</strong>
                            <h6><?=number_format($inventory['stocks']); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body">
                            <strong>Inventory Cost</strong>
                            <h6><?=number_format($inventory['cost'],2); ?></h6>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="card card-body">
                            <h5>Top 20 Best Selling Items</h5>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Sold Qty</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topSolds as $key => $topSold): ?>
                                    <tr>
                                        <td><a href="javascript:void(0);"><?=$topSold['product_name']; ?></a></td>
                                        <td><?=$topSold['sold_qty']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-body">
                            <h5> High Stock Items </h5>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Stocks</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($highStocks as $key => $highStock): ?>
                                    <tr>
                                        <td><a href="javascript:void(0);"><?=$highStock['name']; ?></a></td>
                                        <td><?=$highStock['current_stock']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-body">
                            <h5> Low Stock Items </h5>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Stocks</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lowStocks as $key => $lowStock): ?>
                                    <tr>
                                        <td><a href="javascript:void(0);"><?=$lowStock['name']; ?></a></td>
                                        <td><?=$lowStock['current_stock']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-body">
                            <h5> Zero Stock Items </h5>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Stocks</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($zeroStocks as $key => $zeroStock): ?>
                                    <tr>
                                        <td><a href="javascript:void(0);"><?=$zeroStock['name']; ?></a></td>
                                        <td><?=$zeroStock['current_stock']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>              
            </main>
        </div>
    </div>




    <script type="text/javascript" src="../assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../assets/js/feather.min.js"></script>
    <script type="text/javascript" src="../assets/js/chart.min.js"></script>
    <script>
        $(function() {
            feather.replace();
        });
    </script>
</body>
</html>
