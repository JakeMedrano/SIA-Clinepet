<?php
    session_start();
    
    include 'db_config.php';
    

    $message = ''; 
    
    if (isset($_POST) && $_POST) {

    	if (isset($_POST['login'])) {

    		$username = $_POST['login_username'];
	        $password = md5($_POST['login_password']);

	        $query = $conn->prepare("SELECT * FROM customers WHERE username = ? AND password = ?");
	        $query->execute([$username, $password]);

	        if ($query->rowCount() >= 1) {

	            $user = $query->fetch();
	            $_SESSION['customer'] = $user;

	            header("location: reservation.php");
	            $message = ''; 

	        } else {

	            $message = 'Invalid username and/or password!';
	            
	        }
    	}

    	if (isset($_POST['register'])) {

    		$name = $_POST['name'];
    		$username = $_POST['username'];
	        $password = md5($_POST['password']);

    		$stmt = $conn->prepare("INSERT INTO customers (username, password, name) VALUES (?,?,?)");
            $stmt->execute([$username, $password, $name]);

            echo '<script>alert("Account successfully registered."); window.location="account.php";</script>';

    	}
           
    } 

    if (isset($_SESSION['customer'])) {
        header("location: reservation.php");
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
		<div class="row pt-3">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h5 class="mb-0">Login</h5>
					</div>
					<div class="card-body">
						<form class="form-signin" method="POST">
					        <h1 class="h3 mb-3 font-weight-normal">Login to your account</h1>

					        <?=!empty($message)?"<div class='alert alert-danger'><strong>{$message}</strong></div>":""; ?>

							<div class="form-group">
							    <label for="login_username" class="sr-only">Username</label>
							    <input type="text" id="login_username" name="login_username" class="form-control" placeholder="Email" required />
							</div>
					        
							<div class="form-group">
					        	<label for="login_password" class="sr-only">Password</label>
					        	<input type="password" id="login_password" name="login_password" class="form-control" placeholder="Password" required />
					        </div>
					        
					        <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Login</button>

					        <hr>
					        OR 
					        <hr>
					        <a href="">LOGIN USING FB</a><br>
					        <a href="">LOGIN USING Google</a>
					    </form>						
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h5 class="mb-0">Register</h5>
					</div>
					<div class="card-body"><form class="form-signin" method="POST">
					        <h1 class="h3 mb-3 font-weight-normal">Create your account</h1>

					        <?=!empty($message)?"<div class='alert alert-danger'><strong>{$message}</strong></div>":""; ?>

							<div class="form-group">
							    <label for="name" class="sr-only">Name</label>
							    <input type="text" id="name" name="name" class="form-control" placeholder="Name" required />
							</div> 

							<div class="form-group">
							    <label for="username" class="sr-only">Username</label>
							    <input type="text" id="username" name="username" class="form-control" placeholder="Email" required />
							</div>
					        
							<div class="form-group">
					        	<label for="password" class="sr-only">Password</label>
					        	<input type="password" id="password" name="password" class="form-control" placeholder="Password" required />
					        </div>
					        
					        <button class="btn btn-lg btn-primary btn-block" name="register" type="submit">Register</button>
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
