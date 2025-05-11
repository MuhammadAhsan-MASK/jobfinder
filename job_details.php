<?php
include('db.php');

// Check if job ID is provided
if (!isset($_GET['id'])) {
    die("Job ID is missing!");
}

$job_id = $_GET['id'];

// Fetch job based on job ID
$sql = "SELECT * FROM jobs WHERE id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $job_id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    die("Job not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Details - JobFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2 class="text-center"><?php echo htmlspecialchars($job['title']); ?></h2>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
    <p><strong>Salary Range:</strong> <?php echo htmlspecialchars($job['salary_range']); ?></p>
    <p><strong>Posted On:</strong> <?php echo htmlspecialchars($job['created_at']); ?></p>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="apply_job.php?id=<?= urlencode($job['id']) ?>" method="POST">
            <button type="submit" class="btn btn-success">Apply for Job</button>
        </form>
    <?php else: ?>
        <div class="alert alert-warning mt-3">
            <a href="apply_job.php?id=<?= urlencode($job['id']) ?>" class="btn btn-primary">Apply for Job</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
