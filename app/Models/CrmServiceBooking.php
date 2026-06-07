<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmServiceBooking extends Model
{
    protected $table = 'crm_service_bookings';
    protected $fillable = ['user_id', 'service_id', 'contact_id', 'account_id', 'booking_time', 'status', 'price', 'notes'];
    protected $casts = ['booking_time' => 'datetime', 'price' => 'decimal:2'];

    const STATUSES = ['scheduled' => 'Scheduled', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'no_show' => 'No Show'];
    const STATUS_COLORS = ['scheduled' => 'info', 'completed' => 'success', 'cancelled' => 'danger', 'no_show' => 'warning'];

    public function tenant()  { return $this->belongsTo(User::class, 'user_id'); }
    public function service() { return $this->belongsTo(CrmService::class, 'service_id'); }
    public function contact() { return $this->belongsTo(CrmContact::class, 'contact_id'); }
    public function account() { return $this->belongsTo(CrmAccount::class, 'account_id'); }
}
