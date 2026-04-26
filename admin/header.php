<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdminLoggedIn()) {
    redirect('login.php');
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 250px; }
        body { background: #f8f9fa; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #2c3e50;
            color: white;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .sidebar .nav-link i { width: 25px; }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .top-bar {
            background: white;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <button class="btn btn-dark d-lg-none position-fixed top-0 start-0 m-2" onclick="document.querySelector('.sidebar').classList.toggle('show')">
        <i class="bi bi-list"></i>
    </button>

    <nav class="sidebar">
        <div class="p-4 text-center border-bottom border-secondary">
            <a href="index.php" class="text-white text-decoration-none">
                <i class="bi bi-droplet-fill text-danger fs-2"></i>
                <h5 class="mt-2 mb-0">Pranjyoti</h5>
                <small>Admin Panel</small>
            </a>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" href="index.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'donors.php' ? 'active' : ''; ?>" href="donors.php">
                    <i class="bi bi-people"></i> Manage Donors
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'blood-groups.php' ? 'active' : ''; ?>" href="blood-groups.php">
                    <i class="bi bi-droplet"></i> Blood Groups
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'requests.php' ? 'active' : ''; ?>" href="requests.php">
                    <i class="bi bi-clipboard2-pulse"></i> Blood Requests
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'contacts.php' ? 'active' : ''; ?>" href="contacts.php">
                    <i class="bi bi-envelope"></i> Contact Queries
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'pages.php' ? 'active' : ''; ?>" href="pages.php">
                    <i class="bi bi-file-text"></i> Manage Pages
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link" href="../index.php" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> View Website
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <div class="main-content">
        <div class="top-bar d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><?php echo str_replace('.php', '', ucfirst($currentPage)); ?></h4>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-3 text-muted">
                    <i class="bi bi-person-circle"></i> <?php echo $_SESSION['admin_name']; ?>
                </span>
            </div>
        </div>
        <div class="p-4">