<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessCardController extends Controller
{
    private function tenantId()
    {
        return auth()->user()->getTenantId();
    }

    public function index()
    {
        $card = BusinessCard::where('user_id', $this->tenantId())->first();
        $qrSvg = null;
        if ($card) {
            // Simple QR code SVG placeholder (will use JS-based QR on frontend)
            $qrSvg = '<div style="width:150px;height:150px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;border-radius:8px;"><span style="font-size:12px;color:#666;">QR Code</span></div>';
        }
        return view('admin.business-card.index', compact('card', 'qrSvg'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'theme_color' => 'nullable|string|max:7',
            'photo' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:2048',
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
        ]);

        $data = [
            'user_id' => $this->tenantId(),
            'display_name' => $validated['display_name'],
            'designation' => $validated['designation'] ?? null,
            'company' => $validated['company'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'website' => $validated['website'] ?? null,
            'address' => $validated['address'] ?? null,
            'theme_color' => $validated['theme_color'] ?? '#6366f1',
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = '/storage/' . $request->file('photo')->store('business-cards', 'public');
        }
        if ($request->hasFile('logo')) {
            $data['logo'] = '/storage/' . $request->file('logo')->store('business-cards', 'public');
        }

        // Social links as JSON
        $data['social_links'] = json_encode(array_filter([
            'linkedin' => $request->linkedin,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'youtube' => $request->youtube,
            'github' => $request->github,
        ]));

        BusinessCard::updateOrCreate(
            ['user_id' => $this->tenantId()],
            $data
        );

        return back()->with('success', 'Business card saved successfully.');
    }

    public function vcard()
    {
        $card = BusinessCard::where('user_id', $this->tenantId())->firstOrFail();

        $vcard = "BEGIN:VCARD\r\n";
        $vcard .= "VERSION:3.0\r\n";
        $vcard .= "FN:" . $card->display_name . "\r\n";
        if ($card->company) $vcard .= "ORG:" . $card->company . "\r\n";
        if ($card->designation) $vcard .= "TITLE:" . $card->designation . "\r\n";
        if ($card->email) $vcard .= "EMAIL:" . $card->email . "\r\n";
        if ($card->phone) $vcard .= "TEL;TYPE=CELL:" . $card->phone . "\r\n";
        if ($card->website) $vcard .= "URL:" . $card->website . "\r\n";
        if ($card->address) $vcard .= "ADR;TYPE=WORK:;;" . str_replace("\n", ";", $card->address) . "\r\n";
        $vcard .= "END:VCARD\r\n";

        return response($vcard)
            ->header('Content-Type', 'text/vcard')
            ->header('Content-Disposition', 'attachment; filename="' . str_replace(' ', '_', $card->display_name) . '.vcf"');
    }
}
