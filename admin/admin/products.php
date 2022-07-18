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

    $activeNav = 'products';


    // LOAD FOR FORM SELECT AND TABLE


    $stmt = $conn->prepare("SELECT * FROM categories ORDER BY name");
    $stmt->execute(); 
    $categories = $stmt->fetchAll();
    // LOAD FOR FORM SELECT AND TABLE


    // CHECK IF CREATE FORM OR EDIT FORM
    if (isset($_GET['action']) && isset($_GET['id'])) {

        $id = $_GET['id'];

        if ($_GET['action'] == 'delete') {

            $conn->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
            echo '<script>alert("Product Deleted"); window.location="products.php";</script>';
            
        } elseif ($_GET['action'] == 'edit') {

            $formTitle = "UPDATE FORM";

            $query = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $query->execute([$id]);
            $productEdit = $query->fetch();

            $inputType = $productEdit['type'];
            $inputBarcode = $productEdit['barcode'];
            $inputName = $productEdit['name'];
            $inputCost = $productEdit['cost'];
            $inputPrice = $productEdit['price'];
            $inputCategory = $productEdit['category'];
        }

    } else {

        $id = 0;
        $formTitle = "CREATE FORM";

        $inputType = '';
        $inputBarcode = '';
        $inputName = '';
        $inputCost = '';
        $inputPrice = '';
        $inputCategory = '';

    }
    // CHECK IF CREATE FORM OR EDIT FORM


    // CREATE OR UPDATE DATA TO DATABASE
    if (isset($_POST) && $_POST) {

        $id = $_POST['id'];
        $type = $_POST['type'];
        $barcode = $_POST['barcode'];
        $name = $_POST['name'];
        $cost = $_POST['cost'];
        $price = $_POST['price'];
        $category = $type=='item'?$_POST['category']:null;

        if (isset($_FILES['product_image']['name'])) {
            $filename = strtolower(trim($name));
            $filename = str_replace(' ', '-', $filename);
            $filename = time().$filename.'.jpg';
        }

        if ($id == 0) {

            // create
            $stmt = $conn->prepare("INSERT INTO products (type, barcode, image, name, cost, price, current_stock, category) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([$type, $barcode, $filename, $name, $cost, $price, 0, $category]);

            if (isset($_FILES['product_image']['name'])) {
                move_uploaded_file($_FILES['product_image']['tmp_name'],'../uploads/'.$filename);
            }

            echo '<script>alert("Product Saved"); window.location="products.php";</script>';

        } else {

            // update
            $stmt= $conn->prepare("UPDATE products SET type = ?, barcode = ?, name = ?, cost = ?, price = ?, category = ? WHERE id = ?");
            $stmt->execute([$type, $barcode, $name, $cost, $price, $category, $id]);

            if (isset($_FILES['product_image']['name'])) {
                if (move_uploaded_file($_FILES['product_image']['tmp_name'],'../uploads/'.$filename)) {

                    $stmt= $conn->prepare("UPDATE products SET image = ? WHERE id = ?");
                    $stmt->execute([$filename, $id]);
                }
            }

            echo '<script>alert("Product Updated"); window.location="products.php";</script>';

        }
        
    }
    // CREATE OR UPDATE DATA TO DATABASE


    $filter_category = '';
    if (isset($_GET['filter_category'])) { 

        $filter_category = $_GET['filter_category'];


        $conditions = [];

        if ($filter_category != 'all') {
            $conditions['category'] = $filter_category;
        }

        $where = '';
        foreach ($conditions as $column => $value) {
            $where .= " AND {$column} = '{$value}'";
        }

        $stmt = $conn->prepare("SELECT * FROM products WHERE (id <> '') {$where} ");
        $stmt->execute(); 
        $products = $stmt->fetchAll();

    } else {

        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute(); 
        $products = $stmt->fetchAll();

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
        <div class="row">
            
            <?php include 'nav-side.php'; ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="pt-3">
                    <h1 class="h2">Products</h1>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5><?php echo $formTitle; ?></h5>
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select class="form-control form-select" name="type" id="type" required="">
                                            <option value="item" <?=$inputType=='item'?'selected':''; ?>>Item</option>
                                            <option value="service" <?=$inputType=='service'?'selected':''; ?>>Service</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Barcode</label>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <input type="text" class="form-control" name="barcode" placeholder="Enter here" value="<?=$inputBarcode; ?>" required="" />
                                    </div>
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="product_image">
                                    </div>
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Enter here" value="<?=$inputName; ?>" required="" />
                                    </div>
                                    <div class="form-group">
                                        <label>Cost</label>
                                        <input type="number" class="form-control" name="cost" min="0" placeholder="Enter here" value="<?=$inputCost; ?>" required="" />
                                    </div>
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" class="form-control" name="price" min="0" placeholder="Enter here" value="<?=$inputPrice; ?>" required="" />
                                    </div>
                                    <div class="form-group" id="category_container">
                                        <label>Category</label>
                                        <select name="category" class="form-control form-select" id="category" required="">
                                            <?php foreach ($categories as $key => $category): ?>
                                            <option value="<?=$category['name']; ?>" <?=$inputCategory==$category['id']?'selected':''; ?>><?=$category['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="save" class="btn btn-success">SAVE</button>
                                        <a href="products.php" class="btn btn-dark">CANCEL</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <table class="table product_list">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Barcode</th>
                                            <th>Type</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Category</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $key => $product): 
                                            $image = $product['image']?$product['image']:'product.png';
                                            $image = "../uploads/{$image}";
                                        ?>
                                        <tr data-name="<?=$product['name']; ?>">
                                            <td>
                                                <img src="<?=$image; ?>" class="img-thumbnail" width="50">
                                            </td>
                                            <td><?=$product['barcode']; ?></td>
                                            <td><?=$product['type']; ?></td>
                                            <td><?=$product['name']; ?></td>
                                            <td><?=$product['price']; ?></td>
                                            <td><?=$product['category']; ?></td>
                                            <td>
                                                <a href="products.php?id=<?=$product['id']; ?>&action=edit" class="btn btn-warning btn-sm">EDIT</a>
                                                |
                                                <?php if ($product['current_stock'] == 0): ?>
                                                <a href="products.php?id=<?=$product['id']; ?>&action=delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?');">DELETE</a>
                                                <?php else: ?>
                                                <a href="javascript:void(0);" class="" onclick="return alert('Product has stocks left.');">DELETE</a>
                                                <?php endif; ?>
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

        hideShowByType($('#type').val());
        $("#type").change(function(){
            var type = this.value;

            hideShowByType(type);
        });

        function hideShowByType(type) {
            if (type=="item") {
                $('#category_container').show();
            } else {
                $('#category_container').hide();
            }
        }

        $('.product-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $(".product_list tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });


        $('button#export-product').on('click', function() {
            var category = $('select.filter-category').val();

            window.location="product-pdf.php?filter_category="+category;
        });

        $('button#filter-product').on('click', function() {
            var category = $('select.filter-category').val();

            window.location="products.php?filter_category="+category;
        });

        $('button#filter-clear').on('click', function() {
            window.location="products.php";
        });
    });
    </script>

</body>
</html>
