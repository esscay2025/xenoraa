<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmInvoiceAttachment extends Model
{
    protected $table = 'crm_invoice_attachments';
    protected $fillable = ['invoice_id','user_id','original_name','stored_name','mime_type','file_size'];

    public function invoice() { return $this->belongsTo(CrmInvoice::class, 'invoice_id'); }

    public function getHumanSizeAttribute(): string {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
