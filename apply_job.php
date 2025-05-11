<?php

include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: jobseeker_login.php');
    exit();
}

// Get job title from URL
if (!isset($_GET['title'])) {
    die("Job title is missing!");
}
$job_title = $_GET['title'];

// Fetch job ID based on title
$stmt = $pdo->prepare("SELECT id FROM jobs WHERE title = :title LIMIT 1");
$stmt->execute(['title' => $job_title]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    die("Job not found!");
}

$job_id = $job['id'];

$success_message = '';
$error_message = '';

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_name = $_POST['applicant_name'] ?? '';
    $applicant_email = $_POST['applicant_email'] ?? '';

    if ($applicant_name && $applicant_email) {
        // Insert application into the database
        $stmt = $pdo->prepare("INSERT INTO applications (job_id, applicant_name, applicant_email, status, applied_at) VALUES (:job_id, :name, :email, 'Pending', NOW())");
        try {
            $stmt->execute([
                'job_id' => $job_id,
                'name' => $applicant_name,
                'email' => $applicant_email
            ]);
            $success_message = "Application submitted successfully!";
        } catch (PDOException $e) {
            $error_message = "Failed to submit application: " . $e->getMessage();
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Apply for Job: <?= htmlspecialchars($job_title) ?></h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php elseif ($error_message): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="applicant_name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="applicant_name" name="applicant_name" required>
        </div>
        <div class="mb-3">
            <label for="applicant_email" class="form-label">Your Email</label>
            <input type="email" class="form-control" id="applicant_email" name="applicant_email" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Application</button>
    </form>
</div>

</body>
</html>
