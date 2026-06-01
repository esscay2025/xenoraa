<?php

namespace Database\Seeders;

use App\Models\ForumTopic;
use App\Models\ForumReply;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', function ($q) {
            $q->select('id')->from('roles')->where('name', 'admin')->limit(1);
        })->first() ?? User::first();

        if (!$admin) {
            $this->command->warn('No users found. Skipping forum seeder.');
            return;
        }

        $topics = [
            [
                'title'    => 'How is AI actually changing the way you run your business in 2025?',
                'category' => 'ai-automation',
                'tags'     => 'AI, business, automation, productivity',
                'body'     => "I've been experimenting with AI tools across my business for the past year — from automating customer support with chatbots to using AI for content generation and data analysis. The results have been genuinely surprising.

What I didn't expect was how much time it would save on repetitive tasks. We cut our weekly reporting time by about 70% just by using AI to pull and summarize data from our CRM.

But I'm curious — what's working for others? Are you using AI for:
- Customer support automation?
- Content creation?
- Sales forecasting?
- Internal process automation?
- Something completely different?

Also, what tools are you actually using day-to-day? I've been testing GPT-4, Claude, and some niche tools like Zapier AI and Make.com automations.

Would love to hear real-world experiences — not just the hype.",
                'is_pinned' => true,
                'replies'  => [
                    "We've been using AI for lead scoring in our CRM. It went from a manual 2-hour process to something that runs automatically every morning. The accuracy is surprisingly good — better than our manual scoring was.",
                    "Content creation has been the biggest win for us. We use Claude to draft first versions of blog posts and social content, then our team edits and refines. We went from 2 posts/month to 8 posts/month without hiring anyone new.",
                    "Honest answer: AI hype is real but so are the limitations. We tried AI customer support and had to roll it back because it gave wrong answers about pricing. Now we use it only for FAQ-style queries with human escalation for anything complex.",
                ],
            ],
            [
                'title'    => 'What are the most underrated cybersecurity threats for small businesses in 2025?',
                'category' => 'tech-development',
                'tags'     => 'cybersecurity, hacking, small business, security',
                'body'     => "Most cybersecurity content focuses on enterprise-level threats, but small businesses are actually the most vulnerable — and often the least prepared.

I've been researching this area and the threats that worry me most for small businesses are NOT the obvious ones like ransomware. The underrated ones are:

1. **Business Email Compromise (BEC)** — Attackers impersonate your CEO or CFO via email and trick employees into transferring money. No malware needed.

2. **Supply chain attacks** — Your software vendors get hacked and the malware comes through legitimate software updates.

3. **SIM swapping** — Attackers convince your carrier to transfer your phone number, bypassing 2FA.

4. **AI-powered phishing** — Phishing emails are now personalized and grammatically perfect because attackers use AI to write them.

What threats are you most concerned about? And what practical steps have you taken to protect your business?

I'm especially interested in low-cost security measures that actually work for businesses without a dedicated IT team.",
                'is_pinned' => false,
                'replies'  => [
                    "BEC is massively underrated. We almost lost $45,000 to a BEC attack last year. The email looked exactly like our CFO's address — just one letter off. Now we have a verbal confirmation rule for any wire transfer over $5,000.",
                    "Password manager + hardware security keys (YubiKey) for all admin accounts. It's the single best investment we made. Costs about $50 per employee and eliminates a huge attack surface.",
                    "The AI phishing point is so real. I got a phishing email last month that referenced a real project I'm working on, used my name correctly, and had perfect grammar. Only thing that saved me was hovering over the link before clicking.",
                ],
            ],
            [
                'title'    => 'From idea to first paying customer — what was your biggest lesson?',
                'category' => 'startup-business',
                'tags'     => 'startup, product development, entrepreneurship, lessons',
                'body'     => "I've been building products for 8 years now, and I've made almost every mistake in the book. But if I had to pick the ONE lesson that would have saved me the most time and money, it's this:

**Talk to customers before you build anything.**

I spent 6 months building a SaaS product that I was convinced people needed. I had a beautiful UI, solid backend, and a feature list that I thought was perfect. Then I launched and... crickets. Not because the product was bad, but because I had built what I *thought* people wanted, not what they actually needed.

The second time around, I spent 3 weeks doing customer discovery interviews before writing a single line of code. I found out that the problem I was solving was real, but the solution I had in mind was completely wrong. The actual solution was 10x simpler and took 2 weeks to build.

What was YOUR biggest lesson going from idea to first paying customer?

I'm especially curious about:
- How did you validate before building?
- How did you find your first customers?
- What would you do differently?",
                'is_pinned' => true,
                'replies'  => [
                    "My biggest lesson: charge from day one. I gave my product away free for 6 months thinking I'd convert users later. Almost none converted. When I started charging $29/month from the start, I got fewer signups but much higher quality users who actually used the product.",
                    "Customer discovery is everything. I now do 20 customer interviews before I write any code. I ask: what's the hardest part of your day? What do you wish existed? What would you pay for? The answers always surprise me.",
                    "Distribution is harder than building. I had a great product but no idea how to reach customers. Now I think about distribution strategy before I even design the product. Where do my customers hang out? How will they find me?",
                ],
            ],
            [
                'title'    => 'Personal branding vs. company branding — which should founders focus on first?',
                'category' => 'career-branding',
                'tags'     => 'personal branding, founder, LinkedIn, marketing',
                'body'     => "This is a debate I keep having with other founders and I'd love to hear more perspectives.

The argument for **personal branding first**:
- People buy from people, not companies
- Your personal brand follows you even if the company fails
- It's easier to build trust as a person than as a brand
- LinkedIn and Twitter/X work better for individuals than companies

The argument for **company branding first**:
- Investors and enterprise clients want to see a professional company brand
- You can hire people who represent the company brand
- Personal brand creates dependency on the founder
- Easier to sell the company later if it has its own identity

My current take: For B2C and SMB-focused businesses, personal branding wins early. For enterprise and B2B, company branding matters more from day one.

But I'm genuinely unsure. What's your experience? And if you've built a strong personal brand, what platforms and tactics actually worked for you?",
                'is_pinned' => false,
                'replies'  => [
                    "Personal brand 100% for early-stage founders. My LinkedIn posts drive 60% of our inbound leads. The company page gets almost no organic reach. People want to know the person behind the product.",
                    "I'd say both, but in sequence. Build personal brand first to get initial traction and customers. Then invest in company brand once you have revenue and a team. The personal brand gets you to $1M ARR, the company brand gets you to $10M.",
                    "The key insight I learned: your personal brand IS your company brand at the early stage. They're the same thing. Separate them only when you have a team and the company has its own story to tell.",
                ],
            ],
            [
                'title'    => 'What tech stack would you choose if starting a SaaS product today in 2025?',
                'category' => 'tech-development',
                'tags'     => 'tech stack, SaaS, development, React, Laravel, Next.js',
                'body'     => "I'm planning to build a new SaaS product and I'm doing a fresh evaluation of tech stacks. The landscape has changed a lot in the past 2 years with AI-assisted coding, new frameworks, and the rise of serverless.

Here's what I'm considering:

**Option A: Laravel + Vue/React (traditional full-stack)**
- Proven, mature ecosystem
- Great for rapid prototyping
- Strong ORM and auth out of the box
- Easier to find developers

**Option B: Next.js + Supabase (modern serverless)**
- Excellent developer experience
- Built-in auth, database, storage
- Edge functions for global performance
- TypeScript-first

**Option C: Django + React (Python-first)**
- Great if you need ML/AI integration
- Strong admin panel out of the box
- Large ecosystem

**Option D: Go/Rust backend + React frontend**
- Maximum performance
- Lower hosting costs at scale
- Steeper learning curve

My use case: B2B SaaS, ~500 initial users, needs good API performance, some AI features, and I want to move fast.

What would you choose and why? I'm especially interested in hearing from people who've actually shipped products with these stacks.",
                'is_pinned' => false,
                'replies'  => [
                    "Laravel + Livewire or Inertia.js for B2B SaaS under 10k users. You can ship incredibly fast, the ecosystem is mature, and you don't need a separate frontend framework. I've shipped 3 SaaS products with this stack and it's never been the bottleneck.",
                    "Next.js + Supabase is my recommendation for 2025. The developer experience is excellent, Supabase handles auth/database/storage out of the box, and Vercel deployment is seamless. The main downside is vendor lock-in with Supabase.",
                    "Don't overthink the stack. The best stack is the one you know best. I've seen people waste months evaluating stacks instead of building. Pick something you're comfortable with and ship. You can always refactor later.",
                ],
            ],
        ];

        foreach ($topics as $topicData) {
            $replies = $topicData['replies'] ?? [];
            unset($topicData['replies']);

            $existing = ForumTopic::where('slug', Str::slug($topicData['title']))->first();
            if ($existing) {
                continue;
            }

            $topic = ForumTopic::create([
                'user_id'   => $admin->id,
                'title'     => $topicData['title'],
                'slug'      => ForumTopic::generateSlug($topicData['title']),
                'body'      => $topicData['body'],
                'category'  => $topicData['category'],
                'tags'      => $topicData['tags'],
                'is_pinned' => $topicData['is_pinned'] ?? false,
                'views'     => rand(45, 320),
            ]);

            foreach ($replies as $replyBody) {
                ForumReply::create([
                    'topic_id' => $topic->id,
                    'user_id'  => $admin->id,
                    'body'     => $replyBody,
                ]);
            }
        }

        $this->command->info('Forum seeder completed: 5 topics with replies created.');
    }
}
