<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border-bottom border-secondary">
  <div class="container">
    <a class="navbar-brand text-danger fw-bold fs-3" href="<?= BASE_URL ?>/dashboard.php">
        <i class="bi bi-shield-fill-check"></i> Sakhi SOS
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if(isLoggedIn()): ?>
            <li class="nav-item">
                <a class="nav-link fs-5 px-3" href="<?= BASE_URL ?>/dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-5 px-3" href="<?= BASE_URL ?>/profile.php">Contacts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-5 px-3" href="<?= BASE_URL ?>/history.php">History</a>
            </li>
            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                <a class="btn btn-outline-light rounded-pill px-4" href="<?= BASE_URL ?>/api/auth.php?action=logout">Logout</a>
            </li>
        <?php elseif(isAdminLoggedIn()): ?>
            <li class="nav-item">
                <a class="nav-link fs-5 px-3" href="<?= BASE_URL ?>/admin/dashboard.php">Admin Dashboard</a>
            </li>
            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                <a class="btn btn-outline-light rounded-pill px-4" href="<?= BASE_URL ?>/api/auth.php?action=admin_logout">Logout</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link fs-5 px-3" href="<?= BASE_URL ?>/index.php">Login</a>
            </li>
            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                <a class="btn btn-danger rounded-pill px-4" href="<?= BASE_URL ?>/register.php">Register</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
