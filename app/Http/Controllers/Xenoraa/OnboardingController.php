<?php

namespace App\Http\Controllers\Xenoraa;

use App\Http\Controllers\Controller;
use App\Services\AiTenantContentService;
use App\Services\TenantBootstrapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OnboardingController extends Controller
{
    // Profession → template mapping
    protected $templateMap = [
        'doctor'             => 'doctor',
        'advocate'           => 'advocate',
        'politician'         => 'politician',
        'consultant'         => 'consultant',
        'entrepreneur'       => 'entrepreneur',
        'influencer'         => 'influencer',
        'ecommerce'          => 'ecommerce',
        'business'           => 'business',
        'software_developer' => 'default',
        'designer'           => 'default',
        'educator'           => 'default',
        'freelancer'         => 'default',
        'other'              => 'default',
    ];

    /**
     * Welcome screen shown after payment success (step 1).
     */
    public function welcome()
    {
        $user = Auth::user();
        if ($user->onboarding_completed) {
            return redirect()->route('user.dashboard');
        }
        return view('xenoraa.onboarding.welcome', compact('user'));
    }

    /**
     * Business Info step (step 2) — collect business details or upload file.
     */
    public function businessInfo()
    {
        $user = Auth::user();
        if ($user->onboarding_completed) {
            return redirect()->route('user.dashboard');
        }
        return view('xenoraa.onboarding.business-info', compact('user'));
    }

    /**
     * Save business info and trigger AI content generation.
     */
    public function saveBusinessInfo(Request $request)
    {
        $request->validate([
            'business_info'      => 'nullable|string|max:5000',
            'business_info_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120', // 5MB max
        ]);

        $user = Auth::user();
        $businessText = '';

        // Handle file upload — extract text
        if ($request->hasFile('business_info_file')) {
            $file = $request->file('business_info_file');
            $ext  = strtolower($file->getClientOriginalExtension());

            try {
                if ($ext === 'pdf') {
                    // Use pdftotext (installed on production)
                    $tmpPath = $file->getPathname();
                    $businessText = shell_exec("pdftotext " . escapeshellarg($tmpPath) . " - 2>/dev/null") ?? '';
                } elseif (in_array($ext, ['doc', 'docx'])) {
                    // Use antiword/docx2txt if available, else read raw
                    $tmpPath = $file->getPathname();
                    $businessText = shell_exec("docx2txt " . escapeshellarg($tmpPath) . " - 2>/dev/null") ?? '';
                    if (empty(trim($businessText))) {
                        $businessText = file_get_contents($tmpPath);
                    }
                } elseif ($ext === 'txt') {
                    $businessText = file_get_contents($file->getPathname());
                }
            } catch (\Throwable $e) {
                Log::warning("OnboardingController: File text extraction failed: " . $e->getMessage());
            }
        }

        // Merge with manual text input
        if (!empty($request->business_info)) {
            $businessText = $request->business_info . "\n\n" . $businessText;
        }

        $businessText = trim($businessText);

        // Save raw business info to user record
        try {
            $user->update(['business_info' => substr($businessText, 0, 5000)]);
        } catch (\Throwable $e) {
            Log::warning("OnboardingController: Could not save business_info: " . $e->getMessage());
        }

        // Trigger AI content generation if we have text
        if (!empty($businessText)) {
            try {
                $aiService = new AiTenantContentService();
                $result = $aiService->generateAndApply($user, $businessText);
                if (!$result['success']) {
                    Log::warning("OnboardingController: AI generation failed for user {$user->id}: " . ($result['error'] ?? 'unknown'));
                }
            } catch (\Throwable $e) {
                Log::warning("OnboardingController: AI service exception for user {$user->id}: " . $e->getMessage());
            }
        }

        return redirect()->route('onboarding.profile');
    }

    /**
     * Profile setup step (step 3) — profession-aware form.
     */
    public function profile()
    {
        $user = Auth::user();
        if ($user->onboarding_completed) {
            return redirect()->route('user.dashboard');
        }
        return view('xenoraa.onboarding.profile', compact('user'));
    }

    /**
     * Save profile setup and apply profession template.
     */
    public function saveProfile(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|max:100',
            'tagline'    => 'nullable|string|max:200',
            'bio'        => 'nullable|string|max:1000',
            'phone'      => 'nullable|string|max:20',
            'city'       => 'nullable|string|max:100',
            'profession' => 'nullable|string|max:50',
        ]);

        $user       = Auth::user();
        $profession = $request->profession ?? $user->profession ?? 'other';
        $template   = $this->templateMap[$profession] ?? 'default';

        // Update user record
        $user->update([
            'profession'       => $profession,
            'profile_template' => $template,
        ]);

        // Update site settings
        try {
            \App\Models\SiteSetting::where('user_id', $user->id)
                ->update([
                    'site_name'   => $request->site_title,
                    'tagline'     => $request->tagline,
                    'description' => $request->bio,
                    'phone'       => $request->phone,
                ]);
        } catch (\Exception $e) {
            // Continue silently if site settings update fails
        }

        return redirect()->route('onboarding.complete');
    }

    /**
     * Completion step (step 4) — marks onboarding done.
     */
    public function complete()
    {
        $user = Auth::user();

        // Mark onboarding as completed
        try {
            $user->update(['onboarding_completed' => true]);
        } catch (\Exception $e) {
            // Column may not exist yet — silently continue
        }

        return view('xenoraa.onboarding.complete', compact('user'));
    }

    /**
     * API: Check username availability (AJAX).
     */
    public function checkUsername(Request $request)
    {
        $username = strtolower(trim($request->get('username', '')));

        if (strlen($username) < 3) {
            return response()->json(['available' => false, 'reason' => 'too_short', 'message' => 'Username must be at least 3 characters.']);
        }

        if (!preg_match('/^[a-z0-9_\-]+$/', $username)) {
            return response()->json(['available' => false, 'reason' => 'invalid', 'message' => 'Only letters, numbers, hyphens, and underscores allowed.']);
        }

        $reserved = ['admin', 'staff', 'superadmin', 'api', 'auth', 'login', 'register',
                     'logout', 'dashboard', 'profile', 'chat', 'forum', 'calendar',
                     'shop', 'newsletter', 'chatbot', 'xenoraa', 'solutions', 'about',
                     'blog', 'jobs', 'support', 'help', 'www', 'mail', 'ftp', 'root',
                     'billing', 'payment', 'pricing', 'features', 'showcase', 'get-started'];

        if (in_array($username, $reserved)) {
            return response()->json(['available' => false, 'reason' => 'reserved', 'message' => 'This username is reserved.']);
        }

        $exists = \App\Models\User::where('username', $username)->exists();
        return response()->json([
            'available' => !$exists,
            'message'   => $exists ? 'Username is already taken.' : 'Username is available!',
        ]);
    }
}
