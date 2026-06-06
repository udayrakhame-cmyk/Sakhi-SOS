<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('/dashboard.php');
}

require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h3 class="text-center mb-4 text-danger"><i class="bi bi-shield-lock"></i> User Login</h3>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/api/auth.php" method="POST">
                    <div class="mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-danger w-100 py-2 fw-bold">Login</button>
                </form>
                
                <div class="text-center mt-4 pt-3 border-top border-secondary">
                    <p class="text-muted">Don't have an account? <a href="<?= BASE_URL ?>/register.php" class="text-danger fw-bold text-decoration-none">Register here</a></p>
                    <p class="text-muted mt-3"><a href="<?= BASE_URL ?>/admin/index.php" class="text-secondary text-decoration-none"><i class="bi bi-person-badge"></i> Admin Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
