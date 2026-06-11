<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmPriceBookAttachment extends Model
{
    protected $table = 'crm_price_book_attachments';
    protected $fillable = ['price_book_id', 'user_id', 'original_name', 'stored_name', 'mime_type', 'file_size'];

    public function priceBook()
    {
        return $this->belongsTo(CrmPriceBook::class, 'price_book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
}
