<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h2 class="mb-3 fw-bold">Welcome, <?= sanitizeInput($_SESSION['user_name']) ?></h2>
            <p class="text-muted">Tap the button below in case of an emergency. This will immediately notify your contacts, capture audio evidence, and track your live location.</p>
            
            <div class="sos-container">
                <button id="sosButton" class="btn btn-sos shadow-lg">SOS</button>
            </div>
            
            <div id="sosStatus" class="sos-status mb-4">
                <!-- Status updates will appear here -->
            </div>
        </div>
    </div>
    
    <div class="row mt-2">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-danger mb-4"><i class="bi bi-telephone-fill"></i> Quick Actions</h5>
                    <ul class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between align-items-center px-0">
                            <span><i class="bi bi-shield-check text-primary me-2"></i> Police</span>
                            <a href="tel:100" class="btn btn-sm btn-outline-primary rounded-pill px-3">Call 100</a>
                        </li>
                        <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between align-items-center px-0">
                            <span><i class="bi bi-person-heart text-danger me-2"></i> Women Helpline</span>
                            <a href="tel:1091" class="btn btn-sm btn-outline-danger rounded-pill px-3">Call 1091</a>
                        </li>
                        <li class="list-group-item bg-transparent text-light border-secondary d-flex justify-content-between align-items-center px-0">
                            <span><i class="bi bi-heart-pulse-fill text-success me-2"></i> Ambulance</span>
                            <a href="tel:102" class="btn btn-sm btn-outline-success rounded-pill px-3">Call 102</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-info mb-3"><i class="bi bi-geo-alt-fill"></i> Current Location</h5>
                    <div id="map" class="overflow-hidden" style="height: 220px; width: 100%; border-radius: 8px; background-color: #242f3e;">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="spinner-border text-danger" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/map.js"></script>
<script>
    window.onload = () => {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((pos) => {
                initMap(pos.coords.latitude, pos.coords.longitude, 'map');
            }, () => {
                document.getElementById('map').innerHTML = '<div class="alert alert-danger m-3">Location access denied. Please allow location permissions to use maps.</div>';
            });
        }
    };
</script>

<?php require_once 'includes/footer.php'; ?>
