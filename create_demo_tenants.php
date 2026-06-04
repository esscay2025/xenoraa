<?php
/**
 * Demo Tenant Creation Script
 * Run via: php artisan tinker < create_demo_tenants.php
 */

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Get admin role id
$adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

// =============================================
// TENANT 1: Influencer — Priya Sharma
// =============================================
$influencer = User::updateOrCreate(
    ['email' => 'priya@xenoraa.com'],
    [
        'name'                 => 'Priya Sharma',
        'email'                => 'priya@xenoraa.com',
        'password'             => Hash::make('Priya@Xenoraa2025'),
        'role_id'              => $adminRoleId,
        'username'             => 'priya',
        'plan'                 => 'professional',
        'profession'           => 'Influencer',
        'profile_template'     => 'influencer',
        'site_title'           => 'Priya Sharma — Lifestyle & Fashion Creator',
        'bio'                  => 'Hey! I\'m Priya — a lifestyle, fashion, and travel content creator based in Mumbai. With 2.4M+ followers across platforms, I help brands tell authentic stories that resonate with Gen Z and millennials. From luxury fashion collabs to sustainable living campaigns, I bring creativity and data-driven results together.',
        'avatar'               => null,
        'status'               => 'active',
        'trial_ends_at'        => null,
        'plan_expires_at'      => now()->addYear(),
        'onboarding_completed' => 1,
        'email_verified_at'    => now(),
    ]
);

echo "Created Influencer: priya@xenoraa.com\n";
echo "Profile URL: xenoraa.com/priya\n";

// =============================================
// TENANT 2: Advocate — Arjun Mehta
// =============================================
$advocate = User::updateOrCreate(
    ['email' => 'arjun@xenoraa.com'],
    [
        'name'                 => 'Arjun Mehta',
        'email'                => 'arjun@xenoraa.com',
        'password'             => Hash::make('Arjun@Xenoraa2025'),
        'role_id'              => $adminRoleId,
        'username'             => 'arjun',
        'plan'                 => 'professional',
        'profession'           => 'Advocate',
        'profile_template'     => 'advocate',
        'site_title'           => 'Arjun Mehta — Senior Advocate & Legal Consultant',
        'bio'                  => 'Arjun Mehta is a Senior Advocate at the Bombay High Court with 14+ years of experience in corporate law, intellectual property, and civil litigation. He has represented Fortune 500 companies, startups, and individuals in high-stakes legal matters. Known for his meticulous case preparation and courtroom presence, Arjun combines deep legal expertise with practical business acumen.',
        'avatar'               => null,
        'status'               => 'active',
        'trial_ends_at'        => null,
        'plan_expires_at'      => now()->addYear(),
        'onboarding_completed' => 1,
        'email_verified_at'    => now(),
    ]
);

echo "Created Advocate: arjun@xenoraa.com\n";
echo "Profile URL: xenoraa.com/arjun\n";
echo "Done!\n";
