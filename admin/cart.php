<?php
    session_start();

    include 'db_config.php';


    if (isset($_GET['add-to-cart']) && isset($_GET['id'])) {

    	$product_id = $_GET['id'];
    	$customer_id = $_SESSION['customer']['id'];

    	$query = $conn->prepare("SELECT * FROM cart WHERE customer_id = ? AND product_id = ?");
        $query->execute([$customer_id, $product_id]);
        $customerItem = $query->fetch();

        if ($customerItem) {

        	$stmt= $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->execute([$customerItem['quantity']+1, $customerItem['id']]);

            echo '<script>alert("Your cart item has been updated."); window.location="pet-store.php";</script>';

        } else {

        	$stmt = $conn->prepare("INSERT INTO cart (customer_id, product_id, quantity) VALUES (?,?,?)");
        	$stmt->execute([$customer_id, $product_id, 1]);

        	echo '<script>alert("Item added to your cart."); window.location="pet-store.php";</script>';

        }

    } 

    if (isset($_GET['remove-item']) && isset($_GET['id'])) {

    	$cart_id = $_GET['id'];

    	$conn->prepare("DELETE FROM cart WHERE id = ?")->execute([$cart_id]);
        echo '<script>alert("Cart item removed."); window.location="cart.php";</script>';

    } 

    if (isset($_GET['cart-checkout'])) {

    	$customer_id = $_SESSION['customer']['id'];
    	$customer_name = $_SESSION['customer']['name'];

    	$query = $conn->prepare("SELECT * FROM cart WHERE customer_id = ?");
        $query->execute([$customer_id]);
        $customerItems = $query->fetchAll();

        $stmt = $conn->prepare("INSERT INTO transactions (user_id, ref_number, customer_id, customer_name, customer_cash, customer_change, profit, revenue, type) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([null, (time().rand()), $customer_id, $customer_name, 0, 0, 0, 0,'ONLINE-STORE']);
        $transaction_id = $conn->lastInsertId();

        $totalProfit = $totalRevenue = 0;
        foreach ($customerItems as $key => $customerItem) {

        	$product_id = $customerItem['product_id'];
        	$quantity = $customerItem['quantity'];
        	
            // SELECT EVERY PRODUCT FROM CART ITEMS
        	$query = $conn->prepare("SELECT * FROM products WHERE id = ?");
        	$query->execute([$product_id]);
        	$product = $query->fetch();

            // PROFIT CALCULATION
        	$profit = ($product['price'] - $product['cost']) * $quantity;

        	$sub_total = $product['price'] * $customerItem['quantity'];

			// INSERT TRANSACTION ITEMS (BY PRODUCT)
            $stmt = $conn->prepare("INSERT INTO transaction_item (transaction_id, product_id, product_name, type, price, cost, profit, revenue, quantity, status) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$transaction_id, $product_id, $product['name'], $product['type'], $product['price'], $product['cost'], $profit, $sub_total, $quantity, 1]);


            // PRODUCT TYPE ITEM ONLY
            if ($product['type'] == 'item') {
                // LESS STOCK QTY IN PRODUCT CURRENT_STOCK
                $new_qty = $product['current_stock'] - $quantity;
                $stmt= $conn->prepare("UPDATE products SET current_stock = ? WHERE id = ?");
                $stmt->execute([$new_qty, $product_id]);

                $old_qty = $product['current_stock'];
                $stmt = $conn->prepare("INSERT INTO inventory (product_id, old_qty, new_qty, note) VALUES (?,?,?,?)");
                $stmt->execute([$product_id, $old_qty, $new_qty, 'Transaction ID: '.$transaction_id]);
            } 


            // TOTAL PROFIT AND REVENUE TO LATER TRANSACTION UPDATE BELOW
            $totalProfit += $profit;
            $totalRevenue += $sub_total;

        }

        // UPDATE TRANSACTION PROFIT AND REVENUE
        $stmt= $conn->prepare("UPDATE transactions SET profit = ?, revenue = ? WHERE id = ?");
        $stmt->execute([$totalProfit, $totalRevenue, $transaction_id]);

        $conn->prepare("DELETE FROM cart WHERE customer_id = ?")->execute([$customer_id]);

        echo '<script>alert("Cart successfully checkout."); window.location="cart.php";</script>';

    } 

    if (isset($_POST) && isset($_POST['update-cart'])) {

    	$customer_id = $_SESSION['customer']['id'];

    	foreach ($_POST['quantity'] as $key => $value) {

    		$stmt= $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->execute([$_POST['quantity'][$key], $_POST['cart'][$key]]);

    	}

    	echo '<script>alert("Your cart item has been updated."); window.location="cart.php";</script>';

    }

	$stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute(); 
    $categories = $stmt->fetchAll();

    if (isset($_SESSION['customer'])) {
    	$stmt = $conn->prepare("SELECT * FROM cart WHERE customer_id = ?");
	    $stmt->execute([$_SESSION['customer']['id']]); 
	    $cartItems = $stmt->fetchAll();
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

    <div class="container">
    	<div class="row pt-5 pb-5">
    		<div class="col-md-12 text-center">
    			<h3>Cart</h3>
    		</div>
    	</div>
    	<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<form action="" method="POST">
							<table class="table table-condensed">
								<thead>
									<tr>
										<th width="50%">Item</th>
										<th>Price</th>
										<th width="10%">Quantity</th>
										<th>Sub Total</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($_SESSION['customer'])):?>
									<?php
									$total = 0;
									foreach($cartItems as $cartItem): 
										$query = $conn->prepare("SELECT * FROM products WHERE id = ?");
							        	$query->execute([$cartItem['product_id']]);
							        	$cartProduct = $query->fetch();

                                        $image = $cartProduct['image']?$cartProduct['image']:'product.png';
                                        $image = "uploads/{$image}";

							        	$subTotal = $cartProduct['price'] * $cartItem['quantity'];
							        	$total += $subTotal;
									?>
									<tr>
                                        <td>
                                            <img src="<?=$image; ?>" class="img-thumbnail" width="50">
                                            <?=$cartProduct['name']; ?>
                                        </td>
										<td><?=$cartProduct['price']; ?></td>
										<td>
											<input type="number" name="quantity[]" class="form-control" value="<?=$cartItem['quantity']; ?>" min="1">
											<input type="hidden" name="cart[]" value="<?=$cartItem['id']; ?>">
										</td>
										<td>
											<?=number_format($subTotal, 2); ?>
										</td>
										<td>
											<a href="cart.php?remove-item&id=<?=$cartItem['id']; ?>" class="text-danger" onclick="return confirm('Are you sure you want to remove this item?');">Remove</a>
										</td>
									</tr>
									<?php endforeach; ?>
                                    <?php if (count($cartItems) > 0): ?>
									<tr>
										<td colspan="1" class="">
											<a href="pet-store.php" class="btn btn-default">CONTINUE SHOPPING</a>
										</td>
										<td colspan="1" class=""></td>
										<td colspan="1" class="">
											<button class="btn btn-dark" name="update-cart" type="submit">UPDATE</button>
										</td>
										<td colspan="1" class="">
											<strong>TOTAL: <?=number_format($total, 2); ?></strong>
										</td>
										<td colspan="1" class="">
											<a href="cart.php?cart-checkout" class="btn btn-primary">CHECKOUT</a>	
										</td>
									</tr>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <p class="text-danger"><strong>NO ITEMS FOUND</strong></p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
									<?php else: ?>
									<tr>
										<td colspan="5" class="text-center">
											<p class="text-danger"><strong>LOGIN FIRST</strong></p>
										</td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

    <?php include 'theme-js.php'; ?>
    <script>
        $(function() {

            
        });
    </script>
</body>
</html>
