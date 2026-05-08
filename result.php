<?php
require_once 'config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('analyze.php');

$dream_job      = trim($_POST['dream_job'] ?? '');
$current_skills = trim($_POST['current_skills'] ?? '');

if (!$dream_job || !$current_skills) redirect('analyze.php');

// Job to Skills Mapping
$job_skills_map = [
    'MERN Stack Developer' => ['HTML','CSS','JavaScript','React','Node.js','Express','MongoDB','REST APIs','TypeScript','Redux','Git','Docker'],
    'Full Stack Developer' => ['HTML','CSS','JavaScript','React','Node.js','Express','MongoDB','SQL','Git','REST APIs','TypeScript','AWS'],
    'Frontend Developer' => ['HTML','CSS','JavaScript','React','TypeScript','Redux','Figma','Git','REST APIs','Bootstrap'],
    'Backend Developer' => ['Node.js','Express','Python','Java','SQL','MongoDB','REST APIs','Git','Docker','AWS'],
    'Data Scientist' => ['Python','R','SQL','Machine Learning','TensorFlow','Pandas','NumPy','Matplotlib','Jupyter','Statistics'],
    'Machine Learning Engineer' => ['Python','TensorFlow','Scikit-learn','Deep Learning','SQL','Data Analysis','PyTorch','Keras','Git','Statistics'],
    'UI/UX Designer' => ['Figma','Adobe XD','UI Design','Wireframing','Prototyping','User Research','CSS','HTML','JavaScript','Adobe Creative Suite'],
    'DevOps Engineer' => ['Docker','Kubernetes','AWS','CI/CD','Linux','Git','Jenkins','Terraform','Monitoring','Bash'],
    'Android Developer' => ['Java','Kotlin','Android Studio','XML','REST APIs','Firebase','SQLite','Git','Material Design','Java'],
    'iOS Developer' => ['Swift','Objective-C','Xcode','iOS SDK','REST APIs','Core Data','Git','Firebase','UIKit','SwiftUI'],
    'Python Developer' => ['Python','Django','Flask','SQL','REST APIs','Git','PostgreSQL','Redis','Docker','Testing'],
    'Java Developer' => ['Java','Spring Boot','SQL','Maven','Git','REST APIs','JUnit','Microservices','Docker','Kubernetes'],
    'Cybersecurity Analyst' => ['Linux','Networking','Python','Ethical Hacking','Penetration Testing','Cryptography','Firewalls','SIEM Tools','Git','Security Protocols'],
    'Cloud Engineer (AWS)' => ['AWS','EC2','S3','Lambda','RDS','VPC','CloudFormation','Docker','Terraform','Networking'],
    'Data Analyst' => ['SQL','Python','Excel','Tableau','Power BI','Statistics','Data Visualization','Pandas','NumPy','Google Analytics'],
    'Blockchain Developer' => ['Solidity','Web3.js','Ethereum','Smart Contracts','JavaScript','Python','Git','REST APIs','Cryptography','Node.js']
];

