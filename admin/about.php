<?php
    include 'db_config.php';
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clinipets POS and Reservation</title>
    <?php include 'theme-css.php'; ?>
    <style type="text/css">
    	.mt-5 {
  			margin-top: 5rem;
		}
		.container {
			color: #fff;
		}
		.lead {
			font-size: 36px;
		}

    </style>
</head>
<body>

    <?php include 'nav-top.php'; ?>

	<div class="container marketing">
		<main role="main">
			<div class="row mt-5">
		    	<div class="col-md-12 text-center">
		    		<h1 class="display-4">About Clinipet</h1>
		    	</div>
			</div>
		    <div class="row featurette mt-5">
		        <div class="col-md-10">
		            <p class="lead">Clinipet is dedicated to provide the highest level of veterinary medecine and pet shop along with friendly, compassionate service at affordable prices. We belived in treating every patient as if they were our own pet, and giving them the same loving attention and care</p>
		        </div>
		        <div class="col-md-2">
		            <img src="assets/images/icon.png"  style="border-radius: 250px;">
		        </div>
		    </div>
		</main>
	</div>


    <?php include 'theme-js.php'; ?>
    <script>
        $(function() {

            
        });
    </script>
</body>
</html>
