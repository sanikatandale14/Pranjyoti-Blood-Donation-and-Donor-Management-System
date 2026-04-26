<?php 
$currentPage = basename($_SERVER['PHP_SELF']);
// Use a fixed base path since we know the port
$basePath = '/pranjyoti/';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo $basePath; ?>index.php">
            <i class="bi bi-droplet-fill text-danger"></i> Pranjyoti
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'about.php' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $basePath; ?>user/donors.php">Donors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $basePath; ?>user/request-blood.php">Request Blood</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'contact.php' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>contact.php">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-danger fw-bold" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo $basePath; ?>user/profile.php">My Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo $basePath; ?>user/my-requests.php">My Requests</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo $basePath; ?>user/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-danger me-2" href="<?php echo $basePath; ?>user/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger" href="<?php echo $basePath; ?>user/register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>