<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CrmAccount extends Model
{
    protected $fillable = [
        'user_id', 'owner_id', 'account_image', 'name', 'account_number', 'account_type', 'rating',
        'parent_account_id', 'account_site', 'type', 'industry', 'ownership', 'website', 'phone', 'fax', 'email',
        'annual_revenue', 'employees', 'sic_code', 'ticker_symbol',
        'billing_country', 'billing_building', 'billing_street', 'billing_city', 'billing_state', 'billing_zip', 'billing_lat', 'billing_lng',
        'shipping_country', 'shipping_building', 'shipping_street', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_lat', 'shipping_lng',
        'address', 'city', 'country', 'outstanding_amount', 'description', 'notes', 'status',
    ];
    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }
    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
    public function contacts() { return $this->hasMany(CrmContact::class, 'account_id'); }
    public function deals() { return $this->hasMany(CrmDeal::class, 'account_id'); }
    public function leads() { return $this->hasMany(CrmLead::class, 'account_id'); }
    public function products() { return $this->belongsToMany(CrmProduct::class, 'crm_account_products', 'account_id', 'product_id')->withTimestamps(); }
    public function notes() { return $this->hasMany(CrmNote::class, 'notable_id')->where('notable_type', 'account'); }
    public function activities() { return $this->hasMany(CrmActivity::class, 'related_id')->where('related_type', 'account'); }
    public function quotes() { return $this->hasMany(CrmQuote::class, 'account_id'); }
    public function salesOrders() { return $this->hasMany(CrmSalesOrder::class, 'account_id'); }
    public function invoices() { return $this->hasMany(CrmInvoice::class, 'account_id'); }
}
