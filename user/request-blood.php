<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$donor_id = isset($_GET['donor_id']) ? (int)$_GET['donor_id'] : 0;
$donor = $donor_id ? $conn->query("SELECT * FROM donors WHERE id = $donor_id")->fetch_assoc() : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_name = sanitize($_POST['patient_name']);
    $patient_age = (int)$_POST['patient_age'];
    $blood_group = sanitize($_POST['blood_group']);
    $units_needed = (int)$_POST['units_needed'];
    $hospital_name = sanitize($_POST['hospital_name']);
    $hospital_address = sanitize($_POST['hospital_address']);
    $contact_name = sanitize($_POST['contact_name']);
    $contact_phone = sanitize($_POST['contact_phone']);
    $reason = sanitize($_POST['reason']);
    $urgency = sanitize($_POST['urgency']);
    
    $stmt = $conn->prepare("INSERT INTO blood_requests (patient_name, patient_age, blood_group, units_needed, hospital_name, hospital_address, contact_name, contact_phone, reason, urgency) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisiisssss", $patient_name, $patient_age, $blood_group, $units_needed, $hospital_name, $hospital_address, $contact_name, $contact_phone, $reason, $urgency);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Blood request submitted successfully! We will contact you soon.";
        redirect('my-requests.php');
    } else {
        $error = "Failed to submit request. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Blood - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>.footer { background: #2c3e50; color: white; }</style>
</head>
<body class="bg-light">
    <?php include '../includes/header.php'; ?>
    
    <div class="bg-danger py-5 text-white text-center">
        <div class="container">
            <h1 class="display-4 fw-bold"><i class="bi bi-droplet"></i> Request Blood</h1>
            <p class="lead">Fill out the form to request blood donation</p>
        </div>
    </div>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($donor): ?>
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i> Requesting blood from: <strong><?php echo $donor['name']; ?></strong> (<?php echo $donor['blood_group']; ?>)
                    </div>
                <?php endif; ?>
                
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Blood Request Form</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <h6 class="text-muted mb-3">Patient Information</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Patient Name *</label>
                                    <input type="text" name="patient_name" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Patient Age *</label>
                                    <input type="number" name="patient_age" class="form-control" min="1" max="120" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Blood Group *</label>
                                    <select name="blood_group" class="form-select" required>
                                        <option value="">Select</option>
                                        <?php foreach (getBloodGroups() as $bg): ?>
                                            <option value="<?php echo $bg['name']; ?>" <?php echo $donor ? ($donor['blood_group'] == $bg['name'] ? 'selected' : '') : ''; ?>><?php echo $bg['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Units Needed *</label>
                                    <input type="number" name="units_needed" class="form-control" min="1" value="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Urgency Level</label>
                                    <select name="urgency" class="form-select">
                                        <option value="normal">Normal</option>
                                        <option value="urgent">Urgent</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>
                            </div>
                            
                            <h6 class="text-muted mb-3">Hospital Information</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Hospital Name *</label>
                                    <input type="text" name="hospital_name" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Hospital Address</label>
                                    <textarea name="hospital_address" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            
                            <h6 class="text-muted mb-3">Contact Information</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Your Name *</label>
                                    <input type="text" name="contact_name" class="form-control" required value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Your Phone *</label>
                                    <input type="text" name="contact_phone" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Reason / Additional Notes</label>
                                <textarea name="reason" class="form-control" rows="3" placeholder="Please provide any additional information..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                <i class="bi bi-send"></i> Submit Request
                            </button>
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