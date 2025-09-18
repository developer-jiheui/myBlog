<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Blog extends Model
{
    use HasFactory;
    protected $table = 'blogs';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id','title','slug','contents','image_url'];

    public static function blogTitles() {
        //@TODO May need to check if attr are null.
        return DB::table('blogs')->select('title')->distinct()->get()->toArray();
    }
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
}
