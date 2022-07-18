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


    if (isset($_GET['action']) && isset($_GET['id'])) {

        if (in_array($_GET['action'], ['approve','decline','cancel'])) {

            $stmt= $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            $stmt->execute([$_GET['action'], $_GET['id']]);
            echo '<script>alert("Appointment updated"); window.location="reservations.php";</script>';   

        } elseif ($_GET['action'] == 'delete') {

            $conn->prepare("DELETE FROM appointments WHERE id = ?")->execute([$_GET['id']]);
            echo '<script>alert("Appointment deleted"); window.location="reservations.php";</script>';   
            
        }
    }   

    $stmt = $conn->prepare('SELECT * FROM appointments ORDER BY request_date DESC');
    $stmt->execute();
    $appointments = $stmt->fetchAll();

    $activeNav = 'reservations';

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
                    <h1 class="h2">Reservation</h1>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table transaction_list">
                                        <thead>
                                            <tr>
                                                <th>Request Date</th>
                                                <th>Customer</th>
                                                <th>Service</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($appointments as $key => $appointment): 
                                                $customer_id = $appointment['customer_id'];
                                                $query = $conn->prepare("SELECT * FROM customers WHERE id = ?");
                                                $query->execute([$customer_id]);
                                                $customer = $query->fetch();

                                                $product_id = $appointment['product_id'];
                                                $query = $conn->prepare("SELECT * FROM products WHERE id = ?");
                                                $query->execute([$product_id]);
                                                $product = $query->fetch();
                                            ?>
                                            <tr>
                                                <td><?=$appointment['request_date']; ?></td>
                                                <td><?=$customer['name']; ?></td>
                                                <td>
                                                    <?=$product['name']; ?><br>
                                                    <?=$product['price']; ?>
                                                </td>
                                                <td><?=$appointment['status']; ?></td>
                                                <td>
                                                    <?php if ($appointment['status'] == 'pending'): ?>
                                                        
                                                    <a href="reservations.php?id=<?=$appointment['id']; ?>&action=approve" class="btn btn-success btn-sm mr-2" onclick="return confirm('Are you sure you want to approve?');">APPROVE</a>

                                                    <a href="reservations.php?id=<?=$appointment['id']; ?>&action=decline" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to decline?');">DECLINE</a>

                                                    <?php elseif($appointment['status'] == 'approve' || $appointment['status'] == 'decline'): ?>

                                                    <a href="reservations.php?id=<?=$appointment['id']; ?>&action=cancel" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to cancel?');">CANCEL</a>

                                                    <?php else: ?>
                                                    <?php endif; ?>
                                                    <a href="reservations.php?id=<?=$appointment['id']; ?>&action=delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?');">DELETE</a>

                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <?php include 'theme-js.php'; ?>
</body>
</html>
