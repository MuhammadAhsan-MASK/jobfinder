<?php

include 'db.php';

$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = md5($_POST['password']); // Hash the password using MD5

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['password'] === $password) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            header("Location: jobseeker_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Please enter both email and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Job Seeker Login - JobFinder</title>
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
            color: #4facfe;
        }
        .form-label {
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
        }
        .btn-primary {
            background-color: #4facfe;
            border: none;
        }
        .btn-primary:hover {
            background-color: #3b9dea;
        }
        .form-footer a {
            color: #4facfe;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
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
    <h2 class="text-center mb-4"><i class="bi bi-person-circle me-2"></i>Job Seeker Login</h2>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="jobseeker_login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <div class="text-center mt-3 form-footer">
        <p>Don't have an account? <a href="signup.php">Register here</a></p>
        <a href="index.php" class="btn btn-back w-100"><i class="bi bi-arrow-left"></i> Back to Home</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
