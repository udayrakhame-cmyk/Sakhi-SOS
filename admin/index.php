<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (isAdminLoggedIn()) {
    redirect('/admin/dashboard.php');
}

require_once '../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg p-4 bg-dark">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock-fill text-danger" style="font-size: 3rem;"></i>
                    <h3 class="text-white mt-2">Admin Portal</h3>
                </div>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/api/auth.php" method="POST">
                    <div class="mb-3">
                        <label class="text-light">Username</label>
                        <input type="text" name="username" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <div class="mb-4">
                        <label class="text-light">Password</label>
                        <input type="password" name="password" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <button type="submit" name="admin_login" class="btn btn-danger w-100 py-2 fw-bold">Secure Login</button>
                </form>
                
                <div class="text-center mt-4 border-top border-secondary pt-3">
                    <a href="<?= BASE_URL ?>/index.php" class="text-muted text-decoration-none"><i class="bi bi-arrow-left"></i> Back to User Portal</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
