<?php
include('db.php');

// Check if job seeker is logged in

if (!isset($_SESSION['user_id'])) {
    header('Location: jobseeker_login.php');
    exit();
}

// Fetch jobs from DB
$stmt = $pdo->query("SELECT * FROM jobs ORDER BY created_at DESC LIMIT 5");
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle resume upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resume'])) {
    $resume = $_FILES['resume'];
    if ($resume['error'] == 0 && $resume['type'] === 'application/pdf') {
        $upload_dir = 'uploads/resumes/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $resume_name = uniqid() . '-' . basename($resume['name']);
        $upload_path = $upload_dir . $resume_name;
        if (move_uploaded_file($resume['tmp_name'], $upload_path)) {
            $stmt = $pdo->prepare("UPDATE users SET resume = :resume WHERE id = :id");
            $stmt->execute([
                'resume' => $upload_path,
                'id' => $_SESSION['user_id']
            ]);
            $upload_success = "Resume uploaded successfully!";
        } else {
            $upload_error = "Failed to upload the resume.";
        }
    } else {
        $upload_error = "Only PDF files are allowed.";
    }
}
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
    <h2>Welcome, <strong>JobSeeker</strong></h2>
    <a href="index.php" class="btn btn-outline-dark">Logout</a>
  </div>

  <!-- Resume Upload -->
  <div class="mb-4">
    <h4>Upload Your Resume</h4>
    <?php if (isset($upload_success)) echo "<div class='alert alert-success'>$upload_success</div>"; ?>
    <?php if (isset($upload_error)) echo "<div class='alert alert-danger'>$upload_error</div>"; ?>
    <form method="POST" enctype="multipart/form-data" class="d-flex gap-3">
      <input type="file" name="resume" class="form-control" required accept=".pdf">
      <button type="submit" class="btn btn-primary">Upload</button>
    </form>
  </div>

  <!-- Job Listings -->
  <div class="mt-4">
    <h4 class="text-primary">Latest Job Openings</h4>
    <div class="table-responsive">
      <table class="table table-bordered mt-3">
        <thead class="table-light">
          <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Location</th>
            <th>Salary Range</th>
            <th>Posted</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($jobs as $job): ?>
          <tr>
            <td><?= htmlspecialchars($job['title']) ?></td>
            <td><?= htmlspecialchars($job['category']) ?></td>
            <td><?= htmlspecialchars($job['location']) ?></td>
            <td><?= htmlspecialchars($job['salary_range']) ?></td>
            <td><?= htmlspecialchars($job['created_at']) ?></td>
            <td><a href="job_details.php?id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
</body>
</html>
