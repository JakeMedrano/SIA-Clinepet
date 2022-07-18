<?php
    session_start();

    include 'db_config.php';

	if (isset($_POST['save'])) {

		$product_id = $_POST['product_id'];
		$date = $_POST['request_date'];
		$status = 'PENDING';

		$stmt = $conn->prepare("INSERT INTO appointments (customer_id, product_id, request_date, status) VALUES (?,?,?,?)");
		$stmt->execute([
			$_SESSION['customer']['id'],
			$product_id,
			$_POST['request_date'],
			'pending'
		]);

        echo '<script>alert("Your appointment is pending for approval."); window.location="reservation.php";</script>';

	}


	$stmt = $conn->prepare("SELECT * FROM products WHERE type = 'service' ");
    $stmt->execute(); 
    $products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Clinipets POS and Reservation</title>
    <?php include 'theme-css.php'; ?>

	<link rel="stylesheet" href="assets/css/fullcalendar.min.css" />
</head>
<body>

    <?php include 'nav-top.php'; ?>

	<div class="container">
		<div class="row pt-3">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<strong class="card-title">Select your preferred date.</strong>
					</div>
					<div class="card-body">
			    		<div id="calendar"></div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-header">
						<strong class="card-title">Booking details.</strong>
					</div>
					<div class="card-body">
						<form action="" method="POST">
						    <div class="form-group">
						        <label for="request_date" class="float-left">Selected Date</label>
						        <input type="text" class="form-control" name="request_date" id="request_date" readonly="" required="">
						    </div>
						    <div class="form-group">
						        <label for="fullname" class="float-left">Name</label>
						        <input type="text" class="form-control" name="" id="fullname" value="<?=isset($_SESSION['customer'])?$_SESSION['customer']['name']:''; ?>" readonly="">
						    </div>
						    <div class="form-group">
						        <label for="type" class="float-left">Services</label>
						        <select class="form-control" name="product_id" id="type" required="">
						            
						            <?php foreach ($products as $key => $product): ?>
						            <option value="<?=$product['id']; ?>"><?=$product['name']; ?></option>
						            <?php endforeach; ?>
						        </select>
						    </div>
						    <div class="form-group">
						    	<?php if (isset($_SESSION['customer'])): ?>
						    	<button type="submit" name="save" class="btn btn-primary btn-block">SUBMIT</button>
						    	<?php else: ?>
						    	<a href="account.php" class="btn btn-primary btn-block">LOGIN / REGISTER</a>
						    	<?php endif; ?>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

    <?php include 'theme-js.php'; ?>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/fullcalendar.min.js"></script>
    <script>
        $(function() {
            $('#calendar').fullCalendar({
  				dayClick: function( date, jsEvent, view) {
				    if (moment().format('YYYY-MM-DD') === date.format('YYYY-MM-DD') || date.isAfter(moment())) {
				        //check_availability(date.format());
				        $('#request_date').val(date.format());
				    }
				}
            });	
            
        });
    </script>
</body>
</html>
