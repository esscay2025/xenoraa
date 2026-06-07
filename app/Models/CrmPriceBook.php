<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmPriceBook extends Model
{
    protected $table = 'crm_price_books';
    protected $fillable = ['user_id', 'name', 'description', 'pricing_percentage', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'pricing_percentage' => 'decimal:2'];

    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }
}
