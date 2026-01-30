<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DownloadLog extends Model
{
    use HasFactory;

    protected $table = 'downloads_log';

    public $timestamps = false;

    protected $fillable = [
        'pin_id',
        'media_id',
        'ip_address',
        'user_agent',
        'downloaded_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    public function pin()
    {
        return $this->belongsTo(Pin::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
