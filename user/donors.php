<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$donors = $conn->query("SELECT * FROM donors WHERE status = 'active' AND is_available = 1 ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Donors - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>.footer { background: #2c3e50; color: white; }</style>
</head>
<body class="bg-light">
    <?php include '../includes/header.php'; ?>
    
    <div class="bg-danger py-5 text-white text-center">
        <div class="container">
            <h1 class="display-4 fw-bold"><i class="bi bi-people-fill"></i> Our Donors</h1>
            <p class="lead">Meet our heroes who save lives</p>
        </div>
    </div>
    
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5><?php echo count($donors); ?> Active Donors</h5>
            </div>
            <div class="col-md-6 text-end">
                <a href="search.php" class="btn btn-outline-danger"><i class="bi bi-search"></i> Advanced Search</a>
            </div>
        </div>
        
        <?php if (empty($donors)): ?>
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="mt-3">No donors registered yet</h4>
                <p>Be the first to register as a donor!</p>
                <a href="register-donor.php" class="btn btn-danger">Register as Donor</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($donors as $donor): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card shadow h-100 donor-card">
                            <div class="card-body text-center">
                                <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="bi bi-person-fill text-white" style="font-size: 35px;"></i>
                                </div>
                                <h5 class="card-title"><?php echo $donor['name']; ?></h5>
                                <span class="badge bg-danger fs-6"><?php echo $donor['blood_group']; ?></span>
                                <hr>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-geo-alt text-danger me-1"></i> <?php echo $donor['city']; ?>
                                </p>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-telephone text-danger me-1"></i> <?php echo $donor['phone']; ?>
                                </p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-gender-ambiguous text-danger me-1"></i> <?php echo $donor['gender']; ?>, <?php echo $donor['age']; ?> years
                                </p>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="request-blood.php?donor_id=<?php echo $donor['id']; ?>" class="btn btn-danger btn-sm w-100">
                                    <i class="bi bi-droplet"></i> Request Blood
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>