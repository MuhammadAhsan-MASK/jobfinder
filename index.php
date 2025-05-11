<?php
include('db.php'); // Include the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobFinder - Online Job Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 600;
        }

        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        .hero-section .btn {
            font-size: 1.1rem;
            padding: 12px 25px;
        }

        .search-section {
            padding: 60px 0;
        }

        .search-section h2 {
            font-weight: 600;
        }

        .form-control::placeholder {
            font-style: italic;
        }

        .user-section {
            margin-top: 60px;
            padding: 60px 0;
        }

        .user-section .col-md-6 {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1>Welcome to JobFinder</h1>
        <p>Your gateway to the best job opportunities</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="jobseeker_login.php" class="btn btn-light text-primary shadow-sm">
                <i class="bi bi-person-circle me-2"></i>Job Seeker Login
            </a>
            <a href="employer_login.php" class="btn btn-light text-success shadow-sm">
                <i class="bi bi-briefcase-fill me-2"></i>Employer Login
            </a>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="search-section bg-light text-center">
    <div class="container">
        <h2>Find Your Dream Job</h2>
        <form action="/search" method="GET" class="d-flex justify-content-center mt-4">
            <input type="text" class="form-control form-control-lg w-50 rounded-start" placeholder="Search jobs by title or keyword" name="query">
            <button type="submit" class="btn btn-dark btn-lg rounded-end">Search</button>
        </form>
    </div>
</section>

<!-- User Selection Section -->
<section class="user-section bg-light text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h4 class="text-center">Job Seekers</h4>
                <a href="jobseeker_login.php" class="btn btn-primary w-100">Login</a>
                <p class="text-center">Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div>
            <div class="col-md-6">
                <h4 class="text-center">Employers</h4>
                <a href="employer_login.php" class="btn btn-secondary w-100">Login</a>
                <p class="text-center">Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
