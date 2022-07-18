<?php
    session_start();
    
    include '../db_config.php';
    

    $message = ''; 
    
    if (isset($_POST) && $_POST) {
        
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $query = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $query->execute([$username, $password]);

        if ($query->rowCount() >= 1) {

            $user = $query->fetch();
            $_SESSION['user'] = $user;

            header("location: dashboard.php");
            $message = ''; 

        } else {

            $message = 'Invalid username and/or password!';
            
        }
           
    } 

    if (isset($_SESSION['user'])) {
        header("location: dashboard.php");
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Clinipets POS and Reservation</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/signin.css">
</head>
<body class="text-center">

    <form class="form-signin" method="POST">
        <img class="mb-4" src="../assets/images/logo.png" alt="" width="100%" height="" />
        <h1 class="h3 mb-3 font-weight-normal">Login to Clinipets</h1>

        <?=!empty($message)?"<div class='alert alert-danger'><strong>{$message}</strong></div>":""; ?>

        <label for="username" class="sr-only">Email address</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus />

        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required />
        
        <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">Clinipets Team</p>
    </form>

</body>
</html>
