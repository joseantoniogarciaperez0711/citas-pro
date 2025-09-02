<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','slug','timezone','currency','locale','settings'];

    protected $casts = ['settings' => 'array'];

    public function categories() {
        return $this->hasMany(ServiceCategory::class);
    }

    public function services() {
        return $this->hasMany(Service::class);
    }
}
