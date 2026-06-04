<?php
// Add new columns to users table
$pdo = new PDO('mysql:host=localhost;dbname=gopi_portfolio', 'root', '@biSou20717GK');

$cols = $pdo->query('SHOW COLUMNS FROM users')->fetchAll(PDO::FETCH_COLUMN);

if (!in_array('profile_template', $cols)) {
    $pdo->exec('ALTER TABLE users ADD COLUMN profile_template VARCHAR(50) DEFAULT NULL AFTER profession');
    echo "Added profile_template\n";
} else {
    echo "profile_template already exists\n";
}

if (!in_array('onboarding_completed', $cols)) {
    $pdo->exec('ALTER TABLE users ADD COLUMN onboarding_completed TINYINT(1) DEFAULT 0 AFTER profile_template');
    echo "Added onboarding_completed\n";
} else {
    echo "onboarding_completed already exists\n";
}

echo "Done\n";
