<?php 
require_once 'header.php';

if (isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = sanitize($_POST['status']);
    $conn->query("UPDATE blood_requests SET status = '$status' WHERE id = $id");
    $_SESSION['success'] = "Request status updated!";
    redirect('requests.php');
}

$requests = $conn->query("SELECT br.* 
    FROM blood_requests br 
    ORDER BY br.created_at DESC");
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Blood Requests</h5>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Patient</th>
                        <th>Blood Group</th>
                        <th>Units</th>
                        <!-- <th>Hospital</th> -->
                        <th>Contact</th>
                        <th>Urgency</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
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
                            <!-- <td>
                                <small><?php echo $row['hospital_name'] ?: 'N/A'; ?></small><br>
                                <small class="text-muted"><?php echo substr($row['hospital_address'] ?: '', 0, 30); ?>...</small>
                            </td> -->
                            <td>
                                <small><?php echo $row['contact_name']; ?></small><br>
                                <small class="text-muted"><?php echo $row['contact_phone']; ?></small>
                            </td>
                            <td>
                                <?php
                                $urgencyClass = match($row['urgency']) {
                                    'critical' => 'danger',
                                    'urgent' => 'warning',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?php echo $urgencyClass; ?>"><?php echo ucfirst($row['urgency']); ?></span>
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
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>"><?php echo ucfirst($row['status']); ?></span>
                            </td>
                            <td><small><?php echo date('d M Y', strtotime($row['created_at'])); ?></small></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id']; ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <div class="modal fade" id="viewModal<?php echo $row['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Blood Request Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <strong>Patient Name:</strong><br>
                                                <?php echo $row['patient_name']; ?>
                                            </div>
                                            <div class="col-6">
                                                <strong>Patient Age:</strong><br>
                                                <?php echo $row['patient_age']; ?> years
                                            </div>
                                            <div class="col-6">
                                                <strong>Blood Group:</strong><br>
                                                <span class="badge bg-danger"><?php echo $row['blood_group']; ?></span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Units Needed:</strong><br>
                                                <?php echo $row['units_needed']; ?>
                                            </div>
                                            <div class="col-6">
                                                <strong>Hospital:</strong><br>
                                                <?php echo $row['hospital_name'] ?: 'N/A'; ?>
                                            </div>
                                            <div class="col-6">
                                                <strong>Urgency:</strong><br>
                                                <span class="badge bg-<?php echo $urgencyClass; ?>"><?php echo ucfirst($row['urgency']); ?></span>
                                            </div>
                                            <div class="col-12">
                                                <strong>Hospital Address:</strong><br>
                                                <?php echo $row['hospital_address'] ?: 'N/A'; ?>
                                            </div>
                                            <div class="col-6">
                                                <strong>Contact Name:</strong><br>
                                                <?php echo $row['contact_name']; ?>
                                            </div>
                                            <div class="col-6">
                                                <strong>Contact Phone:</strong><br>
                                                <?php echo $row['contact_phone']; ?>
                                            </div>
                                            <div class="col-12">
                                                <strong>Reason / Notes:</strong><br>
                                                <?php echo $row['reason'] ?: 'No notes provided'; ?>
                                            </div>
                                            <div class="col-12">
                                                <hr>
                                                <strong>Update Status:</strong>
                                                <form method="POST" action="" class="d-flex gap-2 mt-2">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <select name="status" class="form-select">
                                                        <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="approved" <?php echo $row['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                                        <option value="fulfilled" <?php echo $row['status'] == 'fulfilled' ? 'selected' : ''; ?>>Fulfilled</option>
                                                        <option value="rejected" <?php echo $row['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                    </select>
                                                    <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>