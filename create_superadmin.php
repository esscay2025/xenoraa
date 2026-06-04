<?php
// Run with: php artisan tinker < create_superadmin.php
// OR: php artisan eval "require 'create_superadmin.php';"

$user = \App\Models\User::where('email', 'support@xenoraa.com')->first();

if (!$user) {
    $user = \App\Models\User::create([
        'name'               => 'Xenoraa Support',
        'email'              => 'support@xenoraa.com',
        'password'           => bcrypt('@biSou20717'),
        'email_verified_at'  => now(),
        'username'           => 'xenoraa',
        'plan'               => 'business',
        'status'             => 'active',
    ]);
    echo "User created: ID " . $user->id . PHP_EOL;
} else {
    $user->update(['password' => bcrypt('@biSou20717')]);
    echo "User exists: ID " . $user->id . " (password updated)" . PHP_EOL;
}

// Ensure superadmin role exists
$role = \App\Models\Role::firstOrCreate(
    ['name' => 'superadmin'],
    ['description' => 'Super Administrator']
);

// Assign role
$user->roles()->syncWithoutDetaching([$role->id]);
echo "Role 'superadmin' assigned to support@xenoraa.com" . PHP_EOL;
echo "Login at: https://xenoraa.com/login" . PHP_EOL;
echo "Dashboard: https://xenoraa.com/superadmin/dashboard" . PHP_EOL;
