<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Skill Gap System — Find What's Missing</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-logo">⚡ Skill<span>Gap</span> System</div>
    <div class="nav-links">
        <?php if (isLoggedIn()): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="analyze.php" class="btn btn-primary">Analyze Now</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php" class="btn btn-primary">Get Started Free</a>
        <?php endif; ?>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-eyebrow">✨ Smart Skill Analysis</div>
    <h1>Find the <span class="accent">Exact Skills</span><br>You're Missing for Your Dream Job</h1>
    <p>Enter your dream job and current skills. Our system checks the gap and gives you a personalized learning roadmap in seconds.</p>
    <div class="hero-btns">
        <a href="<?= isLoggedIn() ? 'analyze.php' : 'register.php' ?>" class="btn btn-primary btn-lg">Start Analyzing →</a>
        <a href="login.php" class="btn btn-outline btn-lg">Login</a>
    </div>
    <div class="hero-stats">
        <div class="stat-item"><div class="num">50+</div><div class="lbl">Job Roles Supported</div></div>
        <div class="stat-item"><div class="num">Smart</div><div class="lbl">Learning Roadmaps</div></div>
        <div class="stat-item"><div class="num">Free</div><div class="lbl">Always Free</div></div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section style="padding:4rem 0; border-top:1px solid var(--border);">
    <div class="container">
        <h2 style="text-align:center;font-size:2rem;margin-bottom:.5rem;">How It Works</h2>
        <p style="text-align:center;color:var(--muted);margin-bottom:2.5rem;">3 simple steps to your personalized roadmap</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background:rgba(99,102,241,0.15);">🎯</div>
                <h3>Enter Your Dream Job</h3>
                <p>Type the role you're aiming for — MERN Developer, Data Scientist, UI/UX Designer, and more.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:rgba(16,185,129,0.15);">💡</div>
                <h3>Add Your Current Skills</h3>
                <p>Add the skills you already have as tags. HTML, Python, Figma — anything you know counts.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:rgba(245,158,11,0.15);">🗺️</div>
                <h3>Get Your Custom Roadmap</h3>
                <p>Instantly see what's missing and a step-by-step learning roadmap with resources and timelines.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:rgba(244,63,94,0.15);">📊</div>
                <h3>Track Your Progress</h3>
                <p>Save your analyses, track improvement over time, and re-analyze as you learn new skills.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section style="padding:4rem 0;">
    <div class="container" style="text-align:center;">
        <div class="card" style="max-width:600px;margin:0 auto;background:linear-gradient(135deg,rgba(99,102,241,0.15),rgba(16,185,129,0.08));border-color:rgba(99,102,241,0.3);">
            <h2 style="font-size:1.75rem;margin-bottom:.75rem;">Ready to Close Your Skill Gap?</h2>
            <p style="color:var(--muted);margin-bottom:1.5rem;">Join professionals who are using Skill Gap System to fast-track their career.</p>
            <a href="register.php" class="btn btn-primary btn-lg">Create Free Account →</a>
        </div>
    </div>
</section>

<footer>© 2025 Skill Gap System · Built to accelerate your career</footer>
</body>
</html>
