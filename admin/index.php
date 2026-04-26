<?php require_once 'header.php'; ?>

<?php
$totalDonors = $conn->query("SELECT COUNT(*) as c FROM donors")->fetch_assoc()['c'];
$activeDonors = $conn->query("SELECT COUNT(*) as c FROM donors WHERE status = 'active'")->fetch_assoc()['c'];
$pendingRequests = $conn->query("SELECT COUNT(*) as c FROM blood_requests WHERE status = 'pending'")->fetch_assoc()['c'];
$totalRequests = $conn->query("SELECT COUNT(*) as c FROM blood_requests")->fetch_assoc()['c'];
$unreadContacts = $conn->query("SELECT COUNT(*) as c FROM contact_queries WHERE status = 'unread'")->fetch_assoc()['c'];

$recentDonors = $conn->query("SELECT * FROM donors ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
$recentRequests = $conn->query("SELECT * FROM blood_requests ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

$bloodStats = $conn->query("SELECT blood_group, COUNT(*) as count FROM donors WHERE status = 'active' GROUP BY blood_group")->fetch_all(MYSQLI_ASSOC);
?>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Donors</h6>
                        <h2 class="mb-0"><?php echo $totalDonors; ?></h2>
                    </div>
                    <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-people-fill text-danger fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <small class="text-success"><i class="bi bi-check-circle"></i> <?php echo $activeDonors; ?> active</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Pending Requests</h6>
                        <h2 class="mb-0"><?php echo $pendingRequests; ?></h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-clock-history text-warning fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="requests.php" class="text-decoration-none">View all requests <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Requests</h6>
                        <h2 class="mb-0"><?php echo $totalRequests; ?></h2>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-clipboard2-pulse text-info fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="requests.php" class="text-decoration-none">View details <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Unread Messages</h6>
                        <h2 class="mb-0"><?php echo $unreadContacts; ?></h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-envelope text-primary fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="contacts.php" class="text-decoration-none">View messages <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Donors</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Blood Group</th>
                                <th>City</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentDonors as $donor): ?>
                                <tr>
                                    <td><?php echo $donor['name']; ?></td>
                                    <td><span class="badge bg-danger"><?php echo $donor['blood_group']; ?></span></td>
                                    <td><?php echo $donor['city']; ?></td>
                                    <td>
                                        <?php if ($donor['status'] == 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Blood Requests</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Blood Group</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentRequests as $req): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo $req['patient_name']; ?></strong><br>
                                        <small class="text-muted"><?php echo $req['hospital_name']; ?></small>
                                    </td>
                                    <td><span class="badge bg-danger"><?php echo $req['blood_group']; ?></span></td>
                                    <td>
                                        <?php
                                        $statusClass = match($req['status']) {
                                            'pending' => 'warning',
                                            'approved' => 'info',
                                            'fulfilled' => 'success',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>"><?php echo ucfirst($req['status']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Blood Stock by Group</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach (getBloodGroups() as $bg): 
                        $count = 0;
                        foreach ($bloodStats as $stat) {
                            if ($stat['blood_group'] == $bg['name']) {
                                $count = $stat['count'];
                                break;
                            }
                        }
                    ?>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-danger bg-opacity-10 rounded text-center">
                                <h4 class="text-danger mb-1"><?php echo $bg['name']; ?></h4>
                                <p class="mb-0 text-muted"><?php echo $count; ?> donors</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>