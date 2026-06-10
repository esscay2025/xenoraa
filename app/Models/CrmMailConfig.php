<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class CrmMailConfig extends Model
{
    protected $table = 'crm_mail_configs';

    protected $fillable = [
        'user_id', 'mail_driver', 'mail_host', 'mail_port',
        'mail_username', 'mail_password', 'mail_encryption',
        'from_address', 'from_name', 'reply_to',
        'is_active', 'verified_at', 'last_error',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'verified_at' => 'datetime',
        'mail_port'   => 'integer',
    ];

    protected $hidden = ['mail_password'];

    // Encrypt password on set
    public function setMailPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['mail_password'] = Crypt::encryptString($value);
        }
    }

    // Decrypt password on get
    public function getMailPasswordAttribute($value)
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
