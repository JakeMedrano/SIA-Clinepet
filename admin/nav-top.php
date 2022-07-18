<nav class="navbar navbar-expand navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <img src="assets/images/logo.png" width="100px">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample02">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pet-store.php">Pet Store</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reservation.php">Reservation</a>
            </li>
            <?php if (isset($_SESSION['customer'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
            <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="account.php">Login</a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
