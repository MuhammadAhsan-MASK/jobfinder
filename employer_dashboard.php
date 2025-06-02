<?php
include('db.php');


// Redirect if not logged in
if (!isset($_SESSION['employer_id'])) {
    header('Location: employer_login.php');
    exit();
}

$employer_id = $_SESSION['employer_id'];

// Fetch employer name
$stmt = $pdo->prepare("SELECT name FROM employers WHERE id = ?");
$stmt->execute([$employer_id]);
$employer = $stmt->fetch();
$employer_name = $employer ? $employer['name'] : 'Employer';

// Fetch employer's jobs
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE employer_id = ?");
$stmt->execute([$employer_id]);
$jobs = $stmt->fetchAll();

// Fetch applications with resume and skills
$stmt = $pdo->prepare("
    SELECT 
        a.id AS application_id,
        j.title AS job_title,
        a.applicant_name,
        a.status,
        u.id AS user_id,
        u.resume AS resume_path
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    JOIN users u ON a.applicant_email = u.email
    WHERE j.employer_id = :employer_id
");
$stmt->execute(['employer_id' => $employer_id]);
$applications = $stmt->fetchAll();

// Fetch skills for each user
foreach ($applications as &$app) {
    $user_id = $app['user_id'];
    $stmtSkills = $pdo->prepare("SELECT skill_name FROM skills WHERE user_id = ?");
    $stmtSkills->execute([$user_id]);
    $skills = $stmtSkills->fetchAll(PDO::FETCH_COLUMN);
    $app['skills'] = $skills;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employer Dashboard - JobFinder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }
        h2 {
            color: #007bff;
        }
        .tab-content h4 {
            color: #343a40;
            border-bottom: 2px solid #00bfff;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        .nav-tabs {
            background-color: white;
            border-radius: 8px;
            padding: 10px;
        }
        .nav-tabs .nav-link.active {
            background-color: #00bfff;
            color: white;
        }
        .form-section, .table {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #00bfff;
            color: white;
        }
        .btn-logout {
            background-color: #f44336;
            color: white;
            width: 100%;
            margin-top: 30px;
        }
        .btn-logout:hover {
            background-color: #e53935;
        }
        .badge {
            margin-right: 3px;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Welcome <?= htmlspecialchars($employer_name) ?></h2>
    <p class="text-center text-muted">Manage your job postings and view applications easily</p>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="post-job-tab" data-bs-toggle="tab" href="#post-job" role="tab">Post Job</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="view-jobs-tab" data-bs-toggle="tab" href="#view-jobs" role="tab">View Jobs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="view-applications-tab" data-bs-toggle="tab" href="#view-applications" role="tab">View Applications</a>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content mt-3" id="dashboardTabsContent">

        <!-- Post Job Tab -->
        <div class="tab-pane fade show active" id="post-job" role="tabpanel">
            <h4>Post a New Job</h4>
            <form action="postjob.php" method="POST" class="form-section">
                <div class="mb-3">
                    <label class="form-label">Job Title</label>
                    <input type="text" class="form-control" name="job_title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Category</label>
                    <select class="form-select" name="category" required>
                        <option selected disabled>Choose a category</option>
                        <option value="Software">Software Development</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Design">Design</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Description</label>
                    <textarea class="form-control" name="description" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Location</label>
                    <input type="text" class="form-control" name="location" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Salary Range</label>
                    <input type="text" class="form-control" name="salary_range" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Post Job</button>
            </form>
        </div>

        <!-- View Jobs Tab -->
        <div class="tab-pane fade" id="view-jobs" role="tabpanel">
            <h4>Your Posted Jobs</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Salary</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?= htmlspecialchars($job['title']) ?></td>
                            <td><?= htmlspecialchars($job['category']) ?></td>
                            <td><?= htmlspecialchars($job['location']) ?></td>
                            <td><?= htmlspecialchars($job['salary_range']) ?></td>
                            <td>
                                <button class="btn btn-info btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- View Applications Tab -->
        <div class="tab-pane fade" id="view-applications" role="tabpanel">
            <h4>Applications for Your Jobs</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Applicant Name</th>
                        <th>Status</th>
                        <th>CV</th>
                        <th>Skills</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($applications): ?>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td><?= htmlspecialchars($app['job_title']) ?></td>
                                <td><?= htmlspecialchars($app['applicant_name']) ?></td>
                                <td><?= htmlspecialchars($app['status']) ?></td>
                                <td>
                                    <?php if (!empty($app['resume_path'])): ?>
                                        <a href="<?= htmlspecialchars($app['resume_path']) ?>" target="_blank" class="btn btn-secondary btn-sm">View CV</a>
                                    <?php else: ?>
                                        No CV
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($app['skills'])): ?>
                                        <?php foreach ($app['skills'] as $skill): ?>
                                            <span class="badge bg-primary"><?= htmlspecialchars($skill) ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No skills listed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" action="update_application_status.php" class="d-inline">
                                        <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                        <input type="hidden" name="new_status" value="Shortlisted">
                                        <button class="btn btn-success btn-sm" type="submit">Shortlist</button>
                                    </form>
                                    <form method="POST" action="update_application_status.php" class="d-inline">
                                        <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                        <input type="hidden" name="new_status" value="Rejected">
                                        <button class="btn btn-danger btn-sm" type="submit">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No applications found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Logout -->
    <form action="index.php" method="POST">
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
