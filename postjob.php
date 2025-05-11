<?php
include('db.php'); // Include database connection

// Check if employer is logged in
if (!isset($_SESSION['employer_id'])) {
    header('Location: employer_login.php');  // Redirect to login if not logged in
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $title = htmlspecialchars(trim($_POST['job_title']));
    $category = htmlspecialchars(trim($_POST['category']));
    $description = htmlspecialchars(trim($_POST['description']));
    $location = htmlspecialchars(trim($_POST['location']));
    $salary_range = htmlspecialchars(trim($_POST['salary_range']));
    
    if (empty($title) || empty($category) || empty($description) || empty($location) || empty($salary_range)) {
        $error_message = "All fields are required!";
    } else {
        // Prepare the SQL query to insert the new job post into the database
        $employer_id = $_SESSION['employer_id'];
        $stmt = $pdo->prepare("INSERT INTO jobs (employer_id, title, category, description, location, salary_range) VALUES (?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$employer_id, $title, $category, $description, $location, $salary_range])) {
            // Redirect to dashboard or a confirmation page
            header('Location: employer_dashboard.php');
            exit();
        } else {
            $error_message = "Failed to post the job. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - Employer Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Post a New Job</h2>

    <?php
    // Display any error messages
    if (isset($error_message)) {
        echo '<div class="alert alert-danger">' . $error_message . '</div>';
    }
    ?>

    <form action="post_job.php" method="POST">
        <div class="mb-3">
            <label for="job-title" class="form-label">Job Title:</label>
            <input type="text" name="job_title" class="form-control" id="job-title" required>
        </div>
        
        <div class="mb-3">
            <label for="category" class="form-label">Category:</label>
            <input type="text" name="category" class="form-control" id="category" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea name="description" class="form-control" id="description" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Location:</label>
            <input type="text" name="location" class="form-control" id="location" required>
        </div>

        <div class="mb-3">
            <label for="salary-range" class="form-label">Salary Range:</label>
            <input type="text" name="salary_range" class="form-control" id="salary-range" required>
        </div>

        <button type="submit" class="btn btn-primary">Post Job</button>
    </form>
</div>

<!-- Bootstrap JS & Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
