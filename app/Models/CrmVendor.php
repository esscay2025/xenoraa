<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmVendor extends Model
{
    protected $table = 'crm_vendors';
    protected $fillable = ['user_id', 'name', 'phone', 'email', 'website', 'category', 'address', 'description', 'status'];

    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }
    public function purchaseOrders() { return $this->hasMany(CrmPurchaseOrder::class, 'vendor_id'); }
}
