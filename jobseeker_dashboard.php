<?php
include('db.php');

// Check if job seeker is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: jobseeker_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_email = $user['email'];
$user_name = $user['full_name'];

// Fetch applications
$stmt = $pdo->prepare("
    SELECT a.id, j.title, j.category, j.location, j.salary_range, a.status, a.applied_at
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    WHERE a.applicant_email = ?
    ORDER BY a.applied_at DESC
");
$stmt->execute([$user_email]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle resume upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resume'])) {
    $resume = $_FILES['resume'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $resume['tmp_name']);
    finfo_close($finfo);

    if ($resume['error'] == 0 && $mime === 'application/pdf') {
        $upload_dir = 'uploads/resumes/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $resume_name = uniqid() . '-' . basename($resume['name']);
        $upload_path = $upload_dir . $resume_name;
        if (move_uploaded_file($resume['tmp_name'], $upload_path)) {
            $stmt = $pdo->prepare("UPDATE users SET resume = :resume WHERE id = :id");
            $stmt->execute([
                'resume' => $upload_path,
                'id' => $user_id
            ]);
            $upload_success = "Resume uploaded successfully!";
            $user['resume'] = $upload_path; // update user resume path
        } else {
            $upload_error = "Failed to upload the resume.";
        }
    } else {
        $upload_error = "Only PDF files are allowed.";
    }
}

// Handle Add Skill
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_skill'])) {
    $new_skill = trim($_POST['new_skill']);
    if (!empty($new_skill)) {
        $stmt = $pdo->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
        $stmt->execute([$user_id, $new_skill]);
        header("Location: jobseeker_dashboard.php"); // prevent resubmission
        exit();
    }
}

// Handle Delete Skill
if (isset($_GET['delete_skill'])) {
    $skill_id = (int)$_GET['delete_skill'];
    $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ? AND user_id = ?");
    $stmt->execute([$skill_id, $user_id]);
    header("Location: jobseeker_dashboard.php");
    exit();
}

// Fetch Skills
$stmt = $pdo->prepare("SELECT * FROM skills WHERE user_id = ?");
$stmt->execute([$user_id]);
$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>JobSeeker Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f6f9;
      font-family: 'Poppins', sans-serif;
    }
    .card, .table {
      background: white;
      border-radius: 1rem;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>
<div class="container py-5">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Welcome, <strong><?= htmlspecialchars($user_name) ?></strong></h2>
    <a href="index.php" class="btn btn-outline-dark">Logout</a>
  </div>

  <!-- Resume Upload -->
  <div class="mb-4">
    <h4>Upload Your Resume</h4>

    <?php if (!empty($user['resume'])): ?>
      <div class="mb-3">
        <p>Current Resume:</p>
        <embed src="<?= htmlspecialchars($user['resume']) ?>" type="application/pdf" width="100%" height="400px" style="border: 1px solid #ccc; border-radius: 0.5rem;">
        <p class="mt-2">
          <a href="<?= htmlspecialchars($user['resume']) ?>" target="_blank" class="btn btn-sm btn-secondary">Download/View Full Resume</a>
        </p>
      </div>
    <?php endif; ?>

    <?php if (isset($upload_success)) echo "<div class='alert alert-success'>$upload_success</div>"; ?>
    <?php if (isset($upload_error)) echo "<div class='alert alert-danger'>$upload_error</div>"; ?>

    <form method="POST" enctype="multipart/form-data" class="d-flex gap-3">
      <input type="file" name="resume" class="form-control" required accept=".pdf">
      <button type="submit" class="btn btn-primary">Upload</button>
    </form>
  </div>

  <!-- Applications -->
  <div class="mt-4">
    <h4 class="text-primary">Your Job Applications</h4>
    <?php if ($applications): ?>
    <div class="table-responsive">
      <table class="table table-bordered mt-3">
        <thead class="table-light">
          <tr>
            <th>Job Title</th>
            <th>Category</th>
            <th>Location</th>
            <th>Salary Range</th>
            <th>Status</th>
            <th>Applied On</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applications as $app): ?>
          <tr>
            <td><?= htmlspecialchars($app['title']) ?></td>
            <td><?= htmlspecialchars($app['category']) ?></td>
            <td><?= htmlspecialchars($app['location']) ?></td>
            <td><?= htmlspecialchars($app['salary_range']) ?></td>
            <td><?= htmlspecialchars($app['status']) ?></td>
            <td><?= date('M d, Y', strtotime($app['applied_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
      <p>You have not applied for any jobs yet.</p>
    <?php endif; ?>
  </div>
  <!-- Skills Section -->
<div class="mt-5">
  <h4 class="text-success">Your Skills</h4>
  
  <!-- Show Existing Skills -->
  <?php if ($skills): ?>
    <ul class="list-group mb-3">
      <?php foreach ($skills as $skill): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?= htmlspecialchars($skill['skill_name']) ?>
          <a href="?delete_skill=<?= $skill['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this skill?')">Delete</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>No skills added yet.</p>
  <?php endif; ?>

  <!-- Add Skill Form -->
  <form method="POST" class="d-flex gap-2">
    <input type="text" name="new_skill" class="form-control" placeholder="Enter a skill" required>
    <button type="submit" class="btn btn-success">Add Skill</button>
  </form>
</div>


  <!-- Search and Filters -->
  <div class="mt-5">
    <h4 class="mb-3">Search and Filter Jobs</h4>
    <form action="jobs.php" method="GET" class="row g-3">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search keywords...">
      </div>
      <div class="col-md-3">
        <select name="category" class="form-select">
          <option value="">All Categories</option>
          <option value="Software">Software Development</option>
          <option value="Marketing">Marketing</option>
          <option value="Design">Design</option>
        </select>
      </div>
      <div class="col-md-3">
        <input type="text" name="location" class="form-control" placeholder="Location">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Search</button>
      </div>
    </form>
    <div class="mt-3">
      <a href="jobs.php" class="btn btn-outline-primary">Browse All Job Openings</a>
    </div>
  </div>

</div>
</body>
</html>
