<?php
require_once 'config.php';
requireLogin();

$jobs = [
    'MERN Stack Developer','Full Stack Developer','Frontend Developer','Backend Developer',
    'Data Scientist','Machine Learning Engineer','UI/UX Designer','DevOps Engineer',
    'Android Developer','iOS Developer','Python Developer','Java Developer',
    'Cybersecurity Analyst','Cloud Engineer (AWS)','Data Analyst','Blockchain Developer'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analyze Skills — Skill Gap System</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>


<nav class="navbar">
    <a href="index.php" class="nav-logo">⚡ Skill<span>Gap</span> System</a>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="analyze.php" class="active">Analyze</a>
        <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
</nav>

<div class="container page">
    <div style="max-width:680px;margin:0 auto;">

        <div style="margin-bottom:2rem;">
            <h1 style="font-size:2rem;margin-bottom:6px;">Analyze Your Skills</h1>
            <p style="color:var(--muted);">Tell us where you want to go and where you are right now.</p>
        </div>

        <form method="POST" action="result.php" id="analyzeForm">
            <div class="card" style="margin-bottom:1rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label>🎯 Dream Job / Role</label>
                    <input type="text" name="dream_job" id="dream_job" class="form-control"
                        placeholder="e.g. MERN Stack Developer" required autocomplete="off">
                    <div class="job-suggestions" style="margin-top:10px;">
                        <?php foreach ($jobs as $j): ?>
                            <span class="job-chip"><?= htmlspecialchars($j) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom:1.5rem;">
                <label style="display:block;font-size:13px;font-weight:600;font-family:'Outfit',sans-serif;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.05em;">
                    💡 Your Current Skills
                </label>
                <div class="skills-input-wrap" id="skillsWrap">
                    <input type="text" placeholder="Type a skill and press Enter (e.g. HTML)">
                </div>
                <input type="hidden" name="current_skills" id="current_skills_hidden">
                <p class="skill-hint">Press <strong>Enter</strong> or <strong>comma</strong> after each skill · Backspace to delete</p>

                <div style="margin-top:1rem;">
                    <p style="font-size:12px;color:var(--dim);margin-bottom:8px;">QUICK ADD <span id="quick-add-role"></span>→</p>
                    <div id="quick-add-container" style="display:flex;flex-wrap:wrap;gap:6px;">
                        <?php
                        $quick = ['HTML','CSS','JavaScript','Python','Java','C++','React','Node.js','MySQL','MongoDB','Git','Figma','PHP','Bootstrap','TypeScript'];
                        foreach ($quick as $s): ?>
                            <span onclick="addQuickSkill('<?= $s ?>')" class="job-chip"><?= $s ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-full">
                🚀 Analyze My Skills
            </button>
        </form>

    </div>
</div>

<script src="assets/js/main.js"></script>
<script>
function addQuickSkill(skill) {
    const hidden = document.getElementById('current_skills_hidden');
    const wrap = document.getElementById('skillsWrap');
    const existing = hidden.value ? hidden.value.split(',').map(s=>s.trim()) : [];
    if (!existing.includes(skill)) {
        // Trigger via input field
        const input = wrap.querySelector('input');
        input.value = skill;
        input.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }));
    }
}

const jobSkillsMap = {
    'MERN Stack Developer': ['HTML','CSS','JavaScript','React','Node.js','Express','MongoDB','REST APIs','TypeScript','Redux','Git','Docker'],
    'Full Stack Developer': ['HTML','CSS','JavaScript','React','Node.js','Express','MongoDB','SQL','Git','REST APIs','TypeScript','AWS'],
    'Frontend Developer': ['HTML','CSS','JavaScript','React','TypeScript','Redux','Figma','Git','REST APIs','Bootstrap'],
    'Backend Developer': ['Node.js','Express','Python','Java','SQL','MongoDB','REST APIs','Git','Docker','AWS'],
    'Data Scientist': ['Python','R','SQL','Machine Learning','TensorFlow','Pandas','NumPy','Matplotlib','Jupyter','Statistics'],
    'Machine Learning Engineer': ['Python','TensorFlow','Scikit-learn','Deep Learning','SQL','Data Analysis','PyTorch','Keras','Git','Statistics'],
    'UI/UX Designer': ['Figma','Adobe XD','UI Design','Wireframing','Prototyping','User Research','CSS','HTML','JavaScript','Adobe Creative Suite'],
    'DevOps Engineer': ['Docker','Kubernetes','AWS','CI/CD','Linux','Git','Jenkins','Terraform','Monitoring','Bash'],
    'Android Developer': ['Java','Kotlin','Android Studio','XML','REST APIs','Firebase','SQLite','Git','Material Design','Java'],
    'iOS Developer': ['Swift','Objective-C','Xcode','iOS SDK','REST APIs','Core Data','Git','Firebase','UIKit','SwiftUI'],
    'Python Developer': ['Python','Django','Flask','SQL','REST APIs','Git','PostgreSQL','Redis','Docker','Testing'],
    'Java Developer': ['Java','Spring Boot','SQL','Maven','Git','REST APIs','JUnit','Microservices','Docker','Kubernetes'],
    'Cybersecurity Analyst': ['Linux','Networking','Python','Ethical Hacking','Penetration Testing','Cryptography','Firewalls','SIEM Tools','Git','Security Protocols'],
    'Cloud Engineer (AWS)': ['AWS','EC2','S3','Lambda','RDS','VPC','CloudFormation','Docker','Terraform','Networking'],
    'Data Analyst': ['SQL','Python','Excel','Tableau','Power BI','Statistics','Data Visualization','Pandas','NumPy','Google Analytics'],
    'Blockchain Developer': ['Solidity','Web3.js','Ethereum','Smart Contracts','JavaScript','Python','Git','REST APIs','Cryptography','Node.js']
};

const defaultSkills = ['HTML','CSS','JavaScript','Python','Java','C++','React','Node.js','MySQL','MongoDB','Git','Figma','PHP','Bootstrap','TypeScript'];

function updateQuickAddSkills() {
    const jobInput = document.getElementById('dream_job');
    const container = document.getElementById('quick-add-container');
    const roleSpan = document.getElementById('quick-add-role');
    const job = jobInput.value.trim();
    
    let skillsToShow = defaultSkills;
    
    // Check if the exact job matches, or find the closest match
    let matchedJob = null;
    for (const key in jobSkillsMap) {
        if (job.toLowerCase() === key.toLowerCase()) {
            matchedJob = key;
            break;
        }
    }
    
    if (matchedJob) {
        skillsToShow = jobSkillsMap[matchedJob];
        roleSpan.textContent = `FOR ${matchedJob.toUpperCase()} `;
    } else {
        roleSpan.textContent = '';
    }

    // Update the DOM
    container.innerHTML = '';
    skillsToShow.forEach(skill => {
        const span = document.createElement('span');
        span.className = 'job-chip';
        span.textContent = skill;
        span.onclick = () => addQuickSkill(skill);
        container.appendChild(span);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const jobInput = document.getElementById('dream_job');
    jobInput.addEventListener('input', updateQuickAddSkills);
    
    // Also trigger when a job chip is clicked
    document.querySelectorAll('.job-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            setTimeout(updateQuickAddSkills, 50); // wait for job chip click handler to set value
        });
    });
});
</script>
</body>
</html>
