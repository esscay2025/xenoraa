<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmAccount extends Model
{
    protected $fillable = [
        'user_id', 'name', 'type', 'industry', 'website', 'phone', 'email',
        'address', 'city', 'country', 'annual_revenue', 'employees', 'notes', 'status',
    ];

    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }
    public function contacts() { return $this->hasMany(CrmContact::class, 'account_id'); }
    public function deals() { return $this->hasMany(CrmDeal::class, 'account_id'); }
    public function leads() { return $this->hasMany(CrmLead::class, 'account_id'); }
}
