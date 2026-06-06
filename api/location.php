    <?php
// api/location.php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    http_response_code(401);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if ($data && isset($data->alert_id) && isset($data->lat) && isset($data->lng)) {
    try {
        $stmt = $pdo->prepare("INSERT INTO location_tracking (alert_id, latitude, longitude) VALUES (?, ?, ?)");
        $stmt->execute([$data->alert_id, $data->lat, $data->lng]);
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
}
?>
