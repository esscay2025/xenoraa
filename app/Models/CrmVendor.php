<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmVendor extends Model
{
    protected $table = 'crm_vendors';
    protected $fillable = [
        'user_id', 'owner_id', 'name', 'phone', 'fax', 'email',
        'gl_account', 'email_opt_out', 'website', 'category',
        'address', 'description', 'status',
        'bill_country', 'bill_building', 'bill_street', 'bill_city', 'bill_state', 'bill_zip',
    ];

    public function tenant()         { return $this->belongsTo(User::class, 'user_id'); }
    public function owner()          { return $this->belongsTo(User::class, 'owner_id'); }
    public function purchaseOrders() { return $this->hasMany(CrmPurchaseOrder::class, 'vendor_id'); }
}
