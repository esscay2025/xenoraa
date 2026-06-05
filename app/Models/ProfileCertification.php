<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'issuing_organization', 'issue_date',
        'expiry_date', 'credential_id', 'credential_url', 'sort_order',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
