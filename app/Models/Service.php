<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id','service_category_id','name','slug','description',
        'duration_minutes','buffer_before_minutes','buffer_after_minutes',
        'price_cents','currency','taxable','tax_rate_percent',
        'capacity','is_active','is_public','min_notice_minutes',
        'max_notice_days','booking_window_days',
        'online_payment_required','deposit_cents',
        'color','position'
    ];

    protected $casts = [
        'taxable' => 'bool',
        'is_active' => 'bool',
        'is_public' => 'bool',
        'online_payment_required' => 'bool',
    ];

    public function business() { return $this->belongsTo(Business::class); }
    public function category() { return $this->belongsTo(ServiceCategory::class, 'service_category_id'); }
    public function providers() { return $this->belongsToMany(User::class)->withTimestamps(); }

    // Accessor para precio en MXN (decimal)
    public function getPriceAttribute(): float {
        return ($this->price_cents ?? 0) / 100;
    }
}
