<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

requireAdminLogin();

// Fetch statistics
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$activeAlerts = $pdo->query("SELECT COUNT(*) FROM sos_alerts WHERE status = 'active'")->fetchColumn();
$totalAlerts = $pdo->query("SELECT COUNT(*) FROM sos_alerts")->fetchColumn();

// Fetch recent alerts
$alertsStmt = $pdo->query("SELECT s.*, u.name, u.mobile_number FROM sos_alerts s JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC LIMIT 20");
$alerts = $alertsStmt->fetchAll();

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="container-fluid mt-4 mb-5 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-light"><i class="bi bi-speedometer2 text-danger"></i> Admin Dashboard</h2>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-dark border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Users</h5>
                    <div class="d-flex align-items-center mt-3">
                        <div class="display-4 fw-bold text-primary"><?= $totalUsers ?></div>
                        <i class="bi bi-people-fill ms-auto text-muted opacity-25" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-dark border-0 shadow-sm h-100 border-start border-danger border-4">
                <div class="card-body">
                    <h5 class="card-title text-danger fw-bold">Active Alerts</h5>
                    <div class="d-flex align-items-center mt-3">
                        <div class="display-4 fw-bold text-danger"><?= $activeAlerts ?></div>
                        <i class="bi bi-exclamation-triangle-fill ms-auto text-danger opacity-25" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-dark border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total SOS Events</h5>
                    <div class="d-flex align-items-center mt-3">
                        <div class="display-4 fw-bold text-success"><?= $totalAlerts ?></div>
                        <i class="bi bi-shield-check ms-auto text-muted opacity-25" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Table -->
    <div class="card bg-dark border-0 shadow-sm">
        <div class="card-header bg-dark border-bottom border-secondary d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-light"><i class="bi bi-bell-fill text-warning me-2"></i>Recent SOS Alerts</h5>
            <button class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="location.reload()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="table-secondary text-dark">
                        <tr>
                            <th class="py-3 px-4">Alert ID</th>
                            <th class="py-3 px-4">User</th>
                            <th class="py-3 px-4">Contact Info</th>
                            <th class="py-3 px-4">Time</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Location & Audio Evidence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($alerts) > 0): ?>
                            <?php foreach($alerts as $alert): ?>
                            <tr class="<?= $alert['status'] == 'active' ? 'table-danger text-dark fw-bold' : '' ?>">
                                <td class="px-4">#<?= $alert['id'] ?></td>
                                <td class="px-4"><?= sanitizeInput($alert['name']) ?></td>
                                <td class="px-4"><i class="bi bi-telephone-fill me-1"></i> <?= sanitizeInput($alert['mobile_number']) ?></td>
                                <td class="px-4"><?= date('d M, h:i A', strtotime($alert['created_at'])) ?></td>
                                <td class="px-4">
                                    <?php if($alert['status'] == 'active'): ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-2 text-uppercase d-inline-flex align-items-center">
                                            <span class="spinner-grow spinner-grow-sm me-2" role="status" aria-hidden="true"></span> Active
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success rounded-pill px-3 text-uppercase">Resolved</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4">
                                    <div class="d-flex gap-2 align-items-center">
                                        <a href="https://www.google.com/maps?q=<?= $alert['latitude'] ?>,<?= $alert['longitude'] ?>" target="_blank" class="btn btn-sm <?= $alert['status'] == 'active' ? 'btn-dark border-dark' : 'btn-outline-info' ?> rounded-pill px-3">
                                            <i class="bi bi-geo-alt-fill text-danger"></i> Map
                                        </a>
                                        <?php
                                        $audioStmt = $pdo->prepare("SELECT file_path FROM audio_evidence WHERE alert_id = ?");
                                        $audioStmt->execute([$alert['id']]);
                                        $audio = $audioStmt->fetch();
                                        if ($audio):
                                        ?>
                                            <a href="<?= htmlspecialchars($audio['file_path']) ?>" target="_blank" class="btn btn-sm <?= $alert['status'] == 'active' ? 'btn-dark border-dark' : 'btn-outline-warning' ?> rounded-pill px-3">
                                                <i class="bi bi-play-circle-fill text-warning"></i> Listen
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">No SOS alerts recorded in the system.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
