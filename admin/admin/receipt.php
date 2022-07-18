<?php 
    session_start();
    
    include '../db_config.php';
    
    
    
    if (isset($_GET['id']) && !empty($_GET['id'])) {

        $request_id = $_GET['id'];

        $query = $conn->prepare("SELECT * FROM transactions WHERE id=?");
        $query->execute([$request_id]);
        $transaction = $query->fetch();

        $query = $conn->prepare("SELECT * FROM transaction_item WHERE transaction_id=?");
        $query->execute([$transaction['id']]);
        $transactionItems = $query->fetchAll();
        $items = '';
        $total_quantity = 0;

        foreach ($transactionItems as $key => $transactionItem) {

            $query = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $query->execute([$transactionItem['product_id']]);
            $product = $query->fetch();
        
            $items .= "<p style= 'text-align: left;font-size:10px;' >{$product['name']}    -  ₱ {$transactionItem['price']}  x{$transactionItem['quantity']}</br>Sub Total   -  ₱  {$transactionItem['revenue']}</p>";
            $total_quantity += $transactionItem['quantity'];
        }
                
    } else {

        header("location: transactions.php");

    }

    echo "<script> window.print(); window.close(); </script>";
?>

<!DOCTYPE html>

<html>
<head>
    <title>POS Inventory</title>
    <link rel="stylesheet" type="text/css" href="assets/app.css">
</head>
<body>



<div id="Receipt-POS">
    
    
    <h2 style= 'text-align: center;font-size:18px; padding-top: 60px;padding-bottom: 1px;' >Clinipets</h2>
    <div style= 'text-align: left;font-size:10px;padding-right: 10px; padding-bottom: 10px;padding-left: 10px;'>

    <div><p style= 'text-align: left;font-size:10px;padding-left: 1px ' >Cashier : <?php echo $_SESSION['user']['name']; ?></br>Customer : <?php echo($transaction['customer_name']==null?"Walk-in":$transaction['customer_name'])?></br><?=date("Y-M-d (g:i:s a)", strtotime($transaction['transaction_date'])); ?></p>
    <div><?php echo  $items ?></div>
    <div style= 'text-align: left;font-size:10px;' > <p>--------------------------------------------</p></div>
    <div><p>TOTAL  :  ₱<?php echo $transactionItem['revenue']  ?> </br> Cash : ₱<?php echo $transaction['customer_cash']  ?> </br> Change : ₱<?php echo $transaction['customer_change']  ?> </p></div>
    <div style= 'text-align: left;font-size:10px;' > <center><p>***********Items(<?php echo $total_quantity ?>)***********</p></center></div>
    <div style= 'text-align: left;font-size:12px;' > <center><p>TRANSACTION REF: <?php echo $transaction['ref_number'] ?></p></center></div>
    <div style= 'text-align: center;font-size:10px;padding-bottom: 40px;' > <p>-------------------------------------------</br><center>***Thank you for your purchase!***</center></p></div>
    <div style= 'text-align: left;font-size:10px;' > <p>--------------------------------------------</p></div>

</div>    
</div>
    </div>
    
   

    
</body>
</html>