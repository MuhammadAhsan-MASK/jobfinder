<?php

include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: jobseeker_login.php');
    exit();
}

if (!isset($_GET['id'])) {
    die("Job ID is missing!");
}

$job_id = $_GET['id'];

// Fetch job info
$stmt = $pdo->prepare("SELECT title FROM jobs WHERE id = :id");
$stmt->execute(['id' => $job_id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    die("Job not found!");
}

$job_title = $job['title'];

// Fetch user info
$stmt = $pdo->prepare("SELECT full_name, email FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$success_message = '';
$error_message = '';

// Ensure the form is only processed on POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resume_uploaded = false;
    $resume_path = '';

    // Prevent duplicate application
    $check = $pdo->prepare("SELECT * FROM applications WHERE job_id = :job_id AND applicant_email = :email");
    $check->execute([
        'job_id' => $job_id,
        'email' => $user['email']
    ]);
    if ($check->rowCount() > 0) {
        $error_message = "You already applied for this job.";
    } else {
        // Handle resume upload (optional)
        if (!empty($_FILES['resume']['name'])) {
            $target_dir = "uploads/resumes/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name = basename($_FILES['resume']['name']);
            $target_file = $target_dir . time() . "_" . $file_name;
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $allowed = ['pdf', 'doc', 'docx'];
            if (in_array($file_type, $allowed)) {
                if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
                    $resume_uploaded = true;
                    $resume_path = $target_file;
                } else {
                    $error_message = "Resume upload failed.";
                }
            } else {
                $error_message = "Only PDF, DOC, and DOCX files allowed.";
            }
        }

        if (empty($error_message)) {
            // Updated SQL query to match your column names
            $sql = "INSERT INTO applications (job_id, applicant_name, applicant_email, status, applied_at, resume_path)
                    VALUES (:job_id, :applicant_name, :email, 'Pending', NOW(), :resume_path)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'job_id' => $job_id,
                'applicant_name' => $user['full_name'], // Updated column name to 'full_name'
                'email' => $user['email'],
                'resume_path' => $resume_uploaded ? $resume_path : null // If no resume uploaded, set it to null
            ]);

            $success_message = "Application submitted successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Job - <?= htmlspecialchars($job_title) ?></title>
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
    <!-- Back Button -->
    <a href="jobseeker_dashboard.php" class="btn btn-secondary mb-3">Back to Job Listings</a>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Your Full Name</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Your Email</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload Resume (PDF/DOC)</label>
            <input type="file" class="form-control" name="resume" accept=".pdf,.doc,.docx">
        </div>
        <button type="submit" class="btn btn-primary">Submit Application</button>
    </form>
</div>

</body>
</html>
