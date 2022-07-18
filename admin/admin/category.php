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

    $activeNav = 'category';


    // LOAD FOR TABLE
    $stmt = $conn->prepare("SELECT * FROM categories ORDER BY name");
    $stmt->execute(); 
    $categories = $stmt->fetchAll();
    // LOAD FOR TABLE

    // CHECK IF CREATE FORM OR EDIT FORM
    if (isset($_GET['action']) && isset($_GET['id'])) {

        $id = $_GET['id'];

        if ($_GET['action'] == 'delete') {

            $conn->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
            echo '<script>alert("Category Deleted"); window.location="category.php";</script>';
            
        } elseif ($_GET['action'] == 'edit') {

            $formTitle = "UPDATE FORM";

            $query = $conn->prepare("SELECT * FROM categories WHERE id = ?");
            $query->execute([$id]);
            $categoryEdit = $query->fetch();

            $inputName = $categoryEdit['name'];
        }

    } else {

        $id = 0;
        $formTitle = "CREATE FORM";

        $inputName = '';

    }
    // CHECK IF CREATE FORM OR EDIT FORM


    // CREATE OR UPDATE DATA TO DATABASE
    if (isset($_POST) && $_POST) {

        $id = $_POST['id'];
        $name = $_POST['name'];

        if ($id == 0) {

            // create
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);

            echo '<script>alert("Category Saved"); window.location="category.php";</script>';

        } else {

            // update
            $stmt= $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);

            echo '<script>alert("Category Updated"); window.location="category.php";</script>';

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
                    <h1 class="h2">Categories</h1>
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
                                        <button type="submit" name="save" class="btn btn-success">SAVE</button>
                                        <a href="category.php" class="btn btn-dark">CANCEL</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $key => $category): ?>
                                        <tr>
                                            <td><?=$category['name']; ?></td>
                                            <td>
                                                <a href="category.php?id=<?=$category['id']; ?>&action=edit" class="btn btn-warning btn-sm">EDIT</a>
                                                |
                                                <a href="category.php?id=<?=$category['id']; ?>&action=delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?');">DELETE</a>
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
</body>
</html>
