<?php 
require_once 'header.php';

if (isset($_POST['add_group'])) {
    $name = sanitize($_POST['name']);
    $conn->query("INSERT INTO blood_groups (name) VALUES ('$name')");
    $_SESSION['success'] = "Blood group added successfully!";
    redirect('blood-groups.php');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM blood_groups WHERE id = $id");
    $_SESSION['success'] = "Blood group deleted successfully!";
    redirect('blood-groups.php');
}

$bloodGroups = $conn->query("SELECT bg.*, COUNT(d.id) as donor_count FROM blood_groups bg LEFT JOIN donors d ON bg.name = d.blood_group GROUP BY bg.id ORDER BY bg.name");
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Manage Blood Groups</h5>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle"></i> Add Blood Group
    </button>
</div>

<div class="row g-4">
    <?php while ($group = $bloodGroups->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <h2 class="text-white mb-0"><?php echo $group['name']; ?></h2>
                    </div>
                    <h4><?php echo $group['donor_count']; ?> Donors</h4>
                    <p class="text-muted mb-0">Registered with this blood type</p>
                </div>
                <div class="card-footer bg-white border-0 text-center">
                    <a href="?delete=<?php echo $group['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this blood group?')">
                        <i class="bi bi-trash"></i> Delete
                    </a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Blood Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Blood Group Name</label>
                        <select name="name" class="form-select" required>
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_group" class="btn btn-danger">Add Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>