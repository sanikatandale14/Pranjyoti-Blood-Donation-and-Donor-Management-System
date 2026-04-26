<?php 
require_once 'header.php';

if (isset($_POST['update_page'])) {
    $page_name = sanitize($_POST['page_name']);
    $page_title = sanitize($_POST['page_title']);
    $page_content = $_POST['page_content'];
    
    $stmt = $conn->prepare("UPDATE pages SET page_title = ?, page_content = ? WHERE page_name = ?");
    $stmt->bind_param("sss", $page_title, $page_content, $page_name);
    $stmt->execute();
    $_SESSION['success'] = "Page updated successfully!";
    redirect('pages.php');
}

$pages = $conn->query("SELECT * FROM pages ORDER BY page_name");
$pagesData = [];
while ($page = $pages->fetch_assoc()) {
    $pagesData[$page['page_name']] = $page;
}
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Manage Website Pages</h5>
</div>

<div class="row g-4">
    <?php foreach ($pagesData as $key => $page): ?>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-file-text text-danger me-2"></i><?php echo ucfirst($page['page_name']); ?> Page</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="page_name" value="<?php echo $key; ?>">
                        <div class="mb-3">
                            <label class="form-label">Page Title</label>
                            <input type="text" name="page_title" class="form-control" value="<?php echo $page['page_title']; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Page Content (HTML allowed)</label>
                            <textarea name="page_content" class="form-control" rows="10"><?php echo $page['page_content']; ?></textarea>
                        </div>
                        <button type="submit" name="update_page" class="btn btn-danger">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                        <a href="../<?php echo $key; ?>.php" target="_blank" class="btn btn-outline-secondary">
                            <i class="bi bi-eye"></i> View Page
                        </a>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'footer.php'; ?>