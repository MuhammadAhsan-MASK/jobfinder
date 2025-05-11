<?php
include('db.php'); // Include database connection

// Check if employer is logged in
if (!isset($_SESSION['employer_id'])) {
    header('Location: employer_login.html');  // Redirect to login if not logged in
    exit();
}

// Fetch jobs posted by the employer
$employer_id = $_SESSION['employer_id'];
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE employer_id = ?");
$stmt->execute([$employer_id]);
$jobs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Posted Jobs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('navbar.php'); ?>
    <div class="container mt-5">
        <h2>Your Posted Jobs</h2>
        
        <!-- Check if there are any jobs posted -->
        <?php if (count($jobs) === 0): ?>
            <div class="alert alert-info" role="alert">
                You haven't posted any jobs yet. Start by posting a new job.
            </div>
        <?php else: ?>
            <!-- Display jobs in a Bootstrap table -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['title']); ?></td>
                            <td><?php echo htmlspecialchars($job['category']); ?></td>
                            <td><?php echo htmlspecialchars($job['location']); ?></td>
                            <td><?php echo htmlspecialchars($job['salary_range']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <!-- Logout Button -->
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
