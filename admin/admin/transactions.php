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
    // SESSION CHECKER IF LOGGED IN OR NOT

    $activeNav = 'transactions';


    // LOAD FOR FORM SELECT AND TABLE

    $stmt = $conn->prepare('SELECT transaction_date FROM transactions GROUP BY YEAR(transaction_date) ORDER BY transaction_date DESC');
    $stmt->execute();
    $transaction_years = $stmt->fetchAll();
    // LOAD FOR FORM SELECT AND TABLE


    // CHECK IF CREATE FORM OR EDIT FORM
    $action = '';
    if (isset($_GET['action']) && isset($_GET['id'])) {

        $id = $_GET['id'];
        $action = $_GET['action'];

        if ($_GET['action'] == 'void') {

            //$conn->prepare("DELETE FROM transactions WHERE id = ?")->execute([$id]);
            //$conn->prepare("DELETE FROM transaction_item WHERE transaction_id = ?")->execute([$id]);
            $stmt= $conn->prepare("UPDATE transactions SET status = 0 WHERE id = ?");
            $stmt->execute([$id]);
            
            $stmt= $conn->prepare("UPDATE transaction_item SET status = 0 WHERE transaction_id = ?");
            $stmt->execute([$id]);

            $query = $conn->prepare("SELECT * FROM transaction_item WHERE transaction_id = ?");
            $query->execute([$id]);
            $voidItems = $query->fetchAll();

            foreach ($voidItems as $key => $voidItem) {
                 
                $product_id = $voidItem['product_id'];
                $return_qty = $voidItem['quantity'];


                $query = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $query->execute([$product_id]);
                $voidProduct = $query->fetch();

                if ($voidProduct) {

                    $old_qty = $voidProduct['current_stock'];
                    $new_qty = $old_qty + $return_qty;

                    // UPDATE PRODUCT CURRENT STOCK
                    $stmt= $conn->prepare("UPDATE products SET current_stock = ? WHERE id = ?");
                    $stmt->execute([$new_qty, $product_id]);


                    // CREATE INVENTORY HISTORY
                    if ($voidProduct['type'] == 'item') {
                        $stmt = $conn->prepare("INSERT INTO inventory (product_id, old_qty, new_qty, note) VALUES (?,?,?,?)");
                        $stmt->execute([$product_id, $old_qty, $new_qty, 'Void Transaction: '.$id]);   
                    }
                }

            }

            echo '<script>alert("Transaction Voided"); window.location="transactions.php";</script>';
            
        } elseif ($_GET['action'] == 'view') {

            $formTitle = "VIEW TRANSACTION";

            $query = $conn->prepare("SELECT * FROM transactions WHERE id = ?");
            $query->execute([$id]);
            $selectedTransaction = $query->fetch();

            $query = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $query->execute([$selectedTransaction['user_id']]);
            $staff = $query->fetch();

            if (!$selectedTransaction) {
                header("location:transactions.php");
            }
        } elseif ($_GET['action'] == 'delete') {

            $conn->prepare("DELETE FROM transactions WHERE id = ?")->execute([$_GET['id']]);
            $conn->prepare("DELETE FROM transaction_item WHERE transaction_id = ?")->execute([$_GET['id']]);
            echo '<script>alert("Transaction deleted"); window.location="transactions.php";</script>';   
        }

    } else {

        $id = 0;
        $formTitle = "SELECT TRANSACTION FIRST";

    }
    // CHECK IF CREATE FORM OR EDIT FORM

    $filter_year = $filter_month = $filter_day = $filter_customer = '';
    if (isset($_GET['filter_year']) && isset($_GET['filter_month']) && isset($_GET['filter_day'])) { 

        $filter_year = $_GET['filter_year'];
        $filter_month = $_GET['filter_month'];
        $filter_day = $_GET['filter_day'];
        $filter_customer = $_GET['filter_customer'];

        $whereCustomer = "customer_name = 'Walk-in'";
        if ($filter_customer != '') {
            $whereCustomer = "customer_name = {$filter_customer}";
        }

        $conditions = [];

        if ($filter_customer != '') {
            $conditions['customer_name'] = "'%".$filter_customer."%'";
        }

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

        $stmt = $conn->prepare("SELECT * FROM transactions ORDER BY id DESC");
        $stmt->execute(); 
        $transactions = $stmt->fetchAll();

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
                    <h1 class="h2">Inventory (Items Only)</h1>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                     <table class="table transaction_list">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>Total Amount</th>
                                                <th>Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            foreach ($transactions as $key => $transaction): 
                                                $user_id = $transaction['user_id'];
                                                $query = $conn->prepare("SELECT * FROM users WHERE id = ?");
                                                $query->execute([$user_id]);
                                                $user = $query->fetch();

                                                $transaction_id = $transaction['id'];
                                                $items = $conn->prepare("SELECT * FROM transaction_item WHERE transaction_id = ?");
                                                $items->execute([$transaction_id]);
                                                //$items = $query->fetch();
                                            ?>
                                            <tr data-customer="<?=$transaction['customer_name']==null?'Walk-in':$transaction['customer_name']; ?>" data-date="<?=date("m-d-Y", strtotime($transaction['transaction_date'])); ?>" style="background:<?=$transaction['status']==0?'#ffe1e1':'';?>">
                                                <td><?=date("Y-M-d (g:i:s a)", strtotime($transaction['transaction_date'])); ?></td>
                                                <td><?=$transaction['customer_name']==null?'Walk-in':$transaction['customer_name']; ?><br><?=$transaction['ref_number']; ?></td>
                                                <td><?=$transaction['revenue']; ?></td>
                                                <td><?=$transaction['type']; ?></td>
                                                <td>
                                                    <a href="transactions.php?id=<?=$transaction['id']; ?>&action=view" class="">VIEW</a>
                                                    <?php if ($transaction['status']): ?>
                                                    <?php if ($_SESSION['user']['user_type'] == 'admin'): ?> | 
                                                    <a href="transactions.php?id=<?=$transaction['id']; ?>&action=void" class="" onclick="return confirm('Are you sure you want to void transaction?');">VOID</a>
                                                    <?php endif; else: ?>
                                                    [VOIDED]
                                                    <?php endif; ?> |
                                                    <a href="transactions.php?id=<?=$transaction['id']; ?>&action=delete" class="" onclick="return confirm('Are you sure you want to delete transaction?');">DELETE</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">

                                <h5><?php echo $formTitle; ?></h5>
                                <?php if ($action == 'view'): ?>
                                <form method="POST" action="">
                                    <div class="form-grp">
                                        <label>Via: <?=$selectedTransaction['type']; ?></label>
                                    </div>
                                    <div class="form-grp">
                                        <label>Date Time: <?=$selectedTransaction['transaction_date']; ?></span></label>
                                    </div>
                                    <div class="form-grp">
                                        <label>Customer: <?=$selectedTransaction['customer_name']==null?'Walk-in':$selectedTransaction['customer_name']; ?></label>
                                    </div>
                                    <div class="form-grp">
                                        <label>Status: <?=$selectedTransaction['status']?'Completed':'Voided'; ?></label>
                                    </div>
                                 
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Cost</th>
                                                <th>Profit</th>
                                                <th>Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            $stmt = $conn->prepare("SELECT * FROM transaction_item WHERE transaction_id = ? ORDER BY id DESC");
                                            $stmt->execute([$id]); 
                                            $transactionItems = $stmt->fetchAll();
                                            $grandTotal = $subTotal = 0;
                                            foreach ($transactionItems as $key => $transactionItem): 

                                            $pid = $transactionItem['product_id'];
                                            $query = $conn->prepare("SELECT * FROM products WHERE id = ?");
                                            $query->execute([$pid]);
                                            $product = $query->fetch();
                                            

                                            $subTotal = $transactionItem['price'] * $transactionItem['quantity'];
                                            $grandTotal += $subTotal;

                                            ?>
                                            <tr>
                                                <td><?=$transactionItem['product_name']; ?></td>
                                                <td><?=$transactionItem['quantity']; ?></td>
                                                <td><?=$transactionItem['price']; ?></td>
                                                <td><?=$transactionItem['cost']; ?></td>
                                                <td><?=$transactionItem['profit']; ?></td>
                                                <td><?=number_format($subTotal,2); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td><strong>Cash</strong></td>
                                                <td><strong><?=number_format($selectedTransaction['customer_cash'],2); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td><strong>Change</strong></td>
                                                <td><strong><?=number_format($selectedTransaction['customer_change'],2); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td><strong>Grand Total</strong></td>
                                                <td><strong><?=number_format($grandTotal,2); ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <div class="form-grp">
                                        <a href="transactions.php" class="btn-style btn-style-light">CLOSE</a>
                                    </div>
                                </form>
                                <?php endif; ?>
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

        $('button#filter-transaction').on('click', function() {
            var fyear = $('select.filter-year').val();
            var fmonth = $('select.filter-month').val();
            var fday = $('select.filter-day').val();
            var fcustomer = $('.filter-customer').val();

            window.location="transactions.php?filter_year="+fyear+"&filter_month="+fmonth+"&filter_day="+fday+"&filter_customer="+fcustomer;
        });

        $('button#filter-clear').on('click', function() {
            window.location="transactions.php";
        });

        // $('button.filter').on('click', function() {

        //     var customer_name = $('input.filter-customer').val();
        //     var dateYear = $('select.filter-year').val();
        //     var dateMonth = $('select.filter-month').val();
        //     var dateDay = $('select.filter-day').val();

        //     $('.transaction_list tbody tr').hide();
        //     $('.transaction_list tbody tr[data-date="'+dateMonth+'-'+dateDay+'"][data-customer*="'+customer_name+'"]').show();

        //     //console.log($('.transaction_list tbody tr[data-customer*="'+customer_name+'"]').show());

        // });
        
    });
    </script>
</body>
</html>
