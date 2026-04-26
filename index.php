<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Home';
$stats = [];
$bloodGroupResult = $conn->query("SELECT blood_group, COUNT(*) as count FROM donors WHERE status = 'active' GROUP BY blood_group");
while ($row = $bloodGroupResult->fetch_assoc()) {
    $stats[$row['blood_group']] = $row['count'];
}
$totalDonors = $conn->query("SELECT COUNT(*) as count FROM donors WHERE status = 'active'")->fetch_assoc()['count'];
$totalRequests = $conn->query("SELECT COUNT(*) as count FROM blood_requests WHERE status = 'pending'")->fetch_assoc()['count'];
$totalBloodGroups = $conn->query("SELECT COUNT(*) as count FROM blood_groups")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary-red: #dc3545; --dark-red: #b02a37; }
        .navbar-brand { font-weight: bold; color: var(--primary-red) !important; }
        .hero-slider { height: 500px; overflow: hidden; }
        .slide { height: 500px; background-size: cover; background-position: center; position: relative; }
        .slide::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)); }
        .slide-content { position: relative; z-index: 1; height: 100%; display: flex; align-items: center; justify-content: center; text-align: center; color: white; }
        .stats-card { border: none; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .stats-card:hover { transform: translateY(-5px); }
        .blood-type-card { padding: 20px; border-radius: 10px; text-align: center; background: linear-gradient(135deg, var(--primary-red), var(--dark-red)); color: white; }
        .cta-section { background: linear-gradient(135deg, var(--primary-red), var(--dark-red)); color: white; }
        .footer { background: #2c3e50; color: white; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div id="heroSlider" class="carousel slide hero-slider" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=1600');">
                    <div class="slide-content">
                        <div>
                            <h1 class="display-3 fw-bold">Save Lives, Donate Blood</h1>
                            <p class="lead">Your donation can save up to three lives</p>
                            <a href="user/register.php" class="btn btn-light btn-lg mt-3">Become a Donor</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=1600');">
                    <div class="slide-content">
                        <div>
                            <h1 class="display-3 fw-bold">Every Drop Counts</h1>
                            <p class="lead">Join our community of heroes</p>
                            <a href="user/search.php" class="btn btn-light btn-lg mt-3">Search Donors</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1600');">
                    <div class="slide-content">
                        <div>
                            <h1 class="display-3 fw-bold">Together We Can</h1>
                            <p class="lead">Emergency blood requests handled 24/7</p>
                            <a href="user/request-blood.php" class="btn btn-light btn-lg mt-3">Request Blood</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">Our Impact</h2>
                    <p class="text-muted">Making a difference in our community</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-people-fill text-danger fs-1 mb-3"></i>
                            <h3 class="fw-bold text-danger"><?php echo $totalDonors; ?></h3>
                            <p class="text-muted mb-0">Registered Donors</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-droplet-fill text-danger fs-1 mb-3"></i>
                            <h3 class="fw-bold text-danger"><?php echo $totalRequests; ?></h3>
                            <p class="text-muted mb-0">Pending Requests</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-heart-pulse-fill text-danger fs-1 mb-3"></i>
                            <h3 class="fw-bold text-danger"><?php echo $totalBloodGroups; ?></h3>
                            <p class="text-muted mb-0">Blood Types Available</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">Blood Stock Availability</h2>
                    <p class="text-muted">Current inventory by blood type</p>
                </div>
            </div>
            <div class="row g-4">
                <?php foreach (getBloodGroups() as $bg): ?>
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="blood-type-card">
                            <h2 class="mb-0"><?php echo $bg['name']; ?></h2>
                            <p class="mb-0 mt-2"><?php echo isset($stats[$bg['name']]) ? $stats[$bg['name']] : 0; ?> donors</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">How It Works</h2>
                    <div class="d-flex mb-4">
                        <div class="me-3"><div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">1</div></div>
                        <div>
                            <h5>Register as a Donor</h5>
                            <p class="text-muted mb-0">Sign up and fill in your details including blood type</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3"><div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">2</div></div>
                        <div>
                            <h5>Search or Get Found</h5>
                            <p class="text-muted mb-0">Search for donors by blood type and location</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3"><div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">3</div></div>
                        <div>
                            <h5>Save Lives</h5>
                            <p class="text-muted mb-0">Connect with those in need and make a difference</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1615461066841-6116e61058f4?w=600" alt="Blood Donation" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Ready to Save Lives?</h2>
            <p class="mb-4">Join thousands of donors who are making a difference every day</p>
            <a href="user/register.php" class="btn btn-light btn-lg me-2">Register Now</a>
            <a href="user/request-blood.php" class="btn btn-outline-light btn-lg">Request Blood</a>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>