<?php
// Start session for handling session variables
include('db.php'); // Include database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the password using MD5

    // Query the database for the employer's data
    $stmt = $pdo->prepare("SELECT * FROM employers WHERE email = ?");
    $stmt->execute([$email]);
    $employer = $stmt->fetch();

    // Verify the hashed password
    if ($employer && $employer['password'] === $password) {
        // Successful login, store employer's info in session
        $_SESSION['employer_id'] = $employer['id'];
        $_SESSION['employer_name'] = $employer['name'];
        header('Location: employer_dashboard.php');  // Redirect to employer dashboard
        exit;
    } else {
        $error_message = "Invalid email or password."; // Set error message if login fails
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Employer Login - JobFinder</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f6f9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-card {
      background: #fff;
      padding: 2.5rem;
      border-radius: 1rem;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
      max-width: 400px;
      width: 100%;
    }

    .login-card h2 {
      font-weight: 600;
      color: #00bfff;
    }

    .form-label {
      font-weight: 500;
    }

    .form-control:focus {
      border-color: #00bfff;
      box-shadow: 0 0 0 0.2rem rgba(0, 191, 255, 0.25);
    }

    .btn-primary {
      background-color: #00bfff;
      border: none;
    }

    .btn-primary:hover {
      background-color: #00a5d8;
    }

    .form-footer a {
      color: #00bfff;
      text-decoration: none;
    }

    .form-footer a:hover {
      text-decoration: underline;
    }

    /* Add style for back button */
    .btn-back {
      background-color: #f0f0f0;
      border: 1px solid #ccc;
      color: #333;
      margin-bottom: 20px;
    }

    .btn-back:hover {
      background-color: #ddd;
    }
  </style>
</head>
<body>


<div class="login-card">
  <h2 class="text-center mb-4"><i class="bi bi-briefcase-fill me-2"></i>Employer Login</h2>

  <!-- Display error message if login failed -->
  <?php if (isset($error_message)): ?>
    <div class="alert alert-danger text-center">
      <?php echo $error_message; ?>
    </div>
  <?php endif; ?>

  <!-- Back Button -->
 

  <form action="employer_login.php" method="POST">
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
    <div class="mt-3 text-center form-footer">
      <a href="signup.html">Don't have an account? Sign Up</a>
    </div>
    <a href="javascript:history.back()" class="btn btn-back w-100 mb-4">Back</a>
  </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
