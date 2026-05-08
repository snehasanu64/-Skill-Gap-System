<?php
require_once 'config.php';
requireLogin();

$uid = $_SESSION['user_id'];

// Stats
$stmt = $pdo->prepare("SELECT COUNT(*) as total, AVG(match_percent) as avg_match, MAX(match_percent) as best FROM analyses WHERE user_id = ?");
$stmt->execute([$uid]);
$stats = $stmt->fetch();

// History
$stmt = $pdo->prepare("SELECT * FROM analyses WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$uid]);
$analyses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Skill Gap System</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="nav-logo">⚡ Skill<span>Gap</span> System</a>
    <div class="nav-links">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="analyze.php" class="btn btn-primary">+ New Analysis</a>
        <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
</nav>

<div class="container page">

    <div class="page-header">
        <div>
            <h1>Welcome back, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h1>
            <p>Track your skill progress and view past analyses</p>
        </div>
        <a href="analyze.php" class="btn btn-primary">🚀 Analyze Skills</a>
    </div>

    <!-- STATS -->
    <div class="stats-row">
        <div class="stat-card indigo">
            <div class="num"><?= $stats['total'] ?? 0 ?></div>
            <div class="lbl">Total Analyses</div>
        </div>
        <div class="stat-card emerald">
            <div class="num"><?= round($stats['avg_match'] ?? 0) ?>%</div>
            <div class="lbl">Average Match</div>
        </div>
        <div class="stat-card amber">
            <div class="num"><?= $stats['best'] ?? 0 ?>%</div>
            <div class="lbl">Best Score</div>
        </div>
    </div>

    <!-- HISTORY -->
    <div class="page-header" style="margin-bottom:1rem;">
        <h2 style="font-size:1.25rem;">Analysis History</h2>
    </div>

    <?php if (empty($analyses)): ?>
        <div class="empty-state">
            <div class="icon">🔍</div>
            <h3>No analyses yet</h3>
            <p>Run your first skill gap analysis to see results here.</p>
            <a href="analyze.php" class="btn btn-primary" style="margin-top:1rem;">Start Analyzing →</a>
        </div>
    <?php else: ?>
        <div class="history-list">
            <?php foreach ($analyses as $a):
                $pct = $a['match_percent'];
                $color = $pct >= 70 ? 'var(--emerald)' : ($pct >= 40 ? 'var(--amber)' : 'var(--rose)');
                $missing = array_filter(array_map('trim', explode(',', $a['missing_skills'])));
            ?>
            <div class="history-item">
                <div>
                    <div class="job"><?= htmlspecialchars($a['dream_job']) ?></div>
                    <div class="meta">
                        <?= htmlspecialchars($a['current_skills']) ?><br>
                        <span style="color:var(--rose);">Missing: <?= htmlspecialchars(implode(', ', array_slice($missing, 0, 3))) ?><?= count($missing) > 3 ? ' +' . (count($missing)-3) . ' more' : '' ?></span>
                    </div>
                    <div style="font-size:12px;color:var(--dim);margin-top:4px;">
                        <?= date('d M Y, h:i A', strtotime($a['created_at'])) ?>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <span class="pct-badge" style="background:<?= $color ?>22;color:<?= $color ?>;border:1px solid <?= $color ?>44;">
                        <?= $pct ?>% match
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
<script src="assets/js/main.js"></script>
</body>
</html>
