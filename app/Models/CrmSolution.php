<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CrmSolution extends Model
{
    protected $table = 'crm_solutions';
    protected $fillable = ['user_id', 'title', 'question', 'answer', 'category', 'is_public', 'view_count'];
    protected $casts = ['is_public' => 'boolean'];

    public function tenant() { return $this->belongsTo(User::class, 'user_id'); }
}
