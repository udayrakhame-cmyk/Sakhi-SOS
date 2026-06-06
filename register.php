<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('/dashboard.php');
}

require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h3 class="text-center mb-4 text-danger"><i class="bi bi-person-plus"></i> Create Account</h3>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/api/auth.php" method="POST">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-danger w-100 py-2 fw-bold">Register</button>
                </form>
                
                <div class="text-center mt-4 pt-3 border-top border-secondary">
                    <p class="text-muted">Already have an account? <a href="<?= BASE_URL ?>/index.php" class="text-danger fw-bold text-decoration-none">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
