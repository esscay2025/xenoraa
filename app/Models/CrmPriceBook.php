<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CrmPriceBook extends Model
{
    protected $table = 'crm_price_books';
    protected $fillable = ['user_id', 'name', 'description', 'pricing_percentage', 'pricing_model', 'currency', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'pricing_percentage' => 'decimal:2'];
    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }
    public function products() { return $this->belongsToMany(CrmProduct::class, 'price_book_products', 'price_book_id', 'product_id')->withPivot('list_price', 'unit_price', 'discount_percentage')->withTimestamps(); }
    public function notes() { return $this->hasMany(CrmNote::class, 'notable_id')->where('notable_type', 'price_book'); }
    public function attachments() { return $this->hasMany(CrmPriceBookAttachment::class, 'price_book_id')->latest(); }
}
