<?php
include('db.php');

// Check if job seeker is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: jobseeker_login.php');
    exit();
}

// Initialize the jobs array
$jobs = [];

// Handle the search query
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT * FROM jobs WHERE title LIKE :query OR category LIKE :query OR location LIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['query' => '%' . $query . '%']);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle the resume upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resume'])) {
    $resume = $_FILES['resume'];

    // Check for errors
    if ($resume['error'] == 0) {
        // Check file type (only PDF allowed)
        $allowed_types = ['application/pdf'];
        if (in_array($resume['type'], $allowed_types)) {
            $upload_dir = 'uploads/resumes/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true); // Create the directory if it doesn't exist
            }

            $resume_name = uniqid() . '-' . basename($resume['name']);
            $upload_path = $upload_dir . $resume_name;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($resume['tmp_name'], $upload_path)) {
                // Update the resume URL in the database
                $user_id = $_SESSION['user_id'];
                $stmt = $pdo->prepare("UPDATE users SET resume = :resume WHERE id = :id");
                $stmt->execute([
                    'resume' => $upload_path,
                    'id' => $user_id
                ]);

                $upload_success = "Resume uploaded successfully!";
            } else {
                $upload_error = "Failed to upload the resume.";
            }
        } else {
            $upload_error = "Only PDF files are allowed.";
        }
    } else {
        $upload_error = "There was an error uploading your resume.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Seeker Dashboard - JobFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<div class="container mt-5">
    <h2 class="text-center">Welcome to Your Dashboard</h2>
    <div class="row">
        <!-- Search Jobs Section -->
        <div class="col-md-4">
            <h4>Search Jobs</h4>
            <form action="" method="GET">
                <input type="text" class="form-control" placeholder="Search jobs" name="query" value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>">
                <button type="submit" class="btn btn-dark mt-2 w-100">Search</button>
            </form>

            <!-- Display Search Results -->
            <?php if (isset($_GET['query'])): ?>
                <h5 class="mt-3">Search Results for "<?= htmlspecialchars($_GET['query']) ?>"</h5>
                <?php if (count($jobs) > 0): ?>
                    <ul class="list-group mt-2">
                        <?php foreach ($jobs as $job): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($job['title']) ?></strong>
                                <p class="mb-0"><?= substr(htmlspecialchars($job['description']), 0, 100) ?>...</p>
                                <a href="job_details.php?id=<?= $job['id'] ?>" class="btn btn-link">View Details</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No jobs found.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Applications Section -->
        <div class="col-md-4">
            <h4>Your Applications</h4>
            <!-- Sample placeholder applications -->
            <ul class="list-group">
                <li class="list-group-item">Job Title 1</li>
                <li class="list-group-item">Job Title 2</li>
            </ul>
        </div>

        <!-- Upload Resume Section -->
        <div class="col-md-4">
            <h4>Upload Resume</h4>

            <!-- Display success or error message -->
            <?php if (isset($upload_success)): ?>
                <div class="alert alert-success">
                    <?= $upload_success ?>
                </div>
            <?php elseif (isset($upload_error)): ?>
                <div class="alert alert-danger">
                    <?= $upload_error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="file" class="form-control" name="resume" required>
                <button type="submit" class="btn btn-success mt-2 w-100">Upload</button>
            </form>
        </div>
    </div>
</div>

<!-- Logout Button -->
<div class="text-center mt-4">
    <a href="index.php" class="btn btn-danger w-100">Logout</a>
</div>

</body>
</html>
