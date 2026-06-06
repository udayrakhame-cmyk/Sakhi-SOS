<?php
// api/upload_audio.php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio']) && isset($_POST['alert_id'])) {
    $alertId = (int)$_POST['alert_id'];
    $uploadDir = '../assets/uploads/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = basename($_FILES['audio']['name']);
    $targetFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['audio']['tmp_name'], $targetFile)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO audio_evidence (alert_id, file_path) VALUES (?, ?)");
            $dbPath = '/assets/uploads/' . $fileName;
            $stmt->execute([$alertId, $dbPath]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to move file']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
