<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../index.php');
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$donor = $conn->query("SELECT * FROM donors WHERE user_id = $user_id")->fetch_assoc();

if (!$donor) {
    redirect('register-donor.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $full_name = sanitize($_POST['full_name']);
        $phone = sanitize($_POST['phone']);
        $blood_group = sanitize($_POST['blood_group']);
        $city = sanitize($_POST['city']);
        $address = sanitize($_POST['address']);
        
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, blood_group = ?, city = ?, address = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $full_name, $phone, $blood_group, $city, $address, $user_id);
        $stmt->execute();
        
        $stmt2 = $conn->prepare("UPDATE donors SET name = ?, phone = ?, blood_group = ?, city = ?, address = ? WHERE user_id = ?");
        $stmt2->bind_param("sssssi", $full_name, $phone, $blood_group, $city, $address, $user_id);
        $stmt2->execute();
        
        $_SESSION['success'] = "Profile updated successfully!";
        redirect('profile.php');
    }
    
    if (isset($_POST['update_availability'])) {
        $is_available = $_POST['is_available'] == 1 ? 1 : 0;
        $conn->query("UPDATE donors SET is_available = $is_available WHERE user_id = $user_id");
        $_SESSION['success'] = "Availability status updated!";
        redirect('profile.php');
    }
    
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (!password_verify($current_password, $user['password'])) {
            $_SESSION['error'] = "Current password is incorrect.";
        } elseif ($new_password !== $confirm_password) {
            $_SESSION['error'] = "New passwords do not match.";
        } elseif (strlen($new_password) < 6) {
            $_SESSION['error'] = "Password must be at least 6 characters.";
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password = '$hashed' WHERE id = $user_id");
            $_SESSION['success'] = "Password changed successfully!";
        }
        redirect('profile.php');
    }
}

$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$donor = $conn->query("SELECT * FROM donors WHERE user_id = $user_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>.footer { background: #2c3e50; color: white; }</style>
</head>
<body class="bg-light">
    <?php include '../includes/header.php'; ?>
    
    <div class="container py-5">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                            <i class="bi bi-person-fill text-white" style="font-size: 50px;"></i>
                        </div>
                        <h4><?php echo $donor['name']; ?></h4>
                        <p class="text-muted"><?php echo $donor['email']; ?></p>
                        <span class="badge bg-danger fs-6"><?php echo $donor['blood_group']; ?></span>
                        <hr>
                        <div class="d-flex justify-content-center mb-2">
                            <span class="me-2">Availability:</span>
                            <?php if ($donor['is_available']): ?>
                                <span class="badge bg-success">Available</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Not Available</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Quick Info</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-telephone text-danger me-2"></i> <?php echo $donor['phone']; ?></li>
                            <li class="mb-2"><i class="bi bi-geo-alt text-danger me-2"></i> <?php echo $donor['city']; ?></li>
                            <li class="mb-2"><i class="bi bi-gender-ambiguous text-danger me-2"></i> <?php echo $donor['gender']; ?></li>
                            <li><i class="bi bi-calendar text-danger me-2"></i> Age: <?php echo $donor['age']; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person-lines"></i> Edit Profile</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" value="<?php echo $donor['name']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?php echo $donor['email']; ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo $donor['phone']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Blood Group</label>
                                    <select name="blood_group" class="form-select" required>
                                        <?php foreach (getBloodGroups() as $bg): ?>
                                            <option value="<?php echo $bg['name']; ?>" <?php echo $donor['blood_group'] == $bg['name'] ? 'selected' : ''; ?>><?php echo $bg['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" value="<?php echo $donor['city']; ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="2"><?php echo $donor['address'] ?? ''; ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-toggle-on"></i> Availability Status</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="update_availability" value="1">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_available" class="form-check-input" id="availabilitySwitch" value="1" <?php echo $donor['is_available'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="availabilitySwitch">
                                    <?php echo $donor['is_available'] ? 'You are available to donate blood' : 'You are not available to donate'; ?>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-danger mt-3">Update Status</button>
                        </form>
                    </div>
                </div>
                
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-lock"></i> Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="change_password" value="1">
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>