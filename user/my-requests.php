<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../index.php');
}

$user_id = $_SESSION['user_id'];
$requests = $conn->query("SELECT * FROM blood_requests WHERE contact_name = '{$_SESSION['user_name']}' OR contact_phone IN (SELECT phone FROM users WHERE id = $user_id) ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>.footer { background: #2c3e50; color: white; }</style>
</head>
<body class="bg-light">
    <?php include '../includes/header.php'; ?>
    
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-clipboard-check"></i> My Blood Requests</h2>
            <a href="request-blood.php" class="btn btn-danger"><i class="bi bi-plus-circle"></i> New Request</a>
        </div>
        
        <?php if ($requests->num_rows == 0): ?>
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h4 class="mt-3">No blood requests yet</h4>
                <p>Create a new request to find blood donors.</p>
                <a href="request-blood.php" class="btn btn-danger">Create Request</a>
            </div>
        <?php else: ?>
            <div class="card shadow">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-danger">
                                <tr>
                                    <th>Patient</th>
                                    <th>Blood Group</th>
                                    <th>Units</th>
                                    <th>Hospital</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $requests->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $row['patient_name']; ?></strong><br>
                                            <small class="text-muted">Age: <?php echo $row['patient_age']; ?></small>
                                        </td>
                                        <td><span class="badge bg-danger"><?php echo $row['blood_group']; ?></span></td>
                                        <td><?php echo $row['units_needed']; ?></td>
                                        <td>
                                            <?php echo $row['hospital_name']; ?><br>
                                            <small class="text-muted"><?php echo $row['hospital_address']; ?></small>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = match($row['status']) {
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'fulfilled' => 'success',
                                                'rejected' => 'danger',
                                                default => 'secondary'
                                            };
                                            $urgencyBadge = $row['urgency'] !== 'normal' ? ' <span class="badge bg-' . ($row['urgency'] == 'critical' ? 'dark' : 'orange') . '">' . ucfirst($row['urgency']) . '</span>' : '';
                                            ?>
                                            <span class="badge bg-<?php echo $statusClass; ?>"><?php echo ucfirst($row['status']); ?></span><?php echo $urgencyBadge; ?>
                                        </td>
                                        <td><small><?php echo date('d M Y', strtotime($row['created_at'])); ?></small></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>