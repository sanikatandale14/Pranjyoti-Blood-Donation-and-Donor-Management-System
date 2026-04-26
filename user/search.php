<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$blood_group = isset($_GET['blood_group']) ? sanitize($_GET['blood_group']) : '';
$city = isset($_GET['city']) ? sanitize($_GET['city']) : '';

$query = "SELECT * FROM donors WHERE status = 'active' AND is_available = 1";
$params = [];

if ($blood_group) {
    $query .= " AND blood_group = ?";
    $params[] = $blood_group;
}
if ($city) {
    $query .= " AND city LIKE ?";
    $params[] = "%$city%";
}

$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$donors = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Donors - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>.footer { background: #2c3e50; color: white; }</style>
</head>
<body class="bg-light">
    <?php include '../includes/header.php'; ?>
    
    <div class="bg-danger py-5 text-white text-center">
        <div class="container">
            <h1 class="display-4 fw-bold"><i class="bi bi-search"></i> Search Donors</h1>
            <p class="lead">Find blood donors in your area</p>
        </div>
    </div>
    
    <div class="container py-5">
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">All Blood Groups</option>
                            <?php foreach (getBloodGroups() as $bg): ?>
                                <option value="<?php echo $bg['name']; ?>" <?php echo $blood_group == $bg['name'] ? 'selected' : ''; ?>><?php echo $bg['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" placeholder="Enter city name" value="<?php echo $city; ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-danger w-100"><i class="bi bi-search"></i> Search</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mb-3">
            <h5><?php echo count($donors); ?> donor(s) found</h5>
        </div>
        
        <?php if (empty($donors)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No donors found matching your criteria. Try adjusting your search.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($donors as $donor): ?>
                    <div class="col-lg-6">
                        <div class="card shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1"><?php echo $donor['name']; ?></h5>
                                        <span class="badge bg-danger"><?php echo $donor['blood_group']; ?></span>
                                        <span class="badge bg-success">Available</span>
                                    </div>
                                    <div class="text-end">
                                        <i class="bi bi-geo-alt text-danger"></i> <?php echo $donor['city']; ?>
                                    </div>
                                </div>
                                <div class="row text-muted small">
                                    <div class="col-6">
                                        <p class="mb-1"><i class="bi bi-telephone me-2"></i><?php echo $donor['phone']; ?></p>
                                        <p class="mb-1"><i class="bi bi-envelope me-2"></i><?php echo $donor['email']; ?></p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><i class="bi bi-gender-ambiguous me-2"></i><?php echo $donor['gender']; ?></p>
                                        <p class="mb-1"><i class="bi bi-calendar me-2"></i>Age: <?php echo $donor['age']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="request-blood.php?donor_id=<?php echo $donor['id']; ?>" class="btn btn-danger btn-sm w-100">
                                    <i class="bi bi-droplet"></i> Request Blood from this Donor
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