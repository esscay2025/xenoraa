<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmAccountEmail extends Model
{
    protected $table = 'crm_account_emails';

    protected $fillable = [
        'user_id', 'account_id', 'mail_template_id', 'status',
        'to_email', 'cc_email', 'bcc_email', 'subject', 'body_html',
        'from_name', 'from_email', 'scheduled_at', 'sent_at',
        'error_message', 'attachments',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at'      => 'datetime',
        'attachments'  => 'array',
    ];

    public function account()
    {
        return $this->belongsTo(CrmAccount::class, 'account_id');
    }

    public function template()
    {
        return $this->belongsTo(CrmMailTemplate::class, 'mail_template_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }
}
