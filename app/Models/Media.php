<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'title',
        'description',
        'type',
        'category_id',
        'filename',
        'size',
        'poster_url',
        'duration',
        'year',
        'director',
        'cast',
        'episodes',
        'is_processed',
    ];

    protected $casts = [
        'size' => 'integer',
        'duration' => 'integer',
        'episodes' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function downloads()
    {
        return $this->hasMany(DownloadLog::class);
    }

    public function versions()
    {
        return $this->hasMany(MediaVersion::class);
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
