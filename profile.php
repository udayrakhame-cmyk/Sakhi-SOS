<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM emergency_contacts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$contacts = $stmt->fetchAll();

require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h3 class="mb-4 fw-bold"><i class="bi bi-person-lines-fill text-danger"></i> Emergency Contacts</h3>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="card mb-4 border-0 bg-dark shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-light mb-3">Add New Contact</h5>
                    <form action="<?= BASE_URL ?>/api/contacts.php" method="POST" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="contact_name" class="form-control bg-secondary text-white border-0" placeholder="Contact Name" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="phone_number" class="form-control bg-secondary text-white border-0" placeholder="Phone Number" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="relationship" class="form-control bg-secondary text-white border-0" placeholder="Relation (e.g. Sister)">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="add_contact" class="btn btn-danger w-100"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <h5 class="text-muted mb-3">Saved Contacts</h5>
            <div class="row">
                <?php if(count($contacts) > 0): ?>
                    <?php foreach($contacts as $c): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card contact-card h-100 p-3 border-0 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 fw-bold text-light"><?= sanitizeInput($c['contact_name']) ?></h5>
                                    <p class="mb-1 text-muted small"><i class="bi bi-telephone"></i> <?= sanitizeInput($c['phone_number']) ?></p>
                                    <span class="badge bg-danger bg-opacity-75"><?= sanitizeInput($c['relationship']) ?></span>
                                </div>
                                <form action="<?= BASE_URL ?>/api/contacts.php" method="POST" onsubmit="return confirm('Delete this contact?');">
                                    <input type="hidden" name="contact_id" value="<?= $c['id'] ?>">
                                    <button type="submit" name="delete_contact" class="btn btn-outline-secondary border-0 btn-sm text-danger"><i class="bi bi-trash fs-5"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center text-muted my-5 py-5">
                        <i class="bi bi-people fs-1 opacity-50"></i>
                        <p class="mt-3">No emergency contacts added yet.<br>Please add contacts to notify them during an emergency.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
