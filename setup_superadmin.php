<?php
// Create superadmin role if not exists
$role = DB::table('roles')->where('name', 'superadmin')->first();
if (!$role) {
    $roleId = DB::table('roles')->insertGetId([
        'name' => 'superadmin',
        'display_name' => 'Super Administrator',
        'description' => 'Xenoraa platform super admin with full access to all tenants and settings.',
    ]);
    echo "Superadmin role created with id=$roleId\n";
} else {
    $roleId = $role->id;
    echo "Superadmin role exists: id=$roleId\n";
}

// Create or update support@xenoraa.com super admin user
$existing = DB::table('users')->where('email', 'support@xenoraa.com')->first();
if (!$existing) {
    DB::table('users')->insert([
        'name' => 'Xenoraa Support',
        'email' => 'support@xenoraa.com',
        'password' => bcrypt('@biSou20717'),
        'role_id' => $roleId,
        'username' => 'xenoraa',
        'plan' => 'business',
        'status' => 'active',
        'email_verified_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Super admin user created: support@xenoraa.com\n";
} else {
    DB::table('users')->where('email', 'support@xenoraa.com')->update([
        'role_id' => $roleId,
        'password' => bcrypt('@biSou20717'),
        'status' => 'active',
        'updated_at' => now(),
    ]);
    echo "Super admin user updated: support@xenoraa.com\n";
}

// Also add support@xenoraa.com to superadmin_emails in .env check
echo "Done. Login with support@xenoraa.com / @biSou20717\n";
