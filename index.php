<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>JobFinder - Online Job Portal</title>

  <!-- Bootstrap, Icons, Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 0;
    }

    .hero-section {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      color: white;
      height: 100vh;
      display: flex;
      align-items: center;
      text-align: center;
    }

    .hero-content {
      max-width: 700px;
      margin: 0 auto;
    }

    .hero-section h1 {
      font-size: 3.5rem;
      font-weight: 600;
    }

    .hero-section p {
      font-size: 1.25rem;
      margin-bottom: 2rem;
    }

    .btn-custom {
      font-size: 1.1rem;
      padding: 12px 30px;
      border-radius: 30px;
    }

    .features-section {
      padding: 60px 0;
    }

    .feature-box {
      background-color: white;
      border-radius: 15px;
      padding: 30px 20px;
      text-align: center;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
    }

    .feature-box:hover {
      transform: translateY(-5px);
      background-color: #e0f7ff;
    }

    .feature-box i {
      font-size: 40px;
      color: #4facfe;
      margin-bottom: 15px;
    }

    footer {
      background-color: #343a40;
      color: white;
      padding: 20px 0;
      text-align: center;
    }
  </style>
</head>
<body>

<!-- Hero Section -->
<section class="hero-section">
  <div class="container hero-content">
    <h1>Welcome to JobFinder</h1>
    <p>Your gateway to the best job opportunities</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="jobseeker_login.php" class="btn btn-light btn-custom text-primary shadow-sm">
        <i class="bi bi-person-circle me-2"></i> Job Seeker Login
      </a>
      <a href="employer_login.php" class="btn btn-success btn-custom shadow-sm">
        <i class="bi bi-briefcase-fill me-2"></i> Employer Login
      </a>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="features-section">
  <div class="container">
    <h2 class="text-center mb-5 fw-bold">Why Choose JobFinder?</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-box">
          <i class="bi bi-search"></i>
          <h5 class="fw-bold">Powerful Job Search</h5>
          <p>Find jobs that match your skills, location, and interests with advanced filters.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-box">
          <i class="bi bi-shield-check"></i>
          <h5 class="fw-bold">Secure Platform</h5>
          <p>All employers and job seekers are verified for a safe and reliable experience.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-box">
          <i class="bi bi-lightning-charge-fill"></i>
          <h5 class="fw-bold">Fast Application</h5>
          <p>Apply to jobs quickly and track application progress with your dashboard.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  <div class="container">
    <p>&copy; <?= date('Y') ?> JobFinder. All rights reserved.</p>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
