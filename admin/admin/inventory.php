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

    $activeNav = 'inventory';


    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute(); 
    $categories = $stmt->fetchAll();

    $action = '';
    // CHECK IF CREATE FORM OR EDIT FORM
    if (isset($_GET['action']) && isset($_GET['id'])) {

        $action = $_GET['action'];
        $id = $_GET['id'];

        $query = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $query->execute([$id]);
        $selectedProduct = $query->fetch();

        if ($_GET['action'] == 'stock') {

            $formTitle = "UPDATE PRODUCT STOCK: [{$selectedProduct['name']}]";

        } 

    } else {

        $id = 0;
        $formTitle = "PLEASE SELECT PRODUCT";


    }
    // CHECK IF CREATE FORM OR EDIT FORM


    // CREATE OR UPDATE DATA TO DATABASE
    if (isset($_POST) && $_POST) {

        $id = $_POST['id'];
        $new_qty = $_POST['new_qty'];
        $old_qty = $_POST['old_qty'];

        // CREATE INVENTORY HISTORY
        $stmt = $conn->prepare("INSERT INTO inventory (product_id, old_qty, new_qty, note) VALUES (?,?,?,?)");
        $stmt->execute([$id, $old_qty, $new_qty, null]);

        // UPDATE PRODUCT CURRENT STOCK
        $stmt= $conn->prepare("UPDATE products SET current_stock = ? WHERE id = ?");
        $stmt->execute([$new_qty, $id]);


        echo "<script>alert('Product Stock Updated'); window.location='inventory.php?id={$id}&action=stock';</script>";
     
    }
    // CREATE OR UPDATE DATA TO DATABASE




    $filter_category = $filter_stock = '';
    if (isset($_GET['filter_category']) && isset($_GET['filter_stock'])) { 

        $filter_category = $_GET['filter_category'];
        $filter_stock = $_GET['filter_stock'];

        $conditions = [];


        if ($filter_category != 'all') {
            $conditions['category'] = $filter_category;
        }

        $stockWhere = '';
        if ($filter_stock != 'all') {

            $stockWhere .= "AND ";

            if ($filter_stock == 'no-stocks') {
                $stockWhere .= "current_stock <= 0";
            }

            if ($filter_stock == 'low-stocks') {
                $stockWhere .= "current_stock <= 5 AND current_stock > 0";
            }

            if ($filter_stock == 'high-stocks') {
                $stockWhere .= "current_stock >= 20";
            }

        }

        $where = '';
        foreach ($conditions as $column => $value) {
            $where .= " AND {$column} = '{$value}'";
        }
        
        $sql = "SELECT * FROM products WHERE type = 'item' " . $where . $stockWhere;

        $query = $conn->prepare($sql);
        $query->execute();
        $products = $query->fetchAll();

    } else {

        // LOAD FOR FORM SELECT AND TABLE
        $stmt = $conn->prepare("SELECT * FROM products WHERE type = 'item'");
        $stmt->execute(); 
        $products = $stmt->fetchAll();
        // LOAD FOR FORM SELECT AND TABLE

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
                    <h1 class="h2">Inventory (Items Only)</h1>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table product_list">
                                    <thead>
                                        <tr>
                                            <th>Barcode</th>
                                            <th id="pname">Name</th>
                                            <th>Category</th>
                                            <th>Cost</th>
                                            <th>Current Stock </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $key => $product): 

                                            $current_stock = $product['current_stock'];
                                            if ($current_stock <= 0) {
                                                $fstocks = 'no-stocks';
                                            } elseif ($current_stock <= 5 && $current_stock >= 1) {
                                                $fstocks = 'low-stocks';
                                            } elseif ($current_stock > 5) {
                                                $fstocks = 'high-stocks';
                                            }

                                        ?>
                                        <tr data-fdata="<?=$fstocks; ?> <?=$product['category']; ?>" data-fstock="<?=$fstocks; ?>" data-fcategory="<?=$product['category']; ?>">
                                            <td><?=$product['barcode']; ?></td>
                                            <td><?=$product['name']; ?></td>
                                            <td><?=$product['category']; ?></td>
                                            <td><?=$product['cost']; ?></td>
                                            <td><?=$product['current_stock']; ?></td>
                                            <td>
                                                <a href="inventory.php?id=<?=$product['id']; ?>&action=stock" class="btn btn-warning btn-sm">Update Stock</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5><?php echo $formTitle; ?></h5>

                                <?php if ($action == 'stock'): ?>

                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label>Old Quantity</label>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <input type="text" class="form-control" name="old_qty" placeholder="Enter here" value="<?=$selectedProduct['current_stock'];?>" readonly="" />
                                    </div>
                                    <div class="form-group">
                                        <label>New Quantity</label>
                                        <input type="text" class="form-control" name="new_qty" placeholder="<?=$selectedProduct['current_stock'];?>" value="" required="" />
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="save" class="btn btn-primary">SAVE</button>
                                        <a href="inventory.php" class="btn btn-dark">CANCEL</a>
                                    </div>
                                </form>

                                <?php endif; ?>
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
        $('.product-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $(".product_list tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        function sortTable(f,n){
            var rows = $('.product_list tbody tr').get();

            rows.sort(function(a, b) {

                var A = getVal(a);
                var B = getVal(b);

                if (A < B) {
                    return -1*f;
                }

                if (A > B) {
                    return 1*f;
                }

                return 0;
            });

            function getVal(elm) {
                var v = $(elm).children('td').eq(n).text().toUpperCase();
                if ($.isNumeric(v)){
                    v = parseInt(v,10);
                }
                return v;
            }

            $.each(rows, function(index, row) {
                $('.product_list').children('tbody').append(row);
            });
        }

        var pname = 1;

        $("#pname").click(function(){
            pname *= -1;
            var n = $(this).prevAll().length;
            sortTable(pname,n);
        });

        $('button#filter-inventory').on('click', function() {
            var fstocks = $('select.filter-stock').val();
            var fcategory = $('select.filter-category').val();

            window.location="inventory.php?filter_category="+fcategory+"&filter_stock="+fstocks;
        });

        $('button#filter-clear').on('click', function() {
            window.location="inventory.php";
        });
      
    });
    </script>
</body>
</html>
