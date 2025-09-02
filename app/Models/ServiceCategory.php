<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id','name','slug','color','icon','description','position','is_active'
    ];

    public function business() { return $this->belongsTo(Business::class); }
    public function services() { return $this->hasMany(Service::class); }
}
