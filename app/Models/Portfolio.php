<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Portfolio extends Model
{
    protected $table = 'portfolios';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id','title','slug','description','project_url','image_url','like_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(PortfolioImage::class, 'portfolio_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function techs()
    {
        return $this->belongsToMany(Tech::class, 'portfolio_tech', 'portfolio_id', 'tech_id')
            ->withPivot(['level','version','is_primary','sort_order'])
            ->withTimestamps();
    }
    public static function categories() {
        return DB::table('entity_labels')
            ->where('target_type', 'portfolio')
            ->where('kind', 'category')
            ->distinct()
            ->orderBy('name')             // optional: alphabetical
            ->pluck('name')               // or 'slug' if you prefer machine names
            ->toArray();
    }

    // by category slug
    public static function portfoliosByCategory(string $slug)
    {
        return DB::table('portfolios')
            ->join('entity_labels', function ($join) {
                $join->on('portfolios.id', '=', 'entity_labels.target_id')
                    ->where('entity_labels.target_type', 'portfolio')
                    ->where('entity_labels.kind', 'category');
            })
            ->where('entity_labels.slug', $slug)
            ->select('portfolios.*')
            ->orderByDesc('portfolios.created_at')
            ->get();
    }

// by tag slug
    public static function portfoliosByTag(string $slug)
    {
        return DB::table('portfolios')
            ->join('entity_labels', function ($join) {
                $join->on('portfolios.id', '=', 'entity_labels.target_id')
                    ->where('entity_labels.target_type', 'portfolio')
                    ->where('entity_labels.kind', 'tag');
            })
            ->where('entity_labels.slug', $slug)
            ->select('portfolios.*')
            ->orderByDesc('portfolios.created_at')
            ->get();
    }
}
