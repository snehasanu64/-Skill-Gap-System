<?php
require_once 'config.php';
requireLogin();
requireAdmin();

// Overall stats
$total_users    = $pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();
$total_analyses = $pdo->query("SELECT COUNT(*) FROM analyses")->fetchColumn();
$avg_match      = round($pdo->query("SELECT AVG(match_percent) FROM analyses")->fetchColumn() ?? 0);

// Top jobs
$top_jobs = $pdo->query("SELECT dream_job, COUNT(*) as cnt FROM analyses GROUP BY dream_job ORDER BY cnt DESC LIMIT 5")->fetchAll();

// Recent analyses
$recent = $pdo->query("
    SELECT a.*, u.name, u.email
    FROM analyses a
    JOIN users u ON a.user_id = u.id
    ORDER BY a.created_at DESC LIMIT 20
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard — Skill Gap System</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="nav-logo">⚡ Skill<span>Gap</span> System</a>
    <div class="nav-links">
        <span style="font-size:12px;color:var(--muted);padding:4px 10px;background:rgba(244,63,94,0.1);border-radius:4px;color:var(--rose);">ADMIN</span>
        <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
</nav>

<div class="container page">

    <div class="page-header">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Platform-wide analytics and user overview</p>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-card indigo">
            <div class="num"><?= $total_users ?></div>
            <div class="lbl">Total Users</div>
        </div>
        <div class="stat-card emerald">
            <div class="num"><?= $total_analyses ?></div>
            <div class="lbl">Total Analyses</div>
        </div>
        <div class="stat-card amber">
            <div class="num"><?= $avg_match ?>%</div>
            <div class="lbl">Avg Skill Match</div>
        </div>
    </div>

    <!-- TOP JOBS -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="section-title">🔥 Most Analyzed Job Roles</div>
        <?php foreach ($top_jobs as $i => $j): ?>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
            <span style="font-family:'Outfit',sans-serif;font-weight:700;color:var(--indigo2);width:20px;"><?= $i+1 ?></span>
            <div style="flex:1">
                <div style="font-weight:500;font-size:14px;"><?= htmlspecialchars($j['dream_job']) ?></div>
                <div class="progress-wrap" style="margin-top:4px;">
                    <div class="progress-bar progress-indigo" data-pct="<?= min(100, $j['cnt'] * 20) ?>" style="width:0%"></div>
                </div>
            </div>
            <span style="font-size:13px;color:var(--muted);"><?= $j['cnt'] ?> analyses</span>
        </div>
        <?php endforeach; ?>
        <?php if (empty($top_jobs)): ?>
            <p style="color:var(--muted);font-size:14px;">No data yet.</p>
        <?php endif; ?>
    </div>

    <!-- RECENT ANALYSES TABLE -->
    <div class="card">
        <div class="section-title">📋 Recent Analyses</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Dream Job</th>
                        <th>Current Skills</th>
                        <th>Missing Skills</th>
                        <th>Match %</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($recent)): ?>
                    <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:2rem;">No analyses yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($recent as $a):
                        $pct = $a['match_percent'];
                        $color = $pct >= 70 ? 'var(--emerald)' : ($pct >= 40 ? 'var(--amber)' : 'var(--rose)');
                    ?>
                    <tr>
                        <td style="color:var(--dim);"><?= $a['id'] ?></td>
                        <td>
                            <div style="font-weight:500;"><?= htmlspecialchars($a['name']) ?></div>
                            <div style="font-size:12px;color:var(--dim);"><?= htmlspecialchars($a['email']) ?></div>
                        </td>
                        <td style="font-weight:500;color:var(--indigo2);"><?= htmlspecialchars($a['dream_job']) ?></td>
                        <td style="font-size:13px;color:var(--muted);max-width:160px;"><?= htmlspecialchars(substr($a['current_skills'], 0, 50)) ?>...</td>
                        <td style="font-size:13px;color:var(--rose);max-width:160px;"><?= htmlspecialchars(substr($a['missing_skills'], 0, 50)) ?>...</td>
                        <td><span style="color:<?= $color ?>;font-weight:700;font-family:'Outfit',sans-serif;"><?= $pct ?>%</span></td>
                        <td style="font-size:12px;color:var(--dim);white-space:nowrap;"><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<footer>© 2025 Skill Gap System</footer>
<script src="assets/js/main.js"></script>
</body>
</html>
