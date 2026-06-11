<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmPoAttachment extends Model
{
    protected $table = 'crm_po_attachments';
    protected $fillable = [
        'user_id', 'purchase_order_id', 'original_name', 'stored_name', 'mime_type', 'file_size',
    ];

    public function purchaseOrder() {
        return $this->belongsTo(CrmPurchaseOrder::class, 'purchase_order_id');
    }

    public function getHumanSizeAttribute(): string {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
