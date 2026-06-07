<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmCase extends Model
{
    protected $table = 'crm_cases';
    protected $fillable = [
        'user_id', 'account_id', 'contact_id', 'case_number', 'subject',
        'priority', 'status', 'type', 'origin', 'description', 'resolution', 'assigned_to',
    ];

    const PRIORITIES = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'];
    const PRIORITY_COLORS = ['low' => 'success', 'medium' => 'warning', 'high' => 'orange', 'critical' => 'danger'];
    const STATUSES = ['new' => 'New', 'assigned' => 'Assigned', 'in_progress' => 'In Progress', 'pending_customer' => 'Pending Customer', 'resolved' => 'Resolved', 'closed' => 'Closed'];
    const STATUS_COLORS = ['new' => 'info', 'assigned' => 'primary', 'in_progress' => 'warning', 'pending_customer' => 'secondary', 'resolved' => 'success', 'closed' => 'dark'];

    public function tenant()   { return $this->belongsTo(User::class, 'user_id'); }
    public function account()  { return $this->belongsTo(CrmAccount::class, 'account_id'); }
    public function contact()  { return $this->belongsTo(CrmContact::class, 'contact_id'); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }

    public static function generateNumber(int $userId): string
    {
        $count = static::where('user_id', $userId)->count() + 1;
        return 'CASE-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
