<?php
include('db.php');

if (!isset($_SESSION['employer_id'])) {
    header('Location: employer_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'] ?? null;
    $new_status = $_POST['new_status'] ?? '';

    if ($application_id && in_array($new_status, ['Shortlisted', 'Rejected'])) {
        $stmt = $pdo->prepare("UPDATE applications SET status = :status WHERE id = :id");
        $stmt->execute([
            'status' => $new_status,
            'id' => $application_id
        ]);
    }
}

header('Location: employer_dashboard.php');
exit();
