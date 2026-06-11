<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EcomMailConfig extends Model
{
    protected $table = 'ecom_mail_configs';

    protected $fillable = [
        'user_id', 'mail_driver', 'mail_host', 'mail_port',
        'mail_username', 'mail_password', 'mail_encryption',
        'from_address', 'from_name', 'reply_to',
        'is_active', 'verified_at', 'last_error',
    ];

    protected $hidden = ['mail_password'];

    protected $casts = [
        'is_active'    => 'boolean',
        'verified_at'  => 'datetime',
        'mail_port'    => 'integer',
    ];

    // Encrypt password on set
    public function setMailPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['mail_password'] = Crypt::encryptString($value);
        }
    }

    // Decrypt password on get
    public function getMailPasswordAttribute($value): ?string
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
