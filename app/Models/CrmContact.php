<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmContact extends Model
{
    protected $fillable = [
        'user_id', 'account_id', 'first_name', 'last_name', 'email', 'phone',
        'job_title', 'department', 'city', 'country', 'source', 'notes', 'status',
    ];

    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }
    public function account() { return $this->belongsTo(CrmAccount::class, 'account_id'); }
    public function leads() { return $this->hasMany(CrmLead::class, 'contact_id'); }
    public function deals() { return $this->hasMany(CrmDeal::class, 'contact_id'); }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
