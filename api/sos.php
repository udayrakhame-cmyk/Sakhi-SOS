<?php
// api/sos.php
require_once '../includes/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if ($data && isset($data->action)) {
    $userId = $_SESSION['user_id'];
    
    if ($data->action === 'trigger') {
        $lat = $data->lat;
        $lng = $data->lng;
        
        try {
            $stmt = $pdo->prepare("INSERT INTO sos_alerts (user_id, latitude, longitude, status) VALUES (?, ?, ?, 'active') RETURNING id");
            $stmt->execute([$userId, $lat, $lng]);
            $alertId = $stmt->fetchColumn();
            
            // Mocking SMS/Email alert to contacts
            // A real implementation would query `emergency_contacts` and use an API here.
            
            echo json_encode(['success' => true, 'alert_id' => $alertId]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else if ($data->action === 'resolve' && isset($data->alert_id)) {
        try {
            $stmt = $pdo->prepare("UPDATE sos_alerts SET status = 'resolved' WHERE id = ? AND user_id = ?");
            $stmt->execute([$data->alert_id, $userId]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
