<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmQuoteAttachment extends Model
{
    protected $table = 'crm_quote_attachments';
    protected $fillable = ['quote_id', 'user_id', 'original_name', 'stored_name', 'mime_type', 'file_size'];

    public function quote()
    {
        return $this->belongsTo(CrmQuote::class, 'quote_id');
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
}
