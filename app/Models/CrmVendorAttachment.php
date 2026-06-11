<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmVendorAttachment extends Model
{
    protected $table = 'crm_vendor_attachments';
    protected $fillable = ['vendor_id','user_id','original_name','stored_name','mime_type','file_size'];

    public function vendor() { return $this->belongsTo(CrmVendor::class, 'vendor_id'); }

    public function getHumanSizeAttribute(): string {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
