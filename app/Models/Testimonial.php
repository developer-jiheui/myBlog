<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Model
{
    use HasFactory;

    protected $table = 'testimonials';

    protected $fillable = [
        'author_user_id',
        'author_name',
        'author_avatar_url',
        'author_title',
        'body',
        'status',   // 1=public, 0=hidden, 2=pending
        'pinned',
    ];

    protected $casts = [
        'pinned'     => 'boolean',
        'status'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const STATUS_HIDDEN  = 0;
    public const STATUS_PUBLIC  = 1;
    public const STATUS_PENDING = 2;

    // Who wrote it (optional, nullable if user deleted)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }

    // Scopes for convenience
    public function scopePublic($q)
    {
        return $q->where('status', 1);
    }

    public function scopePinnedFirst($q)
    {
        return $q->orderByDesc('pinned')->orderByDesc('created_at');
    }

    public static function statusLabels(): array {
        return [
            self::STATUS_PUBLIC  => 'Public',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_HIDDEN  => 'Hidden',
        ];
    }
}
