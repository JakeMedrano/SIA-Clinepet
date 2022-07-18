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

    $activeNav = 'pos';

    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute(); 
    $products = $stmt->fetchAll();

    if (isset($_POST) && $_POST) {

        if (!array_key_exists('product_id', $_POST)) {
            echo "<script>alert('PLEASE SELECT PRODUCT FIRST'); window.location='pos.php'; </script>";
            exit();
        }

        $customer = $_POST['customer'];
        $cash = $_POST['customer_cash'];
        $change = $_POST['customer_change'];
        $product_id = $_POST['product_id'];
        $product_price = $_POST['product_price'];
        $quantity = $_POST['quantity'];
        $sub_total = $_POST['sub_total'];

        // INSERT TRANSACTION
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, ref_number, customer_name, customer_cash, customer_change, profit, revenue, type) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$_SESSION['user']['id'], (time().rand()), $customer, $cash, $change, 0, 0,'POS']);
        $transaction_id = $conn->lastInsertId();

        $totalProfit = $totalRevenue = 0;
        foreach ($product_id as $key => $x) {

            // SELECT EVERY PRODUCT FROM CART ITEMS
            $query = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $query->execute([$product_id[$key]]);
            $product = $query->fetch();

            // PROFIT CALCULATION
            $profit = ($product['price'] - $product['cost']) * $quantity[$key];

            // INSERT TRANSACTION ITEMS (BY PRODUCT)
            $stmt = $conn->prepare("INSERT INTO transaction_item (transaction_id, product_id, product_name, type, price, cost, profit, revenue, quantity, status) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$transaction_id, $product_id[$key], $product['name'], $product['type'], $product['price'], $product['cost'], $profit, $sub_total[$key], $quantity[$key], 1]);


            // PRODUCT TYPE ITEM ONLY
            if ($product['type'] == 'item') {
                // LESS STOCK QTY IN PRODUCT CURRENT_STOCK
                $new_qty = $product['current_stock'] - $quantity[$key];
                $stmt= $conn->prepare("UPDATE products SET current_stock = ? WHERE id = ?");
                $stmt->execute([$new_qty, $product_id[$key]]);

                $old_qty = $product['current_stock'];
                $stmt = $conn->prepare("INSERT INTO inventory (product_id, old_qty, new_qty, note) VALUES (?,?,?,?)");
                $stmt->execute([$product_id[$key], $old_qty, $new_qty, 'Transaction ID: '.$transaction_id]);
            } 


            // TOTAL PROFIT AND REVENUE TO LATER TRANSACTION UPDATE BELOW
            $totalProfit += $profit;
            $totalRevenue += $sub_total[$key];
            
        }

        // UPDATE TRANSACTION PROFIT AND REVENUE
        $stmt= $conn->prepare("UPDATE transactions SET profit = ?, revenue = ? WHERE id = ?");
        $stmt->execute([$totalProfit, $totalRevenue, $transaction_id]);

        // REDIRECT & ALERT
        echo "<script>
        alert('POS Transaction Saved'); 
        window.open('receipt.php?id={$transaction_id}'); 
        window.focus(); window.location='pos.php'; </script>";

    }

    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute(); 
    $categories = $stmt->fetchAll();
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
                    <h1 class="h2">POINT OF SALE</h1>
                </div>

                <div class="row pt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <a class="btn btn-dark category" data-value="all" href="javascript:void(0);">All</a>
                                        <a class="btn btn-dark category" data-value="item" href="javascript:void(0);">Items</a>
                                        <a class="btn btn-dark category" data-value="service" href="javascript:void(0);">Services</a>
                                    </div>
                                    <div class="col-md-12 pt-3">
                                        <p>[<strong>F1</strong>] to search, [<strong>F2</strong>] to clear</p>
                                        <div class="row">
                                            <div class="col-md-3">
                                                 <input type="search" class="form-control product-search" placeholder="Product search ..." />
                                            </div> 
                                            <div class="col-md-3">
                                                <input type="search" class="form-control product-barcode" placeholder="Product Barcode ..." />
                                            </div> 
                                            <div class="col-md-3">
                                                <select class="form-control form-select filter-category">
                                                    <option value="all">All</option>
                                                    <?php foreach($categories as $category): ?>
                                                    <option value="<?=$category['name']; ?>"><?=$category['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select> 
                                            </div> 
                                            <div class="col-md-3">
                                                <button type="button" id="filter-product" class="btn btn-info">FILTER</button>
                                            </div> 
                                        </div>
                                    </div>

                                    <div class="col-md-12 pt-4">
                                        <div class="row">
                                            <?php foreach ($products as $key => $product): 
                                                $cname = $product['category'];
                                                $pname = $product['name'];
                                                $image = $product['image']?$product['image']:'product.png';
                                                $image = "../uploads/{$image}";
                                            ?>
                                            <div class="col-md-4 mb-4">
                                                
                                                <a href="javascript:void(0);" data-id="<?=$product['id']; ?>" data-barcode="<?=$product['barcode']; ?>" data-name="<?=$product['name']; ?>" data-category="<?=$cname;?>" data-cost="<?=$product['cost']; ?>" data-price="<?=$product['price']; ?>" data-type="<?=$product['type']; ?>" class="item product-select" style="text-decoration: none; color: #333;"> 
                                                    <div class="card">
                                                        <img src="<?=$image; ?>" class="card-img-top" alt="product image">
                                                        <div class="card-body">
                                                            <?=$pname; ?> <?= $product['type']=='item'?'('.$product['current_stock'].')':''; ?>
                                                        </div>
                                                    </div>
                                                </a>

                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="">
                                    <input type="text" name="customer" class="form-control form-control-lg" placeholder="CUSTOMER NAME">
                                    <table class="table cart-items">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th width="15%">Quantity</th>
                                                <th width="20%">Sub Total</th>
                                                <th width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <table class="table">
                                        <tr>
                                            <td>
                                                Cash
                                                <input type="number" class="form-control" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57" name="customer_cash" id="customer_cash" placeholder="0" min="0" value="0" />
                                            </td>
                                            <td>
                                                Change
                                                <input type="number" class="form-control" name="customer_change" id="customer_change" placeholder="0" readonly="" min="0" value="0" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1">
                                                <button type="button" class="btn btn-danger btn-block mt-5 clear-order">X ITEMS</button>
                                            </td>
                                            <td >
                                                <button type="submit" class="btn btn-success btn-block mt-5 save-order" disabled="">PAY <span class="grand-total">0.00</span></button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>                        
                    </div>
                </div>
            </main>
        </div>
    </div>


    

    <?php include 'theme-js.php'; ?>
    <script>

        
        $('a.category').on('click', function() {
            var val = $(this).data('value');
            $('a.product-select').hide();

            if (val == 'all') {
                $('a.product-select').show();
            } else { 
                $('a.product-select[data-type=' + val + ']').show();
            }
        });

        $('input.product-barcode').on('keyup', function() {
            var val = $(this).val();

            if ($('a.product-select[data-barcode="' + val + '"]').length > 0) {

                addToCart($('a.product-select[data-barcode="' + val + '"]'));
                $(this).val('');

            }
        });

        $("body").keydown(function(e){
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 112) { // F1
                $('input.product-barcode').focus();
            } else if (keyCode == 113) { // F2
                $('input.product-barcode').val('');
            } 
        });


        $('body').on('click','a.product-select', function (){
            addToCart($(this));
            
        });

        function addToCart(el) {
            var id = el.data('id');
            var name = el.data('name');
            var cost = el.data('cost');
            var price = el.data('price');

            if ($('table.cart-items tr#'+id).length > 0) {

                var qtyEl = $('table.cart-items tr#'+id).find("td:eq(1) input[id='quantity']");
                var qty_val = qtyEl.val();
                var new_qty = parseInt(qty_val) + 1;
                qtyEl.val(new_qty);

            } else {

                var rst = price * 1;
                $('table.cart-items').append('<tr id="'+id+'"><td>'+name+' <br><small>'+price+'</small></td><td><input class="" type="hidden" name="product_id[]" value="'+id+'"><input type="hidden" name="product_price[]" class="product_price" value="'+price+'"><input type="number" name="quantity[]" id="quantity" class="form-control" value="1" min="1"></td><td><input type="number" name="sub_total[]" id="sub_total" class="form-control" value="'+rst+'" min="0" readonly=""></td><td><a href="javascript:void(0);" class="remove-cart-item">X</a></td></tr>');

            }

            calcCart();
        }

        $('body').on('change','input#quantity', function() {
            calcCart();
        });

        $('body').on('click','a.remove-cart-item', function() {
            $(this).closest('tr').remove();
            calcCart();
            calcCashChange();
        });

        calcCart();
        function calcCart() {
            var grandTotal = 0;
            $("table.cart-items tbody tr").each(function () {
                var row = $(this);
                var price = row.find("td:eq(1) input[class='product_price']").val();
                var qty = row.find("td:eq(1) input[id='quantity']").val();
                var x = parseFloat(price) * parseInt(qty);
                var sub_total = row.find("td:eq(2) input[id='sub_total']").val(x);

                grandTotal += x;

            });

            $('span.grand-total').text(grandTotal);
            calcCashChange();
        }

        calcCashChange();
        function calcCashChange() {
            var buttonDisabled = true;
            var grandTotal = parseFloat($('.grand-total').text());
            var cash = $('#customer_cash');
            var change = $('#customer_change');
            var changeAmt = parseFloat(cash.val()) - grandTotal;

            if (changeAmt < 0) {
                change.val(0);
                buttonDisabled = true;
            } else {
                change.val(changeAmt);
                buttonDisabled = false;
            }

            if (cash.val() <= 0) {
                buttonDisabled = true;
            }
            
            var payBtn = $('.save-order');

            if (buttonDisabled) {
                payBtn.attr('disabled', true);
            } else {
                payBtn.attr('disabled', false);
            }
        }

        $('#customer_cash').on('keyup', function() {
            calcCashChange();
        });

        $('.product-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $(".product-select").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });


        $('body').on('click','button.clear-order', function() {
            $('table.cart-items > tbody').html('');
            calcCart();
        });


        $('button#filter-product').on('click', function() {        
            var category = $('select.filter-category').val();
            var cond = '';

            if (category != 'all') {
                cond += '[data-category="'+category+'"]';
            }


            if (category == 'all') {
                $('.item').show();
            } else {
                $('.item').hide();
                $('.item'+cond).show();    
            }
    
        });
    </script>

</body>
</html>
