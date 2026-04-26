<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page = $conn->query("SELECT * FROM pages WHERE page_name = 'about'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .footer { background: #2c3e50; color: white; }
    </style>
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>
    
    <div class="bg-danger py-5 text-white text-center">
        <div class="container">
            <h1 class="display-4 fw-bold"><i class="bi bi-info-circle"></i> About Us</h1>
            <p class="lead">Learn more about our mission</p>
        </div>
    </div>
    
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=600" alt="About Blood Donation" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6">
                <?php echo $page['page_content'] ?? '<h2>Welcome to Pranjyoti Blood Bank</h2>'; ?>
                <p class="text-muted mt-3">Our mission is to bridge the gap between blood donors and patients in need. We believe that every drop of blood donated can save precious lives.</p>
                <div class="row g-4 mt-4">
                    <div class="col-6">
                        <div class="text-center p-3 bg-white rounded shadow-sm">
                            <i class="bi bi-heart-pulse text-danger fs-1"></i>
                            <h4 class="mt-2">24/7</h4>
                            <p class="text-muted mb-0">Emergency Support</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-white rounded shadow-sm">
                            <i class="bi bi-shield-check text-danger fs-1"></i>
                            <h4 class="mt-2">100%</h4>
                            <p class="text-muted mb-0">Safe Process</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-12">
                <h3 class="text-center mb-4">Why Donate Blood?</h3>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-people-fill text-danger fs-1"></i>
                                <h5 class="mt-3">Save Lives</h5>
                                <p class="text-muted">One donation can save up to three lives. Your contribution makes a real difference.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-heart text-danger fs-1"></i>
                                <h5 class="mt-3">Health Benefits</h5>
                                <p class="text-muted">Donating blood can improve your cardiovascular health and reduce the risk of certain diseases.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-emoji-smile text-danger fs-1"></i>
                                <h5 class="mt-3">Community Impact</h5>
                                <p class="text-muted">Your donation helps your community members in their time of need.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>