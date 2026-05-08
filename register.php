<?php
require_once 'config.php';
if (isLoggedIn()) redirect('dashboard.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $conf  = $_POST['confirm'] ?? '';

    if (!$name || !$email || !$pass) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($pass) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($pass !== $conf) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'This email is already registered. <a href="login.php">Login instead</a>';
        } else {
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)")
                ->execute([$name, $email, $hash]);
            $success = 'Account created! <a href="login.php">Login now →</a>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — Skill Gap System</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php" class="nav-logo">⚡ Skill<span>Gap</span> System</a>
    <div class="nav-links">
        <a href="login.php">Already have an account? Login</a>
    </div>
</nav>

<div class="auth-wrap">
    <div class="auth-card card">
        <h2>Create Account</h2>
        <p class="sub">Start analyzing your skill gap for free</p>

        <?php if ($error): ?><div class="alert alert-danger">⚠ <?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success">✓ <?= $success ?></div><?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="John Doe" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm" class="form-control" placeholder="Repeat password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Create Account →</button>
        </form>

        <hr class="divider">
        <p style="text-align:center;font-size:14px;color:var(--muted);">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
</div>
</body>
</html>
