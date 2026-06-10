<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmContact extends Model
{
    protected $fillable = [
        'user_id', 'owner_id', 'account_id', 'vendor_id', 'reporting_to',
        'contact_image', 'first_name', 'last_name', 'title', 'job_title', 'department',
        'email', 'secondary_email', 'phone', 'mobile', 'other_phone', 'home_phone', 'fax', 'email_opt_out',
        'lead_source', 'assistant', 'assistant_phone',
        'date_of_birth', 'skype_id', 'twitter',
        'mailing_country', 'mailing_building', 'mailing_street', 'mailing_city', 'mailing_state', 'mailing_zip', 'mailing_lat', 'mailing_lng',
        'other_country', 'other_building', 'other_street', 'other_city', 'other_state', 'other_zip', 'other_lat', 'other_lng',
        'city', 'country', 'source', 'description', 'notes', 'attachments', 'status',
    ];

    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }
    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
    public function reportingTo() { return $this->belongsTo(User::class, 'reporting_to'); }
    public function account() { return $this->belongsTo(CrmAccount::class, 'account_id'); }
    public function leads() { return $this->hasMany(CrmLead::class, 'contact_id'); }
    public function deals() { return $this->hasMany(CrmDeal::class, 'contact_id'); }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