// Job to Roadmap Mapping
$job_roadmap_map = [
    'MERN Stack Developer' => [
        ['step'=>1,'title'=>'Master JavaScript Fundamentals','description'=>'Deep dive into ES6+, async/await, closures, and prototype-based OOP.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'JavaScript.info, Eloquent JavaScript'],
        ['step'=>2,'title'=>'Learn React.js Thoroughly','description'=>'Components, hooks (useState, useEffect), context API, and state management with Redux.','duration'=>'4-6 weeks','difficulty'=>'Medium','resources'=>'React Official Docs, React Query, Redux Toolkit'],
        ['step'=>3,'title'=>'Backend with Node.js & Express','description'=>'Build REST APIs, middleware, authentication, error handling, and API design patterns.','duration'=>'4-5 weeks','difficulty'=>'Medium','resources'=>'Node.js Docs, Express.js Guide, REST API Best Practices'],
        ['step'=>4,'title'=>'MongoDB & Data Persistence','description'=>'Schema design, CRUD operations, indexing, aggregation pipeline, and data modeling.','duration'=>'2-3 weeks','difficulty'=>'Easy','resources'=>'MongoDB University, Mongoose Documentation'],
        ['step'=>5,'title'=>'Build Full-Stack Projects','description'=>'Create 2-3 MERN stack projects, implement authentication, deploy to Vercel/Heroku.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'GitHub, Vercel, Heroku, Firebase']
    ],
    'Full Stack Developer' => [
        ['step'=>1,'title'=>'Frontend Fundamentals','description'=>'HTML, CSS, JavaScript, responsive design, and browser APIs.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'MDN Web Docs, freeCodeCamp'],
        ['step'=>2,'title'=>'Frontend Framework Mastery','description'=>'React or Vue.js - components, lifecycle, state management, and routing.','duration'=>'4-6 weeks','difficulty'=>'Medium','resources'=>'Official Framework Docs, Scrimba Courses'],
        ['step'=>3,'title'=>'Backend Development','description'=>'Node.js/Express or Python/Django, REST APIs, middleware, and authentication.','duration'=>'4-5 weeks','difficulty'=>'Medium','resources'=>'Backend Framework Docs, API Design Patterns'],
        ['step'=>4,'title'=>'Database Design','description'=>'SQL/NoSQL, relational design, query optimization, and data migrations.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'SQL Tutorials, Database Design Course'],
        ['step'=>5,'title'=>'DevOps & Deployment','description'=>'Docker, CI/CD, cloud deployment (AWS/Heroku), monitoring, and scaling.','duration'=>'3-4 weeks','difficulty'=>'Hard','resources'=>'Docker Docs, Cloud Platform Guides']
    ],
    'Frontend Developer' => [
        ['step'=>1,'title'=>'HTML & CSS Mastery','description'=>'Semantic HTML, CSS Grid, Flexbox, animations, and responsive design.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'MDN, CSS Tricks, Codecademy'],
        ['step'=>2,'title'=>'JavaScript ES6+ Skills','description'=>'Async operations, DOM manipulation, arrow functions, destructuring, and modules.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'JavaScript.info, Udemy JavaScript Course'],
        ['step'=>3,'title'=>'React Deep Dive','description'=>'Hooks, Context API, performance optimization, testing with Jest/React Testing Library.','duration'=>'4-6 weeks','difficulty'=>'Medium','resources'=>'React Docs, Epic React Course'],
        ['step'=>4,'title'=>'State Management & APIs','description'=>'Redux/Zustand, fetch/axios, GraphQL basics, and API integration patterns.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'Redux Documentation, GraphQL Tutorial'],
        ['step'=>5,'title'=>'Build Portfolio Projects','description'=>'Create 3-4 frontend projects showcasing responsive design, animations, and state management.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'GitHub, CodePen, Dribbble for inspiration']
    ],
    'Backend Developer' => [
        ['step'=>1,'title'=>'Core Programming & Data Structures','description'=>'Choose Python/Java/Node.js, master fundamentals, OOP, algorithms, and data structures.','duration'=>'4-5 weeks','difficulty'=>'Easy','resources'=>'LeetCode, GeeksforGeeks, Cracking Coding Interview'],
        ['step'=>2,'title'=>'Backend Frameworks','description'=>'Django/Flask for Python, Spring Boot for Java, or Express for Node.js.','duration'=>'4-5 weeks','difficulty'=>'Medium','resources'=>'Official Framework Docs, Full Stack Python'],
        ['step'=>3,'title'=>'Database Mastery','description'=>'SQL (PostgreSQL), schema design, indexing, transactions, and NoSQL (MongoDB) basics.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'PostgreSQL Documentation, SQL Tutorial'],
        ['step'=>4,'title'=>'API Design & Security','description'=>'REST principles, authentication (JWT), authorization, input validation, and error handling.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'REST API Best Practices, OWASP Security Guide'],
        ['step'=>5,'title'=>'Deployment & Scalability','description'=>'Docker, Kubernetes basics, cloud deployment, caching (Redis), and performance optimization.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'Docker Docs, Kubernetes Tutorial, Redis Guide']
    ],
    'Data Scientist' => [
        ['step'=>1,'title'=>'Python Fundamentals','description'=>'Data types, control flow, functions, OOP, and libraries (NumPy, Pandas).','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Codecademy Python, Real Python'],
        ['step'=>2,'title'=>'Data Analysis & Manipulation','description'=>'Pandas for data cleaning, EDA techniques, and exploratory analysis workflows.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Pandas Documentation, Kaggle Datasets'],
        ['step'=>3,'title'=>'Statistical Analysis & Visualization','description'=>'Descriptive/inferential statistics, hypothesis testing, Matplotlib, Seaborn, and Plotly.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'Statistics Course, Matplotlib Documentation'],
        ['step'=>4,'title'=>'Machine Learning Foundations','description'=>'Supervised/unsupervised learning, model evaluation, cross-validation, and scikit-learn.','duration'=>'5-6 weeks','difficulty'=>'Medium','resources'=>'Scikit-learn Documentation, Andrew Ng ML Course'],
        ['step'=>5,'title'=>'Deep Learning & Projects','description'=>'TensorFlow/PyTorch basics, neural networks, and build 2-3 portfolio ML projects.','duration'=>'6-8 weeks','difficulty'=>'Hard','resources'=>'TensorFlow Docs, Fast.ai Deep Learning']
    ],
    'Machine Learning Engineer' => [
        ['step'=>1,'title'=>'Advanced Python & Statistics','description'=>'Advanced NumPy, Pandas, linear algebra, calculus, and probability theory.','duration'=>'4-5 weeks','difficulty'=>'Medium','resources'=>'3Blue1Brown, StatQuest with Josh Starmer'],
        ['step'=>2,'title'=>'Classical Machine Learning','description'=>'Regression, classification, clustering, ensemble methods, and hyperparameter tuning.','duration'=>'4-5 weeks','difficulty'=>'Medium','resources'=>'Scikit-learn Docs, Kaggle Competitions'],
        ['step'=>3,'title'=>'Deep Learning Fundamentals','description'=>'Neural networks, CNNs, RNNs, backpropagation, and TensorFlow/PyTorch frameworks.','duration'=>'5-6 weeks','difficulty'=>'Hard','resources'=>'Deep Learning Specialization, Fast.ai'],
        ['step'=>4,'title'=>'Advanced Deep Learning','description'=>'Transformers, NLP, computer vision, and advanced architectures like GANs and RL.','duration'=>'6-8 weeks','difficulty'=>'Hard','resources'=>'Hugging Face, Papers with Code, arXiv'],
        ['step'=>5,'title'=>'Production & MLOps','description'=>'Model deployment, monitoring, A/B testing, MLflow, and scalable ML systems.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'Made With ML, MLOps Guide']
    ],
    'UI/UX Designer' => [
        ['step'=>1,'title'=>'Design Fundamentals','description'=>'Color theory, typography, composition, hierarchy, and visual principles.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Interaction Design Foundation, Design of Everyday Things'],
        ['step'=>2,'title'=>'User Research & Testing','description'=>'User personas, wireframing, prototyping, usability testing, and user interviews.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Nielsen Norman Group, UX Research Methods'],
        ['step'=>3,'title'=>'Figma & Design Tools','description'=>'Master Figma, component libraries, design systems, and handoff workflows.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'Figma Community, Design System Course'],
        ['step'=>4,'title'=>'Interaction & Motion Design','description'=>'Micro-interactions, animations, transitions, and motion design principles.','duration'=>'2-3 weeks','difficulty'=>'Medium','resources'=>'Interaction Design, Framer Documentation'],
        ['step'=>5,'title'=>'Build Portfolio','description'=>'Create 3-4 full design case studies showcasing research, design process, and iterations.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'Behance, Dribbble, Designer Portfolios']
    ],
    'DevOps Engineer' => [
        ['step'=>1,'title'=>'Linux & System Administration','description'=>'Linux basics, shell scripting, file systems, processes, networking, and permissions.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Linux Academy, Ubuntu Server Guide'],
        ['step'=>2,'title'=>'Containerization with Docker','description'=>'Docker fundamentals, images, containers, networking, volumes, and compose.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'Docker Documentation, Docker Deep Dive Book'],
        ['step'=>3,'title'=>'Container Orchestration','description'=>'Kubernetes architecture, deployments, services, networking, and scaling.','duration'=>'5-6 weeks','difficulty'=>'Hard','resources'=>'Kubernetes Documentation, Linux Foundation Course'],
        ['step'=>4,'title'=>'CI/CD & Automation','description'=>'Jenkins, GitHub Actions, GitLab CI, infrastructure as code, and automation pipelines.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'Jenkins Docs, GitOps Practices'],
        ['step'=>5,'title'=>'Cloud Platforms & Monitoring','description'=>'AWS/GCP/Azure, terraform, monitoring stacks (Prometheus, ELK), and incident management.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'AWS Documentation, Terraform Guide']
    ],
    'Android Developer' => [
        ['step'=>1,'title'=>'Java Fundamentals','description'=>'OOP, collections, exception handling, multithreading, and design patterns.','duration'=>'4-5 weeks','difficulty'=>'Easy','resources'=>'Effective Java Book, Java Documentation'],
        ['step'=>2,'title'=>'Android Basics','description'=>'Activities, fragments, intents, lifecycle, layouts, and Android manifest.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'Android Official Documentation, Android Codelabs'],
        ['step'=>3,'title'=>'Android Architecture & Database','description'=>'MVVM pattern, Room database, repositories, LiveData, and data binding.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'Architecture Components Guide, Google Codelabs'],
        ['step'=>4,'title'=>'APIs & Networking','description'=>'Retrofit, OkHttp, JSON parsing, RESTful integration, and background tasks.','duration'=>'2-3 weeks','difficulty'=>'Medium','resources'=>'Retrofit Documentation, Networking Best Practices'],
        ['step'=>5,'title'=>'Build Portfolio Apps','description'=>'Create 2-3 production-quality apps, publish on Play Store, and implement best practices.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'Android Best Practices, Google Play Console']
    ],
    'iOS Developer' => [
        ['step'=>1,'title'=>'Swift Fundamentals','description'=>'Syntax, types, functions, closures, protocols, and memory management (ARC).','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Swift Official Guide, Ray Wenderlich Swift'],
        ['step'=>2,'title'=>'iOS App Architecture','description'=>'UIKit/SwiftUI, view hierarchy, controllers, navigation, and lifecycle management.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'iOS Documentation, Ray Wenderlich Tutorials'],
        ['step'=>3,'title'=>'Data Persistence','description'=>'Core Data, UserDefaults, Keychain, file system, and database fundamentals.','duration'=>'2-3 weeks','difficulty'=>'Medium','resources'=>'Core Data Documentation, Hacking with Swift'],
        ['step'=>4,'title'=>'Networking & APIs','description'=>'URLSession, Codable, error handling, background tasks, and API integration.','duration'=>'2-3 weeks','difficulty'=>'Medium','resources'=>'URLSession Guide, JSON Parsing in Swift'],
        ['step'=>5,'title'=>'Build & Publish Apps','description'=>'Create 2-3 polished apps, implement app analytics, and publish to App Store.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'App Store Guidelines, TestFlight Distribution']
    ],
    'Python Developer' => [
        ['step'=>1,'title'=>'Advanced Python','description'=>'Decorators, generators, context managers, metaclasses, and async/await.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Fluent Python Book, Real Python'],
        ['step'=>2,'title'=>'Web Framework Mastery','description'=>'Django or Flask - models, views, templates, authentication, and middleware.','duration'=>'4-5 weeks','difficulty'=>'Medium','resources'=>'Django Documentation, Two Scoops of Django'],
        ['step'=>3,'title'=>'Database & ORM','description'=>'Django ORM, PostgreSQL, migrations, relationships, and query optimization.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'ORM Documentation, SQL Fundamentals'],
        ['step'=>4,'title'=>'RESTful APIs & Serialization','description'=>'Django REST Framework, serializers, authentication, permissions, and versioning.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'DRF Documentation, Building Web APIs Course'],
        ['step'=>5,'title'=>'Deployment & Production','description'=>'Docker, testing (pytest), CI/CD, gunicorn/uWSGI, and cloud deployment.','duration'=>'3-4 weeks','difficulty'=>'Hard','resources'=>'Production Python, Docker Guide']
    ],
    'Java Developer' => [
        ['step'=>1,'title'=>'Advanced Java','description'=>'Collections, generics, streams, lambda expressions, and functional programming.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Effective Java, Oracle Java Docs'],
        ['step'=>2,'title'=>'Spring Framework','description'=>'Spring Boot, dependency injection, AOP, transactions, and configuration.','duration'=>'4-5 weeks','difficulty'=>'Medium','resources'=>'Spring Documentation, Spring in Action Book'],
        ['step'=>3,'title'=>'Spring Boot & Web Development','description'=>'REST APIs, security, data access with JPA/Hibernate, and validation.','duration'=>'4-5 weeks','difficulty'=>'Medium','resources'=>'Spring Boot Guide, Building Microservices'],
        ['step'=>4,'title'=>'Database & Persistence','description'=>'JPA/Hibernate, schema design, migrations, relationships, and best practices.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'JPA Documentation, Hibernate Guide'],
        ['step'=>5,'title'=>'Production Java','description'=>'Testing (JUnit, Mockito), Docker containerization, deployment, and performance tuning.','duration'=>'3-4 weeks','difficulty'=>'Hard','resources'=>'Java Performance Tuning, Docker Deployment']
    ],
    'Cybersecurity Analyst' => [
        ['step'=>1,'title'=>'Networking Fundamentals','description'=>'TCP/IP, DNS, HTTP/HTTPS, firewalls, VPNs, and network protocols.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'CompTIA Network+, Cisco Networking'],
        ['step'=>2,'title'=>'Linux & System Hardening','description'=>'Linux command line, file permissions, user management, and system security.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'Linux Academy, Ubuntu Security Guide'],
        ['step'=>3,'title'=>'Cybersecurity Fundamentals','description'=>'Encryption, authentication, authorization, vulnerabilities, and threat models.','duration'=>'4-5 weeks','difficulty'=>'Medium','resources'=>'CompTIA Security+, OWASP Guide'],
        ['step'=>4,'title'=>'Ethical Hacking & Penetration Testing','description'=>'Reconnaissance, scanning, enumeration, exploitation, and reporting techniques.','duration'=>'5-6 weeks','difficulty'=>'Hard','resources'=>'CEH Course, HackerOne Tutorials'],
        ['step'=>5,'title'=>'Advanced Security Topics','description'=>'Incident response, digital forensics, security governance, and compliance frameworks.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'GCIH, CISSP Study Guides']
    ],
    'Cloud Engineer (AWS)' => [
        ['step'=>1,'title'=>'AWS Fundamentals','description'=>'AWS core services, account management, billing, security best practices.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'AWS Training, A Cloud Guru AWS Fundamentals'],
        ['step'=>2,'title'=>'Compute & Storage Services','description'=>'EC2, S3, RDS, EBS, snapshots, and backup strategies.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'AWS Documentation, WhizLabs AWS Solutions'],
        ['step'=>3,'title'=>'Networking & Load Balancing','description'=>'VPC, subnets, security groups, NAT, load balancers, and CloudFront.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'AWS Networking Guide, VPC Deep Dive'],
        ['step'=>4,'title'=>'Infrastructure as Code','description'=>'CloudFormation, Terraform, automation, and infrastructure provisioning.','duration'=>'3-4 weeks','difficulty'=>'Hard','resources'=>'Terraform Documentation, CloudFormation Guide'],
        ['step'=>5,'title'=>'Advanced Services & Certification','description'=>'Lambda, DynamoDB, monitoring (CloudWatch), and prepare for AWS Solutions Architect.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'AWS Solutions Architect Exam, Linux Academy']
    ],
    'Data Analyst' => [
        ['step'=>1,'title'=>'SQL Mastery','description'=>'Complex queries, joins, aggregations, window functions, and query optimization.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Mode Analytics SQL Tutorial, LeetCode SQL'],
        ['step'=>2,'title'=>'Python for Data Analysis','description'=>'Pandas, NumPy, data cleaning, wrangling, and exploratory analysis.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Pandas Documentation, Python for Data Analysis Book'],
        ['step'=>3,'title'=>'Data Visualization','description'=>'Tableau, Power BI, Matplotlib, Seaborn, and storytelling with data.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'Tableau Training, Power BI Documentation'],
        ['step'=>4,'title'=>'Statistical Analysis','description'=>'Descriptive statistics, hypothesis testing, regression, and A/B testing.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'Statistics Course, Coursera Statistics'],
        ['step'=>5,'title'=>'Business Intelligence','description'=>'Data warehousing, ETL processes, dashboards, and business metrics.','duration'=>'3-4 weeks','difficulty'=>'Hard','resources'=>'Business Intelligence Guide, Google Analytics']
    ],
    'Blockchain Developer' => [
        ['step'=>1,'title'=>'Blockchain Fundamentals','description'=>'Cryptocurrencies, distributed systems, consensus mechanisms, and blockchain architecture.','duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Blockchain Basics Course, Bitcoin Whitepaper'],
        ['step'=>2,'title'=>'Ethereum & Smart Contracts','description'=>'Ethereum architecture, accounts, transactions, gas, and EVM concepts.','duration'=>'3-4 weeks','difficulty'=>'Medium','resources'=>'Ethereum Docs, Ethereum Stack Exchange'],
        ['step'=>3,'title'=>'Solidity Programming','description'=>'Smart contract development, security best practices, testing, and debugging.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'Solidity Documentation, CryptoZombies Course'],
        ['step'=>4,'title'=>'Web3.js & dApps','description'=>'Interact with blockchain, Web3.js, MetaMask integration, and dApp development.','duration'=>'3-4 weeks','difficulty'=>'Hard','resources'=>'Web3.js Documentation, Hardhat Guide'],
        ['step'=>5,'title'=>'Advanced & Deployment','description'=>'Layer 2 solutions, DeFi protocols, security audits, and smart contract deployment.','duration'=>'4-5 weeks','difficulty'=>'Very Hard','resources'=>'Uniswap V3 Code, Security Audit Reports']
    ]
];


// Analyze skill gap
function analyzeSkillGap($dream_job, $current_skills, $job_skills_map, $job_roadmap_map) {
    $skill_arr = array_map('trim', explode(',', $current_skills));
    
    // Case-insensitive lookup for skills
    $all = ['HTML','CSS','JavaScript','React','Node.js','Express','MongoDB','SQL','Git','REST APIs','TypeScript','Redux','Docker','AWS'];
    foreach ($job_skills_map as $job => $skills) {
        if (strcasecmp($job, $dream_job) === 0) {
            $all = $skills;
            break;
        }
    }
    
    $have    = array_intersect($all, $skill_arr);
    $missing = array_diff($all, $skill_arr);
    $pct     = min(95, round(count($have) / max(1, count($have) + count($missing)) * 100));
    
    // Case-insensitive lookup for roadmap with fallback
    $roadmap = [
        ['step'=>1,'title'=>'Strengthen Core Fundamentals','description'=>"Master the basic concepts required for a $dream_job role.",'duration'=>'3-4 weeks','difficulty'=>'Easy','resources'=>'Google, Official Docs'],
        ['step'=>2,'title'=>'Learn Specialized Tools','description'=>'Pick up the frameworks, libraries, and tools specific to this field.','duration'=>'4-6 weeks','difficulty'=>'Medium','resources'=>'Online Courses, Tutorials'],
        ['step'=>3,'title'=>'Apply Advanced Techniques','description'=>'Dive into advanced concepts and industry best practices.','duration'=>'4-5 weeks','difficulty'=>'Medium','resources'=>'Industry Blogs, Advanced Books'],
        ['step'=>4,'title'=>'Build Real-World Projects','description'=>'Apply your skills by building 2-3 portfolio-ready projects.','duration'=>'4-5 weeks','difficulty'=>'Hard','resources'=>'GitHub, StackOverflow'],
        ['step'=>5,'title'=>'Interview Preparation','description'=>'Practice common interview questions and system design for this role.','duration'=>'2-3 weeks','difficulty'=>'Hard','resources'=>'LeetCode, Mock Interviews']
    ];
    
    foreach ($job_roadmap_map as $job => $map) {
        if (strcasecmp($job, $dream_job) === 0) {
            $roadmap = $map;
            break;
        }
    }

    return [
        'match_percent'    => $pct,
        'skills_required'  => array_slice($all, 0, 8),
        'skills_have'      => array_values($have),
        'skills_missing'   => array_values(array_slice($missing, 0, 6)),
        'summary'          => count($have) >= count($all) ? "🎉 Congratulations! You have all the key skills for a $dream_job!" : 
                             (count($have) / count($all) >= 0.6 ? "You have most of the key skills! Focus on the missing ones to accelerate your path to $dream_job." :
                             "You have some foundational skills, but need more core technologies to land a role as a $dream_job. Focus on the roadmap below."),
        'roadmap'          => $roadmap
    ];
}

$result = analyzeSkillGap($dream_job, $current_skills, $job_skills_map, $job_roadmap_map);

// Save to DB
$pdo->prepare("INSERT INTO analyses (user_id, dream_job, current_skills, missing_skills, roadmap, match_percent)
               VALUES (?, ?, ?, ?, ?, ?)")
    ->execute([
        $_SESSION['user_id'],
        $dream_job,
        $current_skills,
        implode(', ', $result['skills_missing'] ?? []),
        json_encode($result['roadmap'] ?? []),
        $result['match_percent'] ?? 0
    ]);

$pct   = $result['match_percent'];
$score_class = $pct >= 70 ? 'score-high' : ($pct >= 40 ? 'score-mid' : 'score-low');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Results — Skill Gap System</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="nav-logo">⚡ Skill<span>Gap</span> System</a>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="analyze.php">New Analysis</a>
        <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
</nav>

<div class="container page-sm">

    <!-- RESULT HEADER -->
    <div class="result-header">
        <div class="match-score">
            <div class="score-circle <?= $score_class ?>">
                <div class="pct"><?= $pct ?>%</div>
                <div class="lbl">match</div>
            </div>
            <div class="result-meta">
                <h2>Results for <span class="job-name"><?= htmlspecialchars($dream_job) ?></span></h2>
                <p style="margin-bottom:8px;"><?= htmlspecialchars($result['summary']) ?></p>
                <p style="font-size:13px;color:var(--dim);">
                    <?= count($result['skills_have']) ?> skills matched ·
                    <?= count($result['skills_missing']) ?> skills to learn ·
                    <?= count($result['roadmap']) ?> step roadmap
                </p>
            </div>
        </div>
        <div class="progress-wrap" style="margin-top:1.25rem;">
            <div class="progress-bar <?= $pct>=70?'progress-emerald':'progress-indigo' ?>"
                 data-pct="<?= $pct ?>" style="width:0%"></div>
        </div>
    </div>



    <!-- SKILLS GRID -->
    <div class="results-grid">
        <div class="card">
            <div class="section-title">✅ Skills You Have</div>
            <div>
                <?php if (!empty($result['skills_have'])): ?>
                    <?php foreach ($result['skills_have'] as $s): ?>
                        <span class="skill-pill skill-have">✓ <?= htmlspecialchars($s) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color:var(--muted);font-size:14px;">No matching skills detected yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="section-title">❌ Skills You're Missing</div>
            <div>
                <?php if (!empty($result['skills_missing'])): ?>
                    <?php foreach ($result['skills_missing'] as $s): ?>
                        <span class="skill-pill skill-missing">✗ <?= htmlspecialchars($s) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color:var(--emerald);font-size:14px;">🎉 You seem to have all key skills!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ROADMAP -->
    <?php if (!empty($result['roadmap'])): ?>
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="section-title">🗺️ Your Learning Roadmap</div>
        <div style="margin-top:1rem;">
            <?php foreach ($result['roadmap'] as $step): ?>
            <div class="roadmap-step">
                <div class="step-num"><?= $step['step'] ?></div>
                <div class="step-body">
                    <?php
                    $diff = strtolower($step['difficulty'] ?? 'medium');
                    $bc = $diff === 'easy' ? 'badge-easy' : ($diff === 'hard' ? 'badge-hard' : 'badge-medium');
                    ?>
                    <span class="step-badge <?= $bc ?>"><?= htmlspecialchars($step['difficulty']) ?></span>
                    <h4><?= htmlspecialchars($step['title']) ?></h4>
                    <p><?= htmlspecialchars($step['description']) ?></p>
                    <p style="margin-top:6px;">
                        <span style="color:var(--indigo2);font-size:13px;">⏱ <?= htmlspecialchars($step['duration']) ?></span>
                        <span style="color:var(--dim);font-size:13px;margin-left:12px;">📚 <?= htmlspecialchars($step['resources']) ?></span>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ACTIONS -->
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="analyze.php" class="btn btn-primary">🔁 Analyze Again</a>
        <a href="dashboard.php" class="btn btn-outline">📊 View Dashboard</a>
        <button onclick="window.print()" class="btn btn-outline">🖨 Print / Save PDF</button>
    </div>

</div>

<footer>© 2025 Skill Gap System</footer>
<script src="assets/js/main.js"></script>
</body>
</html>
