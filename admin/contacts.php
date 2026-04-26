<?php 
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdminLoggedIn()) {
    redirect('login.php');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM contact_queries WHERE id = $id");
    $_SESSION['success'] = "Message deleted successfully!";
    redirect('contacts.php');
}

if (isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = sanitize($_POST['status']);
    $conn->query("UPDATE contact_queries SET status = '$status' WHERE id = $id");
    $_SESSION['success'] = "Status updated!";
    redirect('contacts.php');
}

$contacts = $conn->query("SELECT * FROM contact_queries ORDER BY created_at DESC");
$unreadCount = $conn->query("SELECT COUNT(*) as c FROM contact_queries WHERE status = 'unread'")->fetch_assoc()['c'];

require_once 'header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0">Contact Queries</h5>
        <?php if ($unreadCount > 0): ?>
            <small class="text-danger"><?php echo $unreadCount; ?> unread messages</small>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <?php while ($contact = $contacts->fetch_assoc()): ?>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 <?php echo $contact['status'] == 'unread' ? 'border-start border-danger border-4' : ''; ?>">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo $contact['name']; ?></strong>
                        <?php if ($contact['status'] == 'unread'): ?>
                            <span class="badge bg-danger ms-2">New</span>
                        <?php endif; ?>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
                                    <input type="hidden" name="status" value="read">
                                    <button type="submit" name="update_status" class="dropdown-item">Mark as Read</button>
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="?delete=<?php echo $contact['id']; ?>" onclick="return confirm('Delete this message?')">Delete</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-envelope me-1"></i> <?php echo $contact['email']; ?>
                            <?php if ($contact['phone']): ?>
                                | <i class="bi bi-telephone me-1"></i> <?php echo $contact['phone']; ?>
                            <?php endif; ?>
                        </small>
                    </div>
                    <?php if ($contact['subject']): ?>
                        <h6><?php echo $contact['subject']; ?></h6>
                    <?php endif; ?>
                    <p class="card-text"><?php echo nl2br($contact['message']); ?></p>
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i> <?php echo date('d M Y, h:i A', strtotime($contact['created_at'])); ?>
                    </small>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    
    <?php if ($contacts->num_rows == 0): ?>
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h4 class="mt-3">No messages yet</h4>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>