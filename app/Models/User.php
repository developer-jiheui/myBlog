<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Testimonial;
use App\Models\AccessHistory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email', 'password', 'user_type', 'first_name', 'last_name',
        'avatar', 'register_type', 'address', 'phone_num', 'bio',
        'job_title', 'birthday', 'instagram_url', 'linkedin_url', 'github_url'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
    ];

    /**
     * Relationships
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'author_user_id');
    }

    public function accessHistories()
    {
        return $this->hasMany(AccessHistory::class);
    }

    /**
     * Auth methods
     */

    // =========================================
    // Authentication overrides
    // =========================================

    public function getAuthPassword()
    {
        return $this->password; // <-- was broken in your snippet
    }

    public function getAuthIdentifierName()
    {
        return 'email'; // login by email
    }
}
