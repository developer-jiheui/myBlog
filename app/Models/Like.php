<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'portfolio_id', 'created_at'];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function portfolio()
    {
        return $this->belongsTo(\App\Models\Portfolio::class);
    }
}
