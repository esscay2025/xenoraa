<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PortfolioSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $roles = [
            ['id' => 1, 'name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Full access to all modules and system settings.'],
            ['id' => 2, 'name' => 'staff', 'display_name' => 'Staff Member', 'description' => 'Access to specific modules like Expense Manager and Job Portal.'],
            ['id' => 3, 'name' => 'visitor', 'display_name' => 'Website Visitor', 'description' => 'Access to blog content, jobs, and ability to leave reviews/comments.'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['id' => $role['id']], $role);
        }

        // 2. Seed Default Admin User
        DB::table('users')->updateOrInsert(
            ['email' => 'gopi@outlook.in'],
            [
                'name' => 'Gopi K',
                'role_id' => 1, // Admin
                'password' => Hash::make('@biSou20717'),
                'email_verified_at' => now(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Seed some dummy staff and visitor users for testing
        DB::table('users')->updateOrInsert(
            ['email' => 'staff@esscay.com'],
            [
                'name' => 'Staff Member',
                'role_id' => 2, // Staff
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. Seed Social Links
        $socials = [
            ['platform' => 'linkedin', 'url' => 'https://www.linkedin.com/in/gopi-k-77ab3b19', 'icon_class' => 'fab fa-linkedin'],
            ['platform' => 'instagram', 'url' => 'https://instagram.com', 'icon_class' => 'fab fa-instagram'],
            ['platform' => 'facebook', 'url' => 'https://facebook.com', 'icon_class' => 'fab fa-facebook'],
            ['platform' => 'threads', 'url' => 'https://threads.net', 'icon_class' => 'fab fa-threads'],
            ['platform' => 'x', 'url' => 'https://x.com', 'icon_class' => 'fab fa-x-twitter'],
            ['platform' => 'behance', 'url' => 'https://behance.net', 'icon_class' => 'fab fa-behance'],
            ['platform' => 'fiverr', 'url' => 'https://fiverr.com', 'icon_class' => 'fas fa-briefcase'],
            ['platform' => 'upwork', 'url' => 'https://upwork.com', 'icon_class' => 'fas fa-laptop-code'],
        ];

        foreach ($socials as $social) {
            DB::table('social_links')->updateOrInsert(['platform' => $social['platform']], $social);
        }

        // 4. Seed Portfolio Experiences (from LinkedIn)
        $experiences = [
            [
                'company_name' => 'Go Esscay Solutions',
                'role' => 'Sales & Marketing Specialist / Founder',
                'start_date' => '2025-01-01',
                'end_date' => null,
                'is_current' => true,
                'description' => 'Founded Go Esscay Solutions to help startups and small businesses implement IT, automation, and open-source applications to run their business smarter, faster, and more efficiently. Focus on making technology simple, affordable, and accessible for every business.',
            ]
        ];

        foreach ($experiences as $exp) {
            DB::table('portfolio_experiences')->updateOrInsert(
                ['company_name' => $exp['company_name'], 'role' => $exp['role']],
                $exp
            );
        }

        // 5. Seed Blog Categories & Posts
        DB::table('blog_categories')->updateOrInsert(
            ['slug' => 'open-source'],
            ['name' => 'Open Source & Automation', 'slug' => 'open-source', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('blog_posts')->updateOrInsert(
            ['slug' => 'welcome-to-go-esscay-solutions'],
            [
                'user_id' => 1,
                'category_id' => 1,
                'title' => 'Welcome to Go Esscay Solutions!',
                'slug' => 'welcome-to-go-esscay-solutions',
                'summary' => 'Our vision is to make technology simple, affordable, and accessible for every business.',
                'content' => 'At Go Esscay Solutions, we focus on helping startups and small businesses implement IT, automation, and open-source applications to run their business smarter, faster, and more efficiently. This journey is still new, but the mission is clear - to make technology simple, affordable, and accessible for every business.',
                'status' => 'published',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 6. Seed Expense Categories
        $expenseCategories = [
            ['name' => 'Software & Subscriptions', 'type' => 'business'],
            ['name' => 'Marketing & Ads', 'type' => 'business'],
            ['name' => 'Travel & Transport', 'type' => 'business'],
            ['name' => 'Office Supplies', 'type' => 'business'],
            ['name' => 'Food & Dining', 'type' => 'personal'],
            ['name' => 'Rent & Utilities', 'type' => 'personal'],
        ];

        foreach ($expenseCategories as $cat) {
            DB::table('expense_categories')->updateOrInsert(['name' => $cat['name']], $cat);
        }
    }
}
