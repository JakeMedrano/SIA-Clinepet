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

    <div class="container-fluid">
    	<div class="row pt-5 pb-5">
    		<div class="col-md-12 text-center">
    			<h3><b>ONLINE STORE</b></h3>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-9">
    			<?php foreach($categories as $category): 
					$stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
				    $stmt->execute([$category['name']]); 
				    $products = $stmt->fetchAll();
				?>
				<div class="row pt-3 pb-5">
					<div class="col-md-12">
						<h5><?=$category['name']; ?></h5>
					</div>
					<?php foreach($products as $product): 
				        $image = $product['image']?$product['image']:'product.png';
	                    $image = "uploads/{$image}";
					?>
					<div class="col-md-3">
						<div class="card">
							<img src="<?=$image; ?>" class="card-img-top" alt="product image">
							<div class="card-body">
								<h5 class="card-title"><?=$product['name']; ?></h5>
							    <p class="card-text">Php <?=number_format($product['price'], 2); ?></p>
							    <?php if (isset($_SESSION['customer'])):?>
							    <a href="?add-to-cart&id=<?=$product['id']; ?>" class="btn btn-primary">Add to Cart</a>
							    <?php else: ?>
							    <a href="account.php" class="btn btn-dark">Login first</a>
							    <?php endif; ?>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
    		</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<h4>My Cart</h4>
						<table class="table table-condensed">
							<thead>
								<tr>
									<th width="80%">Item</th>
									<th>Quantity</th>
								</tr>
							</thead>
							<tbody>
								<?php if (isset($_SESSION['customer'])):?>
								<?php foreach($cartItems as $cartItem): 
								$query = $conn->prepare("SELECT * FROM products WHERE id = ?");
						        $query->execute([$cartItem['product_id']]);
						        $cartProduct = $query->fetch();

						        $image = $cartProduct['image']?$cartProduct['image']:'product.png';
			                    $image = "uploads/{$image}";
								?>
								<tr>
									<td>
										<img src="<?=$image; ?>" class="img-thumbnail" width="50">
										<?=$cartProduct['name']; ?>
									</td>
									<td><?=$cartItem['quantity']; ?></td>
								</tr>
								<?php endforeach; ?>
								<tr>
									<td colspan="2" class="text-center">
										<a href="cart.php" class="btn btn-info btn-sm"> CART</a>
									</td>
								</tr>
								<?php else: ?>
								<tr>
									<td colspan="2" class="text-center">
										<p class="text-danger"><strong>LOGIN FIRST</strong></p>
									</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
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
