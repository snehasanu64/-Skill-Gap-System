<?php
require_once 'config.php';
if (isLoggedIn()) redirect('dashboard.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (!$email || !$pass) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];
            redirect($user['role'] === 'admin' ? 'admin_dashboard.php' : 'dashboard.php');
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Skill Gap System</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php" class="nav-logo">⚡ Skill<span>Gap</span> System</a>
    <div class="nav-links">
        <a href="register.php">Don't have an account? Register</a>
    </div>
</nav>

<div class="auth-wrap">
    <div class="auth-card card">
        <h2>Welcome Back</h2>
        <p class="sub">Login to view your analyses and roadmaps</p>

        <?php if ($error): ?>
            <div class="alert alert-danger">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Your password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Login →</button>
        </form>

        <hr class="divider">

        <p style="text-align:center;font-size:14px;color:var(--muted);">
            New here? <a href="register.php">Create account</a>
        </p>
    </div>
</div>
</body>
</html>
