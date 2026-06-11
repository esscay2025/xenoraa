<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcomMailTemplate extends Model
{
    protected $table = 'ecom_mail_templates';

    protected $fillable = [
        'user_id', 'name', 'type', 'subject', 'body_html',
        'logo_path', 'primary_color', 'secondary_color',
        'font_family', 'is_default', 'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public static $types = [
        'order_confirmation' => 'Order Confirmation',
        'order_shipped'      => 'Order Shipped',
        'order_delivered'    => 'Order Delivered',
        'order_cancelled'    => 'Order Cancelled',
        'payment_received'   => 'Payment Received',
        'payment_failed'     => 'Payment Failed',
        'refund_processed'   => 'Refund Processed',
        'cart_abandoned'     => 'Abandoned Cart',
        'review_request'     => 'Review Request',
        'welcome'            => 'Welcome Email',
        'general'            => 'General',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::$types[$this->type] ?? ucfirst($this->type);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
