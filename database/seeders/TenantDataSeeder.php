<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class TenantDataSeeder extends Seeder
{
    public function run(): void
    {
        $priya  = User::where('email', 'priya@xenoraa.com')->first();
        $arjun  = User::where('email', 'arjun@xenoraa.com')->first();
        $gopi   = User::where('email', 'gopi@outlook.in')->first();

        if (!$priya || !$arjun) {
            $this->command->error('Priya or Arjun user not found!');
            return;
        }

        // ── 1. CLEAR PRIYA & ARJUN BLOG POSTS ────────────────────────────────
        DB::table('blog_posts')->whereIn('user_id', [$priya->id, $arjun->id])->delete();

        // ── 2. CLEAR PRIYA & ARJUN JOBS ──────────────────────────────────────
        DB::table('jobs')->whereIn('user_id', [$priya->id, $arjun->id])->delete();

        // ── 3. CLEAR ALL CRM LEADS (unowned + priya/arjun) ───────────────────
        // Keep only Gopi's leads
        DB::table('crm_leads')->where(function($q) use ($gopi) {
            $q->whereNull('user_id')->orWhereNotIn('user_id', [$gopi->id]);
        })->delete();

        // ── 4. CLEAR ALL CHATBOT TRAINING (unowned) ──────────────────────────
        // Keep Gopi's training (user_id = gopi->id or null — null ones belong to gopi)
        // We'll reassign nulls to gopi and create fresh ones for priya/arjun
        DB::table('chatbot_training')->whereNull('user_id')->update(['user_id' => $gopi->id]);
        DB::table('chatbot_training')->whereIn('user_id', [$priya->id, $arjun->id])->delete();

        // ── 5. CLEAR CHATBOT CONVERSATIONS (unowned) ─────────────────────────
        DB::table('chatbot_conversations')->whereNull('user_id')->update(['user_id' => $gopi->id]);
        DB::table('chatbot_conversations')->whereIn('user_id', [$priya->id, $arjun->id])->delete();

        // ── 6. UPDATE SITE SETTINGS for gopi (assign user_id) ────────────────
        DB::table('site_settings')->whereNull('user_id')->update(['user_id' => $gopi->id]);

        // ── 7. SEED PRIYA'S SITE SETTINGS ────────────────────────────────────
        $this->seedSiteSettings($priya->id, [
            'site_name'        => 'Priya Sharma',
            'site_tagline'     => 'Lifestyle Influencer & Content Creator',
            'site_description' => 'Fashion, beauty, travel, and lifestyle content that inspires millions. Collaborations, brand partnerships, and authentic storytelling.',
            'contact_email'    => 'priya@xenoraa.com',
            'contact_phone'    => '+91 98765 43210',
            'contact_address'  => 'Mumbai, Maharashtra, India',
            'theme'            => 'influencer',
            'hero_title'       => 'Creating Content That Connects',
            'hero_subtitle'    => 'Lifestyle | Fashion | Beauty | Travel',
        ]);

        // ── 8. SEED ARJUN'S SITE SETTINGS ────────────────────────────────────
        $this->seedSiteSettings($arjun->id, [
            'site_name'        => 'Arjun Mehta',
            'site_tagline'     => 'Senior Advocate & Legal Consultant',
            'site_description' => 'Expert legal services in corporate law, civil litigation, and intellectual property. Trusted by businesses and individuals across India.',
            'contact_email'    => 'arjun@xenoraa.com',
            'contact_phone'    => '+91 98765 12345',
            'contact_address'  => 'Chennai, Tamil Nadu, India',
            'theme'            => 'advocate',
            'hero_title'       => 'Justice Through Expertise',
            'hero_subtitle'    => 'Corporate Law | Civil Litigation | IP Rights',
        ]);

        // ── 9. SEED PRIYA'S BLOG POSTS ───────────────────────────────────────
        $this->seedPriyaBlogPosts($priya->id);

        // ── 10. SEED ARJUN'S BLOG POSTS ──────────────────────────────────────
        $this->seedArjunBlogPosts($arjun->id);

        // ── 11. SEED PRIYA'S JOBS ────────────────────────────────────────────
        $this->seedPriyaJobs($priya->id);

        // ── 12. SEED ARJUN'S JOBS ────────────────────────────────────────────
        $this->seedArjunJobs($arjun->id);

        // ── 13. SEED PRIYA'S CRM LEADS ───────────────────────────────────────
        $this->seedPriyaLeads($priya->id);

        // ── 14. SEED ARJUN'S CRM LEADS ───────────────────────────────────────
        $this->seedArjunLeads($arjun->id);

        // ── 15. SEED PRIYA'S CHATBOT TRAINING ────────────────────────────────
        $this->seedPriyaChatbotTraining($priya->id);

        // ── 16. SEED ARJUN'S CHATBOT TRAINING ────────────────────────────────
        $this->seedArjunChatbotTraining($arjun->id);

        // ── 17. UPDATE PROFILE TEMPLATES ─────────────────────────────────────
        $priya->update(['profile_template' => 'influencer', 'profession' => 'Influencer']);
        $arjun->update(['profile_template' => 'advocate', 'profession' => 'Advocate']);

        $this->command->info('✅ Tenant data seeded for Priya (Influencer) and Arjun (Advocate)');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SITE SETTINGS
    // ─────────────────────────────────────────────────────────────────────────

    private function seedSiteSettings(int $userId, array $settings): void
    {
        // Remove existing settings for this tenant
        DB::table('site_settings')->where('user_id', $userId)->delete();

        $now = now();
        foreach ($settings as $key => $value) {
            DB::table('site_settings')->insert([
                'user_id'    => $userId,
                'key'        => $key,
                'value'      => $value,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIYA — BLOG POSTS (Influencer / Content Creator)
    // ─────────────────────────────────────────────────────────────────────────

    private function seedPriyaBlogPosts(int $userId): void
    {
        $posts = [
            [
                'title'   => '10 Minimalist Wardrobe Essentials Every Woman Needs in 2025',
                'summary' => 'Building a capsule wardrobe doesn\'t have to be overwhelming. Here are my top 10 pieces that form the foundation of a stylish, versatile closet.',
                'content' => '<p>A minimalist wardrobe is not about having fewer options — it\'s about having the <strong>right</strong> options. After years of experimenting with fashion and collaborating with top brands, I\'ve narrowed it down to 10 essentials that work for every occasion.</p>

<h2>1. The Perfect White Shirt</h2>
<p>A crisp white shirt is the backbone of any capsule wardrobe. Wear it tucked into high-waisted trousers for a boardroom look, or half-tucked with jeans for a casual weekend vibe. I\'ve been loving the linen versions this season — breathable, sustainable, and effortlessly chic.</p>

<h2>2. Tailored Blazer</h2>
<p>A well-fitted blazer instantly elevates any outfit. Go for a neutral tone — camel, grey, or classic black. I pair mine over slip dresses, with wide-leg pants, or even over a simple tee for that smart-casual look.</p>

<h2>3. High-Waisted Dark Denim</h2>
<p>Dark wash denim is incredibly versatile. It can be dressed up with heels and a silk blouse, or kept casual with sneakers and a crop top. The high-waist silhouette is universally flattering and has been my go-to for years.</p>

<h2>4. Little Black Dress (LBD)</h2>
<p>No wardrobe is complete without an LBD. Choose a classic cut — A-line or wrap — that flatters your body type. This one piece can take you from a daytime brunch to an evening event with just a change of accessories.</p>

<h2>5. Neutral Trench Coat</h2>
<p>A trench coat is the ultimate layering piece. Beige or camel works with virtually every colour palette. I wear mine over everything — from gym wear to evening dresses — and it always looks intentional.</p>

<h2>6. Silk or Satin Slip Dress</h2>
<p>The slip dress is one of the most versatile pieces in my wardrobe. Layer it over a turtleneck in winter, wear it alone in summer, or belt it for a defined silhouette. The luxurious fabric makes even simple outfits look elevated.</p>

<h2>7. Wide-Leg Trousers</h2>
<p>Wide-leg trousers are having a major moment, and for good reason — they\'re comfortable, sophisticated, and endlessly stylish. I love them in neutral tones like ivory, stone, or charcoal.</p>

<h2>8. Classic Striped Top</h2>
<p>A Breton stripe top is a timeless piece that never goes out of style. It\'s casual, effortless, and pairs beautifully with everything from tailored trousers to denim shorts.</p>

<h2>9. Quality Leather Belt</h2>
<p>A simple leather belt in tan or black can transform an outfit. Use it to define your waist on oversized pieces or to add a finishing touch to trousers and jeans.</p>

<h2>10. Comfortable Ankle Boots</h2>
<p>A pair of ankle boots in a neutral colour bridges the gap between casual and dressy. I wear mine with everything — dresses, jeans, skirts, and even wide-leg trousers.</p>

<h2>My Shopping Philosophy</h2>
<p>When building a capsule wardrobe, I always ask: <em>Will I wear this at least 30 times?</em> If the answer is yes, it earns its place. Quality over quantity, always. Invest in pieces that are well-made, and they\'ll reward you for years.</p>

<p>What are your wardrobe essentials? Drop them in the comments — I\'d love to know!</p>',
                'status'     => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title'   => 'My Honest Review: 7 Days in Bali on a Budget — What Nobody Tells You',
                'summary' => 'I spent 7 days in Bali with a ₹60,000 budget. Here\'s the unfiltered truth about what it\'s really like — the good, the overrated, and the hidden gems.',
                'content' => '<p>Bali has been on every travel influencer\'s feed for years. After finally making the trip, I\'m here to give you the honest, unfiltered review — not just the perfectly curated Instagram moments.</p>

<h2>Day 1-2: Ubud — The Spiritual Heart</h2>
<p>I started in Ubud, and it immediately felt different from what I expected. Yes, the rice terraces at Tegallalang are stunning, but arrive before 7 AM to avoid the crowds and the entrance fee hawkers. The Sacred Monkey Forest is genuinely magical — just keep your sunglasses on your face, not your head (learned that the hard way!).</p>

<p>Where I stayed: A beautiful bamboo villa for ₹2,800/night. Breakfast included, pool access, and the most incredible jungle views. Booking.com had better rates than Airbnb for this one.</p>

<h2>Day 3: Waterfalls & Temples</h2>
<p>Tegenungan Waterfall was worth every rupiah of the entrance fee. The Tirta Empul temple, where locals perform purification rituals, was one of the most moving experiences of the trip. Dress respectfully — a sarong is required and available to rent at the entrance.</p>

<h2>Day 4-5: Seminyak — The Trendy Beach Town</h2>
<p>Seminyak is where the Instagram content practically creates itself. The beach clubs are gorgeous but expensive — Potato Head and Ku De Ta are worth one sunset visit each. For daily beach time, I found quieter spots just a 10-minute walk north.</p>

<p>Food highlight: Nasi goreng from a warung (local eatery) for ₹150 vs the same dish at a beach club for ₹1,200. The warung version was better. Always.</p>

<h2>Day 6: Canggu — For the Digital Nomads</h2>
<p>Canggu has a completely different energy — think specialty coffee shops, surf culture, and co-working spaces. I loved the vibe, though it felt more like a trendy neighbourhood in any global city than distinctly Balinese.</p>

<h2>Day 7: Tanah Lot & Departure</h2>
<p>The Tanah Lot sea temple at sunset is genuinely one of the most beautiful things I\'ve ever seen. Go on a clear day, and bring a wide-angle lens if you have one.</p>

<h2>Budget Breakdown (7 days, solo)</h2>
<ul>
<li>Accommodation: ₹19,600</li>
<li>Flights (Mumbai-Bali return): ₹18,000</li>
<li>Food & drinks: ₹8,000</li>
<li>Transport (scooter rental + taxis): ₹4,500</li>
<li>Activities & entrance fees: ₹5,200</li>
<li>Shopping & souvenirs: ₹4,700</li>
<li><strong>Total: ₹60,000</strong></li>
</ul>

<h2>What Nobody Tells You</h2>
<p>The traffic in Seminyak and Canggu is genuinely terrible. Rent a scooter only if you\'re comfortable riding in chaotic conditions. The humidity is intense — pack light, breathable fabrics. And the "Instagram spots" are often crowded and require patience (or a 5 AM alarm).</p>

<p>Would I go back? Absolutely. Bali has a magic that\'s hard to explain — it\'s in the incense, the offerings on every doorstep, the warmth of the people. Just go with realistic expectations and an open heart.</p>',
                'status'     => 'published',
                'published_at' => now()->subDays(12),
            ],
        ];

        foreach ($posts as $post) {
            DB::table('blog_posts')->insert([
                'user_id'      => $userId,
                'category_id'  => null,
                'title'        => $post['title'],
                'slug'         => Str::slug($post['title']) . '-' . Str::random(4),
                'summary'      => $post['summary'],
                'content'      => $post['content'],
                'status'       => $post['status'],
                'published_at' => $post['published_at'],
                'views_count'  => rand(120, 800),
                'created_at'   => $post['published_at'],
                'updated_at'   => $post['published_at'],
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ARJUN — BLOG POSTS (Advocate / Lawyer)
    // ─────────────────────────────────────────────────────────────────────────

    private function seedArjunBlogPosts(int $userId): void
    {
        $posts = [
            [
                'title'   => 'Understanding Your Rights as a Consumer: A Practical Guide to the Consumer Protection Act 2019',
                'summary' => 'The Consumer Protection Act 2019 brought sweeping changes to how consumer grievances are addressed in India. Here\'s what every citizen needs to know.',
                'content' => '<p>The Consumer Protection Act 2019 replaced the three-decade-old 1986 Act and introduced significant reforms that strengthen consumer rights in the digital age. As an advocate who regularly handles consumer disputes, I want to break down the key provisions in plain language.</p>

<h2>Who is a Consumer?</h2>
<p>Under the 2019 Act, a consumer is any person who buys goods or avails services for personal use — not for commercial resale. Importantly, the Act now explicitly covers <strong>e-commerce transactions</strong>, which was a major gap in the earlier legislation.</p>

<h2>Key Rights Under the Act</h2>
<p>The Act guarantees six fundamental consumer rights:</p>
<ol>
<li><strong>Right to Safety:</strong> Protection against goods and services that are hazardous to life and property.</li>
<li><strong>Right to Information:</strong> The right to be informed about the quality, quantity, potency, purity, standard, and price of goods and services.</li>
<li><strong>Right to Choose:</strong> Access to a variety of goods and services at competitive prices.</li>
<li><strong>Right to be Heard:</strong> Assurance that consumer interests will receive due consideration.</li>
<li><strong>Right to Redressal:</strong> The right to seek redressal against unfair trade practices or exploitation.</li>
<li><strong>Right to Consumer Education:</strong> The right to acquire knowledge and skills to be an informed consumer.</li>
</ol>

<h2>The Three-Tier Redressal System</h2>
<p>Consumer disputes are resolved through a three-tier quasi-judicial system:</p>
<ul>
<li><strong>District Consumer Disputes Redressal Commission:</strong> For claims up to ₹1 crore.</li>
<li><strong>State Consumer Disputes Redressal Commission:</strong> For claims between ₹1 crore and ₹10 crore.</li>
<li><strong>National Consumer Disputes Redressal Commission (NCDRC):</strong> For claims exceeding ₹10 crore.</li>
</ul>

<h2>E-Commerce and the 2019 Act</h2>
<p>One of the most significant additions is the regulation of e-commerce platforms. Online marketplaces must now:</p>
<ul>
<li>Display seller information and product details clearly.</li>
<li>Not engage in misleading advertisements.</li>
<li>Establish a grievance redressal mechanism.</li>
<li>Not manipulate prices or engage in unfair trade practices.</li>
</ul>

<h2>Product Liability</h2>
<p>The 2019 Act introduced a dedicated chapter on product liability. A manufacturer, service provider, or seller can now be held liable for harm caused by a defective product or deficient service. This is a significant step toward holding corporations accountable.</p>

<h2>How to File a Complaint</h2>
<p>You can now file complaints online through the <a href="https://consumerhelpline.gov.in" target="_blank">National Consumer Helpline</a> or the e-Daakhil portal. The process has been significantly simplified, and you do not necessarily need a lawyer for smaller claims.</p>

<h2>When to Consult a Lawyer</h2>
<p>While the system is designed to be consumer-friendly, complex cases involving large amounts, product liability, or corporate defendants benefit greatly from legal representation. If you believe your rights have been violated, I encourage you to seek legal advice before filing.</p>

<p>Have questions about a specific consumer dispute? Feel free to reach out through the contact form on this page.</p>',
                'status'     => 'published',
                'published_at' => now()->subDays(7),
            ],
            [
                'title'   => 'Startup Legal Checklist: 10 Things Every Founder Must Do Before Launching',
                'summary' => 'Most startups focus on product and funding — and forget the legal foundation. Here are 10 critical legal steps every founder must complete before going live.',
                'content' => '<p>In my years of advising startups and SMEs, I\'ve seen promising businesses derailed by legal oversights that could have been avoided with basic due diligence. This checklist is designed to help founders build on a solid legal foundation.</p>

<h2>1. Choose the Right Business Structure</h2>
<p>The most fundamental decision is your business structure. Each has different implications for liability, taxation, and fundraising:</p>
<ul>
<li><strong>Sole Proprietorship:</strong> Simplest, but unlimited personal liability.</li>
<li><strong>Partnership Firm:</strong> Suitable for 2-20 partners; governed by the Partnership Act 1932.</li>
<li><strong>Limited Liability Partnership (LLP):</strong> Combines partnership flexibility with limited liability.</li>
<li><strong>Private Limited Company:</strong> Preferred for startups seeking investment; enables equity dilution and has limited liability.</li>
</ul>
<p>For most tech startups seeking VC funding, a Private Limited Company is the recommended structure.</p>

<h2>2. Register Your Business</h2>
<p>Depending on your chosen structure, register with the appropriate authority — MCA (Ministry of Corporate Affairs) for companies and LLPs, or the Registrar of Firms for partnerships. Obtain your Certificate of Incorporation or Registration Certificate.</p>

<h2>3. Protect Your Intellectual Property</h2>
<p>Your brand name, logo, software, and unique processes are valuable assets. Take these steps early:</p>
<ul>
<li>Trademark your brand name and logo (apply through the IP India portal).</li>
<li>File provisional patents for any novel inventions.</li>
<li>Register copyrights for original creative works.</li>
<li>Use NDAs with employees, contractors, and potential partners.</li>
</ul>

<h2>4. Draft Founders\' Agreements</h2>
<p>A founders\' agreement is arguably the most important document for a multi-founder startup. It should clearly define equity splits, vesting schedules, roles and responsibilities, decision-making authority, and exit provisions. Disputes between founders are one of the leading causes of startup failure — a well-drafted agreement prevents most of them.</p>

<h2>5. Obtain Necessary Licences and Registrations</h2>
<p>Depending on your business, you may need: GST registration (mandatory above ₹20 lakh turnover), FSSAI licence (food businesses), RBI approval (fintech/NBFC), DPIIT recognition (for startup benefits), and sector-specific licences.</p>

<h2>6. Draft Employee Agreements</h2>
<p>Every employee and contractor should sign a comprehensive agreement covering: job description, compensation, confidentiality, IP assignment (all work created belongs to the company), non-solicitation, and termination conditions.</p>

<h2>7. Set Up Proper Accounting and Compliance</h2>
<p>Maintain proper books of accounts from day one. File GST returns, TDS returns, and annual company filings on time. Non-compliance attracts heavy penalties and can disqualify you from government schemes and investor due diligence.</p>

<h2>8. Create Your Terms of Service and Privacy Policy</h2>
<p>If you have a website or app, you legally need a Privacy Policy (especially if you collect user data) and Terms of Service. These documents protect you from liability and are required under the IT Act 2000 and the upcoming Digital Personal Data Protection Act.</p>

<h2>9. Open a Dedicated Business Bank Account</h2>
<p>Never mix personal and business finances. A dedicated business account is not just good practice — it\'s legally required for companies and LLPs, and essential for clean financial records during due diligence.</p>

<h2>10. Consult a Lawyer Before Signing Any Major Contract</h2>
<p>Before signing investor term sheets, vendor agreements, partnership contracts, or office leases, have a qualified lawyer review them. The cost of legal review is always less than the cost of a bad contract.</p>

<h2>Final Thoughts</h2>
<p>Building a startup is exciting, and legal matters can feel like a distraction from the "real work." But the founders who invest in a solid legal foundation early are the ones who can scale without fear. If you need guidance on any of these steps, I\'m here to help.</p>',
                'status'     => 'published',
                'published_at' => now()->subDays(14),
            ],
        ];

        foreach ($posts as $post) {
            DB::table('blog_posts')->insert([
                'user_id'      => $userId,
                'category_id'  => null,
                'title'        => $post['title'],
                'slug'         => Str::slug($post['title']) . '-' . Str::random(4),
                'summary'      => $post['summary'],
                'content'      => $post['content'],
                'status'       => $post['status'],
                'published_at' => $post['published_at'],
                'views_count'  => rand(80, 450),
                'created_at'   => $post['published_at'],
                'updated_at'   => $post['published_at'],
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIYA — JOBS (Influencer / Content Creator)
    // ─────────────────────────────────────────────────────────────────────────

    private function seedPriyaJobs(int $userId): void
    {
        DB::table('jobs')->insert([
            'user_id'      => $userId,
            'title'        => 'Brand Collaboration Manager',
            'slug'         => 'brand-collaboration-manager-' . time(),
            'description'  => 'I am looking for a dedicated Brand Collaboration Manager to handle inbound brand partnership requests, negotiate contracts, manage deliverables, and ensure timely content delivery. You will be the bridge between my creative team and brand partners, ensuring every collaboration is authentic, on-brand, and mutually beneficial.',
            'requirements' => "• 2+ years experience in influencer marketing, PR, or brand partnerships\n• Strong negotiation and communication skills\n• Understanding of Instagram, YouTube, and short-form video platforms\n• Experience with media kits, rate cards, and campaign reporting\n• Ability to manage multiple campaigns simultaneously\n• Passion for lifestyle, fashion, and beauty content",
            'location'     => 'Mumbai, Maharashtra (Hybrid)',
            'type'         => 'full-time',
            'salary_range' => '₹40,000 – ₹65,000/month',
            'status'       => 'active',
            'expires_at'   => now()->addDays(30),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ARJUN — JOBS (Advocate / Lawyer)
    // ─────────────────────────────────────────────────────────────────────────

    private function seedArjunJobs(int $userId): void
    {
        DB::table('jobs')->insert([
            'user_id'      => $userId,
            'title'        => 'Junior Advocate — Corporate & Commercial Law',
            'slug'         => 'junior-advocate-corporate-law-' . time(),
            'description'  => 'My chambers is seeking a motivated Junior Advocate to assist with corporate law matters, contract drafting, legal research, and client advisory work. This is an excellent opportunity for a recently enrolled advocate to gain hands-on experience across a wide range of commercial and civil matters under the guidance of a senior advocate with 15+ years of experience.',
            'requirements' => "• Enrolled with the Bar Council of Tamil Nadu\n• LLB or LLM from a recognised institution\n• Strong research and drafting skills\n• Knowledge of corporate law, contract law, and civil procedure\n• Proficiency in legal research tools (SCC Online, Manupatra)\n• Excellent written and verbal communication in English and Tamil\n• Ability to handle court appearances independently",
            'location'     => 'Chennai, Tamil Nadu',
            'type'         => 'full-time',
            'salary_range' => '₹25,000 – ₹45,000/month',
            'status'       => 'active',
            'expires_at'   => now()->addDays(45),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIYA — CRM LEADS
    // ─────────────────────────────────────────────────────────────────────────

    private function seedPriyaLeads(int $userId): void
    {
        $leads = [
            ['name' => 'Nykaa Beauty', 'email' => 'partnerships@nykaa.com', 'mobile' => '+91 22 4019 4019', 'source' => 'email', 'status' => 'qualified', 'priority' => 'high', 'summary' => 'Interested in a 3-month Instagram + Reels collaboration for their new skincare line. Budget: ₹2.5L/month.'],
            ['name' => 'Zara India', 'email' => 'influencer@zara.in', 'mobile' => '+91 80 4567 8901', 'source' => 'instagram_dm', 'status' => 'proposal_sent', 'priority' => 'high', 'summary' => 'Looking for a festive season campaign — 4 posts + 8 stories. Negotiating rates.'],
            ['name' => 'Mia Jewellery', 'email' => 'collab@mia.com', 'mobile' => '+91 98765 11111', 'source' => 'chatbot', 'status' => 'new', 'priority' => 'medium', 'summary' => 'Reached out for a jewellery unboxing reel. Small brand, but good fit for my aesthetic.'],
        ];

        foreach ($leads as $lead) {
            DB::table('crm_leads')->insert(array_merge($lead, [
                'user_id'    => $userId,
                'created_at' => now()->subDays(rand(1, 20)),
                'updated_at' => now(),
            ]));
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ARJUN — CRM LEADS
    // ─────────────────────────────────────────────────────────────────────────

    private function seedArjunLeads(int $userId): void
    {
        $leads = [
            ['name' => 'Ravi Krishnan', 'email' => 'ravi.k@techstartup.in', 'mobile' => '+91 98765 55555', 'source' => 'referral', 'status' => 'qualified', 'priority' => 'high', 'summary' => 'Startup founder needing founders agreement, IP assignment, and employment contracts. Budget: ₹50,000.'],
            ['name' => 'Meena Exports Pvt Ltd', 'email' => 'legal@meenaexports.com', 'mobile' => '+91 44 2345 6789', 'source' => 'website', 'status' => 'proposal_sent', 'priority' => 'high', 'summary' => 'Export company facing a contractual dispute with a foreign buyer. Seeking representation in NCDRC.'],
            ['name' => 'Suresh Babu', 'email' => 'suresh.b@gmail.com', 'mobile' => '+91 94444 22222', 'source' => 'chatbot', 'status' => 'new', 'priority' => 'medium', 'summary' => 'Property dispute matter — boundary wall encroachment by neighbour. Needs civil suit filing.'],
        ];

        foreach ($leads as $lead) {
            DB::table('crm_leads')->insert(array_merge($lead, [
                'user_id'    => $userId,
                'created_at' => now()->subDays(rand(1, 15)),
                'updated_at' => now(),
            ]));
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIYA — CHATBOT TRAINING (Influencer AI Assistant)
    // ─────────────────────────────────────────────────────────────────────────

    private function seedPriyaChatbotTraining(int $userId): void
    {
        $training = [
            // Greetings
            ['category' => 'Greetings', 'question' => 'Hello / Hi / Hey', 'answer' => 'Hi there! 👋 Welcome to Priya\'s page! I\'m Priya\'s AI assistant. I can help you with brand collaboration enquiries, content creation questions, or just point you in the right direction. What can I help you with today?', 'sort_order' => 1],
            ['category' => 'Greetings', 'question' => 'Who are you?', 'answer' => 'I\'m Priya\'s AI assistant! Priya Sharma is a lifestyle influencer and content creator based in Mumbai, known for her authentic take on fashion, beauty, travel, and everyday living. I\'m here to help with collaboration enquiries and general questions.', 'sort_order' => 2],

            // About Priya
            ['category' => 'About', 'question' => 'Who is Priya?', 'answer' => 'Priya Sharma is a Mumbai-based lifestyle influencer and content creator with a highly engaged following across Instagram, YouTube, and Pinterest. She creates authentic content around fashion, beauty, travel, home décor, and sustainable living. Her audience is primarily women aged 18-35.', 'sort_order' => 3],
            ['category' => 'About', 'question' => 'What platforms is Priya on?', 'answer' => 'Priya is active on Instagram (primary), YouTube (long-form content and vlogs), Pinterest (mood boards and style guides), and her personal blog at xenoraa.com/priya. She posts consistently and maintains high engagement rates across all platforms.', 'sort_order' => 4],
            ['category' => 'About', 'question' => 'What is Priya\'s niche?', 'answer' => 'Priya\'s content focuses on lifestyle, fashion, beauty, travel, and sustainable living. She is known for her minimalist aesthetic, honest product reviews, and relatable storytelling. Her audience trusts her recommendations because she only works with brands she genuinely believes in.', 'sort_order' => 5],

            // Collaborations
            ['category' => 'Collaborations', 'question' => 'How can I collaborate with Priya?', 'answer' => 'We\'d love to hear from you! Please fill out the collaboration enquiry form on this page, or email us at priya@xenoraa.com with your brand details, campaign brief, and timeline. Priya\'s team reviews all enquiries within 48 hours.', 'sort_order' => 6],
            ['category' => 'Collaborations', 'question' => 'What types of collaborations does Priya do?', 'answer' => 'Priya works on a variety of collaborations including: sponsored Instagram posts and Reels, YouTube integrations and dedicated videos, Instagram Stories campaigns, product unboxings and reviews, brand ambassador programmes, event appearances, and co-created content. Each collaboration is tailored to fit naturally with her content style.', 'sort_order' => 7],
            ['category' => 'Collaborations', 'question' => 'What is Priya\'s rate card?', 'answer' => 'Priya\'s rates vary based on deliverables, campaign duration, exclusivity, and usage rights. Please request her media kit by emailing priya@xenoraa.com or filling out the enquiry form. Her team will send a customised proposal based on your campaign needs.', 'sort_order' => 8],
            ['category' => 'Collaborations', 'question' => 'Does Priya do gifted collaborations?', 'answer' => 'Priya is selective about gifted collaborations and considers them on a case-by-case basis. She prioritises paid partnerships to ensure the quality and time invested in each collaboration. Please reach out with your product details and she\'ll let you know if it\'s a fit.', 'sort_order' => 9],

            // Content
            ['category' => 'Content', 'question' => 'How often does Priya post?', 'answer' => 'Priya posts consistently — typically 4-5 times per week on Instagram (including Reels and Stories), 1-2 YouTube videos per month, and weekly blog posts. Her content calendar is planned 2-4 weeks in advance.', 'sort_order' => 10],
            ['category' => 'Content', 'question' => 'Can I see Priya\'s media kit?', 'answer' => 'Absolutely! Please email priya@xenoraa.com to request the latest media kit. It includes audience demographics, engagement rates, platform statistics, past brand collaborations, and content samples.', 'sort_order' => 11],

            // Contact
            ['category' => 'Contact', 'question' => 'How do I contact Priya directly?', 'answer' => 'For business enquiries, please email priya@xenoraa.com or use the contact form on this page. For general questions, you can also DM on Instagram @priyasharma. Priya\'s team responds to all business enquiries within 48 hours.', 'sort_order' => 12],
        ];

        foreach ($training as $item) {
            DB::table('chatbot_training')->insert([
                'user_id'    => $userId,
                'category'   => $item['category'],
                'question'   => $item['question'],
                'answer'     => $item['answer'],
                'is_active'  => true,
                'sort_order' => $item['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ARJUN — CHATBOT TRAINING (Legal AI Assistant)
    // ─────────────────────────────────────────────────────────────────────────

    private function seedArjunChatbotTraining(int $userId): void
    {
        $training = [
            // Greetings
            ['category' => 'Greetings', 'question' => 'Hello / Hi / Hey', 'answer' => 'Hello! Welcome to Arjun Mehta\'s legal practice. I\'m Arjun\'s AI assistant. I can help you understand our legal services, schedule a consultation, or answer general questions about your legal matter. How can I assist you today?', 'sort_order' => 1],
            ['category' => 'Greetings', 'question' => 'Who are you?', 'answer' => 'I\'m the AI assistant for Arjun Mehta, Senior Advocate. I can provide general information about our legal services and help you take the first step toward resolving your legal matter. Please note that I provide general information only — for specific legal advice, please schedule a consultation with Arjun directly.', 'sort_order' => 2],

            // About Arjun
            ['category' => 'About', 'question' => 'Who is Arjun Mehta?', 'answer' => 'Arjun Mehta is a Senior Advocate based in Chennai, Tamil Nadu, with over 15 years of experience in corporate law, civil litigation, and intellectual property. He is enrolled with the Bar Council of Tamil Nadu and has represented clients before the Madras High Court, NCDRC, and various civil courts across Tamil Nadu.', 'sort_order' => 3],
            ['category' => 'About', 'question' => 'What areas of law does Arjun practise?', 'answer' => 'Arjun Mehta specialises in: Corporate & Commercial Law (company formation, contracts, M&A), Civil Litigation (property disputes, recovery suits, injunctions), Intellectual Property (trademarks, copyrights, patents), Consumer Protection (NCDRC/State Commission matters), Startup Legal Advisory (founders agreements, IP assignment, compliance), and Employment Law (employment contracts, termination disputes).', 'sort_order' => 4],

            // Services
            ['category' => 'Services', 'question' => 'I need help with a contract dispute', 'answer' => 'Contract disputes are one of our core practice areas. Whether you\'re dealing with a breach of contract, ambiguous terms, or non-performance by the other party, Arjun can advise you on your options — from negotiation and mediation to filing a civil suit. Please schedule a consultation to discuss the specifics of your matter.', 'sort_order' => 5],
            ['category' => 'Services', 'question' => 'I need help with a property dispute', 'answer' => 'Property disputes — including boundary encroachments, title disputes, landlord-tenant issues, and partition matters — are handled by our practice. Arjun has extensive experience in civil courts across Tamil Nadu. Please share the basic details of your matter through the contact form and we\'ll get back to you within 24 hours.', 'sort_order' => 6],
            ['category' => 'Services', 'question' => 'I need legal help for my startup', 'answer' => 'Congratulations on your startup! We provide comprehensive startup legal services including: choosing the right business structure, company/LLP registration, founders\' agreements, IP protection (trademarks, patents), employment contracts, terms of service and privacy policies, and ongoing compliance. Book a startup legal consultation to get started.', 'sort_order' => 7],
            ['category' => 'Services', 'question' => 'I need to register a trademark', 'answer' => 'Trademark registration is a critical step in protecting your brand. The process involves a trademark search, filing the application with the IP India office, responding to examination reports, and publication in the Trademark Journal. The process typically takes 18-24 months for registration. We handle the entire process on your behalf. Please contact us to get started.', 'sort_order' => 8],

            // Consultation
            ['category' => 'Consultation', 'question' => 'How do I book a consultation?', 'answer' => 'You can book a consultation by: (1) Filling out the contact form on this page, (2) Emailing arjun@xenoraa.com with a brief description of your matter, or (3) Calling +91 98765 12345 during office hours (Mon-Sat, 10 AM - 6 PM). Arjun offers both in-person consultations at his Chennai chambers and video consultations for clients outside Chennai.', 'sort_order' => 9],
            ['category' => 'Consultation', 'question' => 'What are your consultation fees?', 'answer' => 'Consultation fees vary depending on the complexity of the matter. An initial consultation is typically ₹2,000 for a 30-minute session. For ongoing matters, Arjun works on a retainer basis or charges per appearance/deliverable. Please contact us for a fee estimate specific to your matter.', 'sort_order' => 10],

            // Disclaimer
            ['category' => 'Disclaimer', 'question' => 'Is this legal advice?', 'answer' => 'Please note that the information provided by this AI assistant is for general informational purposes only and does not constitute legal advice. Every legal matter is unique and requires personalised advice from a qualified advocate. For advice specific to your situation, please schedule a consultation with Arjun Mehta directly.', 'sort_order' => 11],
        ];

        foreach ($training as $item) {
            DB::table('chatbot_training')->insert([
                'user_id'    => $userId,
                'category'   => $item['category'],
                'question'   => $item['question'],
                'answer'     => $item['answer'],
                'is_active'  => true,
                'sort_order' => $item['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
