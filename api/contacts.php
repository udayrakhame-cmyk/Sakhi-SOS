<?php
// api/contacts.php
require_once '../includes/db.php';
require_once '../includes/functions.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_contact'])) {
        $name = sanitizeInput($_POST['contact_name']);
        $phone = sanitizeInput($_POST['phone_number']);
        $relationship = sanitizeInput($_POST['relationship']);
        $userId = $_SESSION['user_id'];
        
        try {
            $stmt = $pdo->prepare("INSERT INTO emergency_contacts (user_id, contact_name, phone_number, relationship) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $name, $phone, $relationship]);
            $_SESSION['success'] = "Emergency contact added.";
        } catch(PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        redirect('/profile.php');
    }
    
    if (isset($_POST['delete_contact'])) {
        $contactId = (int)$_POST['contact_id'];
        $userId = $_SESSION['user_id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM emergency_contacts WHERE id = ? AND user_id = ?");
            $stmt->execute([$contactId, $userId]);
            $_SESSION['success'] = "Contact deleted.";
        } catch(PDOException $e) {
            $_SESSION['error'] = "Failed to delete contact.";
        }
        redirect('/profile.php');
    }
}
?>
