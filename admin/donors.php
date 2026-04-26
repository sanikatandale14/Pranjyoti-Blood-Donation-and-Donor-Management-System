<?php 
require_once 'header.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM donors WHERE id = $id");
    $_SESSION['success'] = "Donor deleted successfully!";
    redirect('donors.php');
}

if (isset($_POST['add_donor'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $blood_group = sanitize($_POST['blood_group']);
    $gender = sanitize($_POST['gender']);
    $age = (int)$_POST['age'];
    $city = sanitize($_POST['city']);
    $address = sanitize($_POST['address']);
    
    $stmt = $conn->prepare("INSERT INTO donors (name, email, phone, blood_group, gender, age, city, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiss", $name, $email, $phone, $blood_group, $gender, $age, $city, $address);
    $stmt->execute();
    $_SESSION['success'] = "Donor added successfully!";
    redirect('donors.php');
}

if (isset($_POST['update_donor'])) {
    $id = (int)$_POST['id'];
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $blood_group = sanitize($_POST['blood_group']);
    $gender = sanitize($_POST['gender']);
    $age = (int)$_POST['age'];
    $city = sanitize($_POST['city']);
    $address = sanitize($_POST['address']);
    $status = sanitize($_POST['status']);
    
    $stmt = $conn->prepare("UPDATE donors SET name=?, email=?, phone=?, blood_group=?, gender=?, age=?, city=?, address=?, status=? WHERE id=?");
    $stmt->bind_param("sssssisssi", $name, $email, $phone, $blood_group, $gender, $age, $city, $address, $status, $id);
    $stmt->execute();
    $_SESSION['success'] = "Donor updated successfully!";
    redirect('donors.php');
}

$donors = $conn->query("SELECT * FROM donors ORDER BY created_at DESC");
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Manage Donors</h5>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle"></i> Add New Donor
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Blood Group</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $donors->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><span class="badge bg-danger"><?php echo $row['blood_group']; ?></span></td>
                            <td><?php echo $row['city']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        
                        <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Donor</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="">
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" name="phone" class="form-control" value="<?php echo $row['phone']; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Blood Group</label>
                                                    <select name="blood_group" class="form-select" required>
                                                        <?php foreach (getBloodGroups() as $bg): ?>
                                                            <option value="<?php echo $bg['name']; ?>" <?php echo $row['blood_group'] == $bg['name'] ? 'selected' : ''; ?>><?php echo $bg['name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Gender</label>
                                                    <select name="gender" class="form-select">
                                                        <option value="Male" <?php echo $row['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                                        <option value="Female" <?php echo $row['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Age</label>
                                                    <input type="number" name="age" class="form-control" value="<?php echo $row['age']; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">City</label>
                                                    <input type="text" name="city" class="form-control" value="<?php echo $row['city']; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="active" <?php echo $row['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                                        <option value="inactive" <?php echo $row['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Address</label>
                                                    <textarea name="address" class="form-control" rows="2"><?php echo $row['address']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="update_donor" class="btn btn-primary">Update Donor</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Donor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone *</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Blood Group *</label>
                            <select name="blood_group" class="form-select" required>
                                <option value="">Select</option>
                                <?php foreach (getBloodGroups() as $bg): ?>
                                    <option value="<?php echo $bg['name']; ?>"><?php echo $bg['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Age *</label>
                            <input type="number" name="age" class="form-control" min="18" max="65" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City *</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_donor" class="btn btn-danger">Add Donor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>