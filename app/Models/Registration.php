<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'apartment_sizes',
        'first_name',
        'last_name',
        'street',
        'zip_city',
        'email',
        'phone',
        'exported_at',
    ];

    protected $casts = [
        'apartment_sizes' => 'array',
        'exported_at' => 'datetime',
    ];

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
