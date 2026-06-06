<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g. consultant, advocate
            $table->string('name');           // e.g. Nexus, Lex
            $table->string('category');       // e.g. IT & Technology
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('accent_color', 20)->default('#7c3aed');
            $table->string('bg_color', 20)->default('#ffffff');
            $table->string('preview_css')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_sub')->nullable();
            $table->json('sections')->nullable();
            $table->string('best_for')->nullable();
            $table->string('profession_key')->nullable(); // maps to user profession
            $table->json('profession_keywords')->nullable(); // keywords to match profession
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('thumbnail')->nullable();
            $table->string('demo_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed the 6 default themes
        $themes = [
            [
                'slug'              => 'consultant',
                'name'              => 'Nexus',
                'category'          => 'IT & Technology',
                'description'       => 'Dark, minimal, ultra-professional. Built for IT consultants, solution architects, and tech executives who want to command authority online.',
                'tags'              => json_encode(['Dark', 'Minimal', 'Professional', 'Tech']),
                'accent_color'      => '#7c3aed',
                'bg_color'          => '#0a0a0a',
                'preview_css'       => 'bg:#0a0a0a;text:#fff;accent:#7c3aed;card:#111',
                'hero_title'        => 'IT Consultant & Solution Architect',
                'hero_sub'          => 'Cloud · DevOps · Digital Transformation',
                'sections'          => json_encode(['Hero', 'About', 'Services', 'Portfolio', 'Blog', 'Contact']),
                'best_for'          => 'IT Consultants, Solution Architects, Tech Executives',
                'profession_key'    => 'consultant',
                'profession_keywords' => json_encode(['it', 'tech', 'software', 'developer', 'consultant', 'architect', 'devops', 'cloud', 'engineer']),
                'is_premium'        => false,
                'is_active'         => true,
                'sort_order'        => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'slug'              => 'influencer',
                'name'              => 'Aura',
                'category'          => 'Creative & Social',
                'description'       => 'Colorful, bold, and social-first. Built for content creators, influencers, and digital personalities who live for engagement.',
                'tags'              => json_encode(['Colorful', 'Bold', 'Social', 'Creative']),
                'accent_color'      => '#f43f5e',
                'bg_color'          => '#fff7f7',
                'preview_css'       => 'bg:#fff7f7;text:#1a1a1a;accent:#f43f5e;card:#fff',
                'hero_title'        => 'Lifestyle Influencer & Content Creator',
                'hero_sub'          => 'Fashion · Travel · Beauty',
                'sections'          => json_encode(['Hero', 'About', 'Portfolio', 'Collaborations', 'Blog', 'Contact']),
                'best_for'          => 'Influencers, Bloggers, YouTubers, Creators',
                'profession_key'    => 'influencer',
                'profession_keywords' => json_encode(['influencer', 'creator', 'blogger', 'youtuber', 'social media', 'content', 'lifestyle', 'fashion', 'travel']),
                'is_premium'        => true,
                'is_active'         => true,
                'sort_order'        => 2,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'slug'              => 'advocate',
                'name'              => 'Lex',
                'category'          => 'Legal & Advocacy',
                'description'       => 'Authoritative, trust-building, and clean. The perfect digital presence for lawyers, advocates, and legal consultants.',
                'tags'              => json_encode(['Professional', 'Trust', 'Clean', 'Legal']),
                'accent_color'      => '#0ea5e9',
                'bg_color'          => '#f8fafc',
                'preview_css'       => 'bg:#f8fafc;text:#0f172a;accent:#0ea5e9;card:#fff',
                'hero_title'        => 'Senior Advocate & Legal Consultant',
                'hero_sub'          => 'Corporate Law · Civil Litigation · IP',
                'sections'          => json_encode(['Hero', 'Practice Areas', 'About', 'Vacancies', 'Blog', 'Contact']),
                'best_for'          => 'Lawyers, Advocates, Legal Consultants',
                'profession_key'    => 'advocate',
                'profession_keywords' => json_encode(['lawyer', 'advocate', 'legal', 'attorney', 'counsel', 'law', 'litigation', 'solicitor', 'barrister']),
                'is_premium'        => true,
                'is_active'         => true,
                'sort_order'        => 3,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'slug'              => 'entrepreneur',
                'name'              => 'Momentum',
                'category'          => 'Business & Startup',
                'description'       => 'Bold, energetic, and conversion-focused. Built for founders, startup CEOs, and business coaches who mean business.',
                'tags'              => json_encode(['Bold', 'Startup', 'Growth', 'Business']),
                'accent_color'      => '#f59e0b',
                'bg_color'          => '#0f0f0f',
                'preview_css'       => 'bg:#0f0f0f;text:#fff;accent:#f59e0b;card:#1c1c1c',
                'hero_title'        => 'Founder, Investor & Business Coach',
                'hero_sub'          => 'Startups · Growth · Leadership',
                'sections'          => json_encode(['Hero', 'About', 'Portfolio', 'Services', 'Blog', 'Contact']),
                'best_for'          => 'Founders, CEOs, Business Coaches, Investors',
                'profession_key'    => 'entrepreneur',
                'profession_keywords' => json_encode(['founder', 'ceo', 'entrepreneur', 'startup', 'business', 'investor', 'coach', 'executive', 'director']),
                'is_premium'        => true,
                'is_active'         => true,
                'sort_order'        => 4,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'slug'              => 'doctor',
                'name'              => 'Vitae',
                'category'          => 'Healthcare & Medical',
                'description'       => 'Calm, reassuring, and clinically clean. Designed for doctors, specialists, and healthcare professionals who put patients first.',
                'tags'              => json_encode(['Clean', 'Medical', 'Calm', 'Trust']),
                'accent_color'      => '#10b981',
                'bg_color'          => '#f0fdf4',
                'preview_css'       => 'bg:#f0fdf4;text:#064e3b;accent:#10b981;card:#fff',
                'hero_title'        => 'Consultant Physician & Specialist',
                'hero_sub'          => 'Internal Medicine · Cardiology · Wellness',
                'sections'          => json_encode(['Hero', 'About', 'Specialisations', 'Appointments', 'Blog', 'Contact']),
                'best_for'          => 'Doctors, Surgeons, Specialists, Clinics',
                'profession_key'    => 'doctor',
                'profession_keywords' => json_encode(['doctor', 'physician', 'surgeon', 'specialist', 'medical', 'healthcare', 'clinic', 'hospital', 'dentist', 'nurse']),
                'is_premium'        => true,
                'is_active'         => true,
                'sort_order'        => 5,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'slug'              => 'politician',
                'name'              => 'Civitas',
                'category'          => 'Politics & Public Service',
                'description'       => 'Patriotic, powerful, and people-first. Built for politicians, public servants, and civic leaders who want to connect with constituents.',
                'tags'              => json_encode(['Patriotic', 'Bold', 'Community', 'Leadership']),
                'accent_color'      => '#dc2626',
                'bg_color'          => '#fafafa',
                'preview_css'       => 'bg:#fafafa;text:#111;accent:#dc2626;card:#fff',
                'hero_title'        => 'Public Servant & Community Leader',
                'hero_sub'          => 'Governance · Development · People',
                'sections'          => json_encode(['Hero', 'Vision', 'Achievements', 'Events', 'Blog', 'Contact']),
                'best_for'          => 'Politicians, Public Servants, NGO Leaders',
                'profession_key'    => 'politician',
                'profession_keywords' => json_encode(['politician', 'minister', 'mla', 'mp', 'councillor', 'public servant', 'ngo', 'civic', 'government', 'leader']),
                'is_premium'        => true,
                'is_active'         => true,
                'sort_order'        => 6,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        DB::table('themes')->insert($themes);
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
