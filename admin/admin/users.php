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

    $activeNav = 'users';


    // LOAD FOR TABLE
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute(); 
    $users = $stmt->fetchAll();
    // LOAD FOR TABLE


    // CHECK IF CREATE FORM OR EDIT FORM
    if (isset($_GET['action']) && isset($_GET['id'])) {

        $id = $_GET['id'];

        if ($_GET['action'] == 'delete') {

            $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
            echo '<script>alert("User Deleted"); window.location="users.php";</script>';
            
        } elseif ($_GET['action'] == 'edit') {

            $formTitle = "UPDATE FORM";

            $query = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $query->execute([$id]);
            $userEdit = $query->fetch();

            $inputUsername = $userEdit['username'];
            $inputPassword = $userEdit['password'];
            $inputName = $userEdit['name'];
        }

    } else {

        $id = 0;
        $formTitle = "CREATE FORM";

        $inputType = '';
        $inputUsername = '';
        $inputPassword = '';
        $inputName = '';

    }
    // CHECK IF CREATE FORM OR EDIT FORM


    // CREATE OR UPDATE DATA TO DATABASE
    if (isset($_POST) && $_POST) {

        $id = $_POST['id'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $name = $_POST['name'];

        if ($id == 0) {

            // create
            $stmt = $conn->prepare("INSERT INTO users (user_type, username, password, name) VALUES (?,?,?,?)");
            $stmt->execute(['admin', $username, $password, $name]);

            echo '<script>alert("User Saved"); window.location="users.php";</script>';

        } else {

            // update
            $stmt= $conn->prepare("UPDATE users SET user_type = ?, username = ?, name = ? WHERE id = ?");
            $stmt->execute(['admin', $username, $name, $id]);

            if (isset($_POST['password']) && !empty($_POST['password'])) {
                $stmt= $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$password, $id]);
            }
            
            echo '<script>alert("User Updated"); window.location="users.php";</script>';

        }
        
    }
    // CREATE OR UPDATE DATA TO DATABASE

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
                    <h1 class="h2">Users</h1>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5><?php echo $formTitle; ?></h5>
                                <form method="POST" action="">
                                    
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <input type="text" class="form-control" name="name" placeholder="Enter here" value="<?=$inputName; ?>" required="" />
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="username" placeholder="Enter here" value="<?=$inputUsername; ?>" required="" />
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="text" class="form-control" name="password" placeholder="Enter here" value="" <?php $formTitle=='CREATE FORM'?'required=""':''; ?> />
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="save" class="btn btn-success">SAVE</button>
                                        <a href="users.php" class="btn btn-dark">CANCEL</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5>User List</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $key => $user): ?>
                                        <tr>
                                            <td><?=$user['name']; ?></td>
                                            <td>
                                                <?=$user['username']; ?>
                                            </td>
                                            <td>
                                                ****
                                            </td>
                                            <td>
                                                <a href="users.php?id=<?=$user['id']; ?>&action=edit" class="btn btn-warning btn-sm">EDIT</a>
                                                |
                                                <a href="users.php?id=<?=$user['id']; ?>&action=delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?');">DELETE</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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
        var id = $('input[name="id"]').val();

        if (id == 0) {
            // create user
            $('input[name="password"]').prop('readonly', true).prop('placeholder', 'Auto generated');

            var rand = Math.floor((Math.random() * 10000) + 1);

            $('input[name="name"]').on('keyup', function() {
                var name = $(this).val();
                var pwd = name +''+ rand;

                $('input[name="password"]').val(pwd);
            });

        } else {
            // update user
            $('input[name="password"]').prop('readonly', false).prop('placeholder', 'Password');
        }
    });
    </script>
</body>
</html>
