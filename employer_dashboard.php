<?php
include('db.php');

// Check if employer is logged in
if (!isset($_SESSION['employer_id'])) {
    header('Location: employer_login.php');
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
    <title>Employer Dashboard - JobFinder</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }
        .tab-content {
            margin-top: 20px;
        }
        .nav-tabs .nav-item .nav-link.active {
            background-color: #00bfff;
            color: white;
        }
        /* Style for logout button */
        .btn-logout {
            background-color: #f44336;
            color: white;
            border: none;
            width: 100%;
            margin-top: 30px;
        }
        .btn-logout:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>

<div class="container mt-5">
   
<h2 class="text-center">Welcome to Your Employer Dashboard</h2>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="post-job-tab" data-bs-toggle="tab" href="#post-job" role="tab" aria-controls="post-job" aria-selected="true">Post Job</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="view-jobs-tab" data-bs-toggle="tab" href="#view-jobs" role="tab" aria-controls="view-jobs" aria-selected="false">View Jobs</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="view-applications-tab" data-bs-toggle="tab" href="#view-applications" role="tab" aria-controls="view-applications" aria-selected="false">View Applications</a>
        </li>
    </ul>

    <div class="tab-content" id="dashboardTabsContent">
        <!-- Post Job Tab -->
        <div class="tab-pane fade show active" id="post-job" role="tabpanel" aria-labelledby="post-job-tab">
            <h4>Post a New Job</h4>
            <form action="postjob.php" method="POST" class="w-75 mx-auto">
                <div class="mb-3">
                    <label for="job-title" class="form-label">Job Title</label>
                    <input type="text" class="form-control" id="job-title" name="job_title" required>
                </div>
                <div class="mb-3">
                    <label for="job-category" class="form-label">Job Category</label>
                    <select class="form-select" id="job-category" name="category" required>
                        <option selected>Choose a category</option>
                        <option value="Software">Software Development</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Design">Design</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="job-description" class="form-label">Job Description</label>
                    <textarea class="form-control" id="job-description" name="description" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="job-location" class="form-label">Job Location</label>
                    <input type="text" class="form-control" id="job-location" name="location" required>
                </div>
                <div class="mb-3">
                    <label for="salary-range" class="form-label">Salary Range</label>
                    <input type="text" class="form-control" id="salary-range" name="salary_range" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Post Job</button>
            </form>
        </div>

        <!-- View Jobs Tab -->
        <div class="tab-pane fade" id="view-jobs" role="tabpanel" aria-labelledby="view-jobs-tab">
            <h4>Your Posted Jobs</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Job Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">Location</th>
                        <th scope="col">Salary</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job['title']); ?></td>
                        <td><?php echo htmlspecialchars($job['category']); ?></td>
                        <td><?php echo htmlspecialchars($job['location']); ?></td>
                        <td><?php echo htmlspecialchars($job['salary_range']); ?></td>
                        <td>
                            <button class="btn btn-info btn-sm">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- View Applications Tab -->
        <div class="tab-pane fade" id="view-applications" role="tabpanel" aria-labelledby="view-applications-tab">
            <h4>Applications for Your Jobs</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Job Title</th>
                        <th scope="col">Applicant Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example application listings -->
                    <tr>
                        <td>React Native Developer</td>
                        <td>Talha Arshad</td>
                        <td>Pending</td>
                        <td>
                            <button class="btn btn-success btn-sm">Shortlist</button>
                            <button class="btn btn-danger btn-sm">Reject</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Marketing</td>
                        <td>Ali Nahar</td>
                        <td>Interviewing</td>
                        <td>
                            <button class="btn btn-success btn-sm">Shortlist</button>
                            <button class="btn btn-danger btn-sm">Reject</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Logout Button -->
    <form action="index.php" method="POST">
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>

</div>

<!-- Bootstrap JS & Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
