<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmSoAttachment extends Model
{
    protected $table = 'crm_so_attachments';
    protected $fillable = [
        'user_id', 'sales_order_id', 'original_name', 'stored_name', 'mime_type', 'file_size',
    ];

    public function salesOrder() {
        return $this->belongsTo(CrmSalesOrder::class, 'sales_order_id');
    }

    public function getHumanSizeAttribute(): string {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
