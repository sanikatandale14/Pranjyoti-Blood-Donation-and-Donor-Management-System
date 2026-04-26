<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    redirect('../index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = sanitize($_POST['phone']);
    $gender = sanitize($_POST['gender']);
    $blood_group = sanitize($_POST['blood_group']);
    $city = sanitize($_POST['city']);
    
    $errors = [];
    
    if (empty($full_name) || empty($email) || empty($password)) {
        $errors[] = "Please fill in all required fields.";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    
    $checkEmail = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $errors[] = "Email already exists.";
    }
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, gender, blood_group, city) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $full_name, $email, $hashed_password, $phone, $gender, $blood_group, $city);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! Please login.";
            redirect('login.php');
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0"><i class="bi bi-person-plus"></i> Create Account</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $error): ?>
                                    <p class="mb-0"><?php echo $error; ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="full_name" class="form-control" required value="<?php echo isset($_POST['full_name']) ? $_POST['full_name'] : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" name="email" class="form-control" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password *</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password *</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select</option>
                                        <option value="Male" <?php echo isset($_POST['gender']) && $_POST['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo isset($_POST['gender']) && $_POST['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo isset($_POST['gender']) && $_POST['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Blood Group</label>
                                    <select name="blood_group" class="form-select">
                                        <option value="">Select</option>
                                        <?php foreach (getBloodGroups() as $bg): ?>
                                            <option value="<?php echo $bg['name']; ?>" <?php echo isset($_POST['blood_group']) && $_POST['blood_group'] == $bg['name'] ? 'selected' : ''; ?>><?php echo $bg['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" value="<?php echo isset($_POST['city']) ? $_POST['city'] : ''; ?>">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger btn-lg w-100">Register</button>
                                </div>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        <p class="text-center mb-0">
                            Already have an account? <a href="login.php" class="text-danger">Login here</a>
                        </p>
                        <p class="text-center mt-3 mb-0">
                            <a href="../index.php" class="text-danger"><i class="bi bi-house"></i> Back to Homepage</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>