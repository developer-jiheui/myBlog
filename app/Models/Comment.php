<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function images()
    {
        return $this->hasMany(BlogImage::class);
    }

    public function labels()
    {
        return $this->morphMany(EntityLabel::class, 'target', 'target_type', 'target_id')
            ->where('target_type', 'blog');
    }
    public static function displayComments(){
     return DB::table('comment')->select('title')->distinct()->get()->toArray();
    }
}
