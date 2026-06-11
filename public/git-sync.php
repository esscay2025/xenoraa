<?php
// Temporary git sync script - DELETE AFTER USE
// Security: simple token check
$token = $_GET['token'] ?? '';
if ($token !== 'xenoraa_sync_2026') {
    http_response_code(403);
    die('Forbidden');
}

$app_path = '/var/www/gopi.blog/gopi-portfolio';
$output = [];
$return_code = 0;

// Change to app directory
chdir($app_path);

// Run git commands
$commands = [
    'git config --global --add safe.directory ' . $app_path,
    'git -C ' . $app_path . ' status --short',
    'git -C ' . $app_path . ' add -A',
    'git -C ' . $app_path . ' commit -m "feat: Store Config module + v4.12-v4.15 all changes sync" --allow-empty',
    'GIT_SSH_COMMAND="ssh -i /var/www/gopi.blog/.ssh/github_deploy -o StrictHostKeyChecking=no" git -C ' . $app_path . ' push origin main 2>&1',
];

$results = [];
foreach ($commands as $cmd) {
    exec($cmd . ' 2>&1', $out, $rc);
    $results[] = [
        'cmd' => $cmd,
        'output' => implode("\n", $out),
        'return_code' => $rc
    ];
    $out = [];
}

header('Content-Type: application/json');
echo json_encode(['status' => 'done', 'results' => $results], JSON_PRETTY_PRINT);
