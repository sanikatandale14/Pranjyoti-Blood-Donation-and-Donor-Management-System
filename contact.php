<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    $stmt = $conn->prepare("INSERT INTO contact_queries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Thank you for contacting us! We will get back to you soon.";
        redirect('contact.php');
    } else {
        $error = "Failed to send message. Please try again.";
    }
}

$page = $conn->query("SELECT * FROM pages WHERE page_name = 'contact'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?php echo SITE_NAME; ?></title>
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
            <h1 class="display-4 fw-bold"><i class="bi bi-envelope"></i> Contact Us</h1>
            <p class="lead">We'd love to hear from you</p>
        </div>
    </div>
    
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-5 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <h4 class="mb-4">Get in Touch</h4>
                        <div class="mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6>Address</h6>
                                    <p class="text-muted mb-0">Blood Bank Center, Medical Road<br>City - 123456</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6>Phone</h6>
                                    <p class="text-muted mb-0">+91 9876543210</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6>Email</h6>
                                    <p class="text-muted mb-0">info@pranjyoti.org</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex">
                                <div class="me-3">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6>Working Hours</h6>
                                    <p class="text-muted mb-0">Mon - Sat: 8:00 AM - 8:00 PM<br>Sun: Emergency Only</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Send us a Message</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Your Name *</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Subject</label>
                                    <input type="text" name="subject" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message *</label>
                                    <textarea name="message" class="form-control" rows="5" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger btn-lg w-100">
                                        <i class="bi bi-send"></i> Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>