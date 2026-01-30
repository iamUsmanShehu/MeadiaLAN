<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'resolution',
        'filename',
        'size',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
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
