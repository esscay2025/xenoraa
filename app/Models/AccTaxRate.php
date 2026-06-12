<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccTaxRate extends Model
{
    use HasFactory;
    protected $table = 'acc_tax_rates';
    protected $fillable = [
        'user_id','tenant_owner_id','name','rate','tax_type','is_default','is_active',
    ];
    protected $casts = [
        'rate'       => 'decimal:2',
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];
}
