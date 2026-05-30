<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class JobPostingsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'gopi@outlook.in')->first();
        if (!$admin) return;

        $jobs = [
            [
                'title'          => 'Project In-Charge / Site Coordinator',
                'slug'           => 'project-in-charge-site-coordinator-porur-chennai',
                'company'        => 'Go Esscay Solutions',
                'location'       => 'Porur, Chennai',
                'type'           => 'full-time',
                'salary_range'   => '₹18,000 – ₹30,000/month',
                'description'    => '<h2>About the Role</h2>
<p>We are looking for an experienced <strong>Project In-Charge / Site Coordinator</strong> to join our growing team at Go Esscay Solutions, Porur, Chennai. You will be responsible for overseeing day-to-day site operations, coordinating with vendors and teams, and ensuring projects are delivered on time and within budget.</p>

<h2>Qualifications</h2>
<ul>
  <li>Diploma / Engineering in Civil or Mechanical</li>
  <li>3 to 5 years of relevant experience in site coordination or project management</li>
</ul>

<h2>Skills Required</h2>
<ul>
  <li>Strong leadership and site coordination abilities</li>
  <li>Good communication skills in English</li>
  <li>Vendor and team management experience</li>
  <li>Ability to read and interpret engineering drawings</li>
  <li>Problem-solving mindset with attention to detail</li>
</ul>

<h2>Responsibilities</h2>
<ul>
  <li>Oversee and manage all on-site activities and personnel</li>
  <li>Coordinate with vendors, subcontractors, and internal teams</li>
  <li>Ensure compliance with safety standards and project specifications</li>
  <li>Monitor project progress and report to management</li>
  <li>Handle material procurement and inventory tracking at site</li>
</ul>

<h2>What We Offer</h2>
<ul>
  <li>Competitive salary: ₹18,000 – ₹30,000 per month</li>
  <li>Professional work environment with career growth opportunities</li>
  <li>Exposure to real-time fabrication and manufacturing projects</li>
</ul>',
                'requirements'   => "Diploma / Engineering (Civil or Mechanical)\n3-5 years experience\nStrong leadership & site coordination\nGood communication in English\nVendor & team management",
                'status'         => 'active',
            ],
            [
                'title'          => 'CNC Laser Machine & Folding Machine Operator',
                'slug'           => 'cnc-laser-folding-machine-operator-porur-chennai',
                'company'        => 'Go Esscay Solutions',
                'location'       => 'Porur, Chennai',
                'type'           => 'full-time',
                'salary_range'   => '₹20,000 – ₹30,000/month',
                'description'    => '<h2>About the Role</h2>
<p>We are hiring a skilled <strong>CNC Laser Machine & Folding Machine Operator</strong> to join our production team at Go Esscay Solutions. You will operate state-of-the-art CNC laser cutting and folding machines to produce precision fabricated components.</p>

<h2>Qualifications</h2>
<ul>
  <li>ITI / Diploma in Mechanical Engineering</li>
  <li>1 to 5 years of hands-on experience with CNC machines</li>
</ul>

<h2>Skills Required</h2>
<ul>
  <li>Hands-on experience in CNC Laser Cutting Machines</li>
  <li>Experience in CNC Folding / Press Brake Machines</li>
  <li>Ability to read and interpret engineering drawings</li>
  <li>Knowledge of precision measuring instruments (vernier calipers, micrometers)</li>
  <li>Understanding of material properties and cutting parameters</li>
</ul>

<h2>Job Responsibilities</h2>
<ul>
  <li>Operate CNC laser and folding machines efficiently and safely</li>
  <li>Ensure accuracy and quality in all fabrication work</li>
  <li>Follow production schedules and safety standards strictly</li>
  <li>Maintain machines and work area in proper condition</li>
  <li>Perform routine maintenance and report machine issues promptly</li>
  <li>Minimise material wastage through precise operations</li>
</ul>

<h2>Salary & Benefits</h2>
<p>₹20,000 – ₹30,000 per month based on experience. Professional work environment with growth opportunities.</p>',
                'requirements'   => "ITI / Diploma in Mechanical Engineering\n1-5 years experience\nCNC Laser Cutting Machine operation\nCNC Folding / Press Brake Machine operation\nEngineering drawing reading\nPrecision measuring instruments",
                'status'         => 'active',
            ],
            [
                'title'          => 'AutoCAD Sheet Metal Draftsman',
                'slug'           => 'autocad-sheet-metal-draftsman-porur-chennai',
                'company'        => 'Go Esscay Solutions',
                'location'       => 'Porur, Chennai',
                'type'           => 'full-time',
                'salary_range'   => '₹20,000 – ₹35,000/month',
                'description'    => '<h2>About the Role</h2>
<p>We are seeking a talented <strong>AutoCAD Sheet Metal Draftsman</strong> to join our design and production team. You will prepare detailed fabrication drawings for sheet metal components, working closely with the production team to ensure accurate manufacturing output.</p>

<h2>Qualifications</h2>
<ul>
  <li>Diploma or B.E. in Mechanical / Civil Engineering (preferred)</li>
  <li>2 to 5 years of relevant drafting experience</li>
</ul>

<h2>Skills Required</h2>
<ul>
  <li>Proficiency in AutoCAD for sheet metal laser cutting drawings</li>
  <li>Knowledge of sheet metal folding and bend calculations</li>
  <li>Ability to prepare Fabrication & Development Drawings</li>
  <li>Understanding of laser cutting and sheet metal manufacturing processes</li>
  <li>Basic knowledge of CNC Laser Machine requirements</li>
  <li>Experience in dimensioning and material optimisation (preferred)</li>
</ul>

<h2>Job Responsibilities</h2>
<ul>
  <li>Prepare accurate 2D sheet metal drawings for laser cutting and fabrication</li>
  <li>Create development drawings for complex sheet metal components</li>
  <li>Collaborate with production team to resolve drawing-related issues</li>
  <li>Optimise material usage in drawings to reduce wastage</li>
  <li>Maintain drawing archives and revision control</li>
</ul>

<h2>Why Join Us?</h2>
<ul>
  <li>Work on real-time fabrication and manufacturing projects</li>
  <li>Opportunity to enhance your design and production skills</li>
  <li>Professional work environment with career growth</li>
  <li>Salary: ₹20,000 – ₹35,000 per month based on experience</li>
</ul>',
                'requirements'   => "Diploma or B.E. (Mechanical/Civil preferred)\n2-5 years experience\nAutoCAD proficiency\nSheet metal drawing preparation\nBend calculations\nFabrication drawing knowledge",
                'status'         => 'active',
            ],
            [
                'title'          => 'CAD Drafting Engineer / Draftsperson',
                'slug'           => 'cad-drafting-engineer-draftsperson-porur-chennai',
                'company'        => 'Go Esscay Solutions',
                'location'       => 'Porur, Chennai',
                'type'           => 'full-time',
                'salary_range'   => '₹18,000 – ₹30,000/month',
                'description'    => '<h2>About the Role</h2>
<p>Go Esscay Solutions is hiring <strong>CAD Drafting Engineers / Draftspersons</strong> — both freshers and experienced candidates are welcome to apply. This is an excellent opportunity to kick-start or advance your career in fabrication and manufacturing design.</p>

<h2>Qualifications</h2>
<ul>
  <li><strong>Diploma Candidates:</strong> 2018 – 2024 passed out (Freshers and experienced both can apply)</li>
  <li><strong>B.E. Candidates</strong> (Mechanical / Civil preferred): Design experience is mandatory</li>
</ul>

<h2>Technical Skills Required</h2>
<ul>
  <li>Proficiency in AutoCAD (2D drafting)</li>
  <li>Ability to read and prepare 2D Fabrication drawings</li>
  <li>Knowledge of Sheet Metal / Structural Drawings (preferred)</li>
  <li>Basic understanding of manufacturing processes</li>
  <li>Attention to detail and accuracy in dimensioning</li>
</ul>

<h2>What You Will Do</h2>
<ul>
  <li>Prepare 2D fabrication and structural drawings using AutoCAD</li>
  <li>Assist senior engineers in complex drawing preparation</li>
  <li>Maintain drawing files and revision history</li>
  <li>Coordinate with the production floor to clarify drawing requirements</li>
  <li>Learn and grow within a professional manufacturing environment</li>
</ul>

<h2>Salary & Benefits</h2>
<p>₹18,000 – ₹30,000 per month based on experience. Freshers are encouraged to apply — we invest in training and development.</p>

<blockquote>Great projects start with great people! Interested candidates can contact us directly for interview details.</blockquote>',
                'requirements'   => "Diploma (2018-2024) or B.E. in Mechanical/Civil\nFreshers and experienced both welcome\nAutoCAD proficiency\n2D fabrication drawing preparation\nSheet metal/structural drawing knowledge preferred",
                'status'         => 'active',
            ],
        ];

        foreach ($jobs as $job) {
            // Remove fields not in the jobs table
            unset($job['company'], $job['experience']);
            Job::updateOrCreate(
                ['slug' => $job['slug']],
                array_merge($job, [
                    'user_id'    => $admin->id,
                    'expires_at' => Carbon::now()->addDays(rand(30, 90)),
                    'created_at' => Carbon::now()->subDays(rand(1, 14)),
                ])
            );
        }

        $this->command->info('✅ 4 job postings seeded successfully.');
    }
}
