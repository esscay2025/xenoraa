<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CrmVendorAttachment;
use App\Models\CrmNote;
use App\Models\CrmActivity;

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

    public function notes()       { return $this->morphMany(CrmNote::class, 'notable'); }
    public function activities()  { return $this->morphMany(CrmActivity::class, 'related'); }
    public function attachments() { return $this->hasMany(CrmVendorAttachment::class, 'vendor_id'); }
    public function products()    { return $this->hasMany(CrmProduct::class, 'vendor_id'); }
    public function contacts()    { return $this->hasMany(CrmContact::class, 'vendor_id'); }
}
