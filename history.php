<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM sos_alerts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$alerts = $stmt->fetchAll();

require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="container mt-4 mb-5">
    <h3 class="mb-4 fw-bold"><i class="bi bi-clock-history text-danger"></i> SOS History</h3>
    
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="table-secondary text-dark">
                        <tr>
                            <th class="py-3 px-4">Date & Time</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Initial Location</th>
                            <th class="py-3 px-4">Audio Evidence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($alerts) > 0): ?>
                            <?php foreach($alerts as $alert): ?>
                            <tr>
                                <td class="px-4"><?= date('d M Y, h:i A', strtotime($alert['created_at'])) ?></td>
                                <td class="px-4">
                                    <?php if($alert['status'] == 'active'): ?>
                                        <span class="badge bg-danger rounded-pill px-3">Active</span>
                                    <?php elseif($alert['status'] == 'resolved'): ?>
                                        <span class="badge bg-success rounded-pill px-3">Resolved</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill px-3"><?= htmlspecialchars($alert['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4">
                                    <a href="https://www.google.com/maps?q=<?= $alert['latitude'] ?>,<?= $alert['longitude'] ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                        <i class="bi bi-geo-alt"></i> View Map
                                    </a>
                                </td>
                                <td class="px-4">
                                    <?php
                                    $audioStmt = $pdo->prepare("SELECT file_path FROM audio_evidence WHERE alert_id = ?");
                                    $audioStmt->execute([$alert['id']]);
                                    $audio = $audioStmt->fetch();
                                    if ($audio):
                                    ?>
                                        <audio controls src="<?= htmlspecialchars($audio['file_path']) ?>" style="height: 35px; width: 250px; outline: none;"></audio>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">No audio captured</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-shield-check fs-1 opacity-50 mb-3 d-block"></i>
                                    No SOS alerts recorded. Stay safe!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
