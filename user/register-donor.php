<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../index.php');
}

$user_id = $_SESSION['user_id'];
$checkDonor = $conn->query("SELECT * FROM donors WHERE user_id = $user_id");
if ($checkDonor->num_rows > 0) {
    redirect('profile.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $blood_group = sanitize($_POST['blood_group']);
    $gender = sanitize($_POST['gender']);
    $age = (int)$_POST['age'];
    $city = sanitize($_POST['city']);
    $address = sanitize($_POST['address']);
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    
    $stmt = $conn->prepare("INSERT INTO donors (user_id, name, email, phone, blood_group, gender, age, city, address, is_available) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssisss", $user_id, $name, $email, $phone, $blood_group, $gender, $age, $city, $address, $is_available);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Donor registration successful!";
        redirect('profile.php');
    } else {
        $error = "Registration failed. Please try again.";
    }
}

$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Registration - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>.footer { background: #2c3e50; color: white; }</style>
</head>
<body class="bg-light">
    <?php include '../includes/header.php'; ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0"><i class="bi bi-heart-pulse"></i> Donor Registration</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="name" class="form-control" required value="<?php echo $user['full_name']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" required value="<?php echo $user['email']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="text" name="phone" class="form-control" required value="<?php echo $user['phone'] ?? ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Blood Group *</label>
                                    <select name="blood_group" class="form-select" required>
                                        <option value="">Select</option>
                                        <?php foreach (getBloodGroups() as $bg): ?>
                                            <option value="<?php echo $bg['name']; ?>" <?php echo $user['blood_group'] == $bg['name'] ? 'selected' : ''; ?>><?php echo $bg['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Age *</label>
                                    <input type="number" name="age" class="form-control" min="18" max="65" required value="<?php echo isset($_POST['age']) ? $_POST['age'] : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City *</label>
                                    <input type="text" name="city" class="form-control" required value="<?php echo $user['city'] ?? ''; ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="2"><?php echo $user['address'] ?? ''; ?></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_available" class="form-check-input" id="is_available" checked>
                                        <label class="form-check-label" for="is_available">
                                            I am available to donate blood
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger btn-lg w-100">
                                        <i class="bi bi-heart-pulse"></i> Register as Donor
                                    </button>
                                </div>
                            </div>
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