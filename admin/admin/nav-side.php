<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?=$activeNav=='dashboard'?'active':''; ?>" href="dashboard.php">
                    <span data-feather="home"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$activeNav=='pos'?'active':''; ?>" href="pos.php">
                    <span data-feather="smartphone"></span>
                    POS
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$activeNav=='inventory'?'active':''; ?>" href="inventory.php">
                    <span data-feather="list"></span>
                    Inventory
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$activeNav=='transactions'?'active':''; ?>" href="transactions.php">
                    <span data-feather="file"></span>
                    Transactions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$activeNav=='products'?'active':''; ?>" href="products.php">
                    <span data-feather="shopping-cart"></span>
                    Products and Services
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$activeNav=='category'?'active':''; ?>" href="category.php">
                    <span data-feather="bookmark"></span>
                    Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$activeNav=='reservations'?'active':''; ?>" href="reservations.php">
                    <span data-feather="calendar"></span>
                    Reservations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$activeNav=='users'?'active':''; ?>" href="users.php">
                    <span data-feather="users"></span>
                    Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$activeNav=='reports'?'active':''; ?>" href="reports.php">
                    <span data-feather="bar-chart-2"></span>
                    Reports
                </a>
            </li>
        </ul>
    </div>
</nav>