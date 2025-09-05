<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioImage extends Model
{
    use HasFactory;

    protected $fillable = ['portfolio_id', 'url', 'alt_text', 'caption', 'position', 'is_cover'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}

