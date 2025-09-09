<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessHistory extends Model
{
    use HasFactory;

    protected $table = 'access_histories';

    protected $fillable = [
        'user_id',
        'ip',
        'session_id',
        'signed_in_at',
        'signed_out_at',
    ];

    protected $casts = [
        'signed_in_at'  => 'datetime',
        'signed_out_at' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // Relationship back to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
