<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pin extends Model
{
    use HasFactory;

    protected $table = 'pins';

    protected $fillable = [
        'pin_code',
        'max_downloads',
        'used_downloads',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function downloads()
    {
        return $this->hasMany(DownloadLog::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->used_downloads < $this->max_downloads;
    }

    public function getRemainingDownloads()
    {
        return $this->max_downloads - $this->used_downloads;
    }

    public function canDownload()
    {
        return $this->isActive();
    }

    public function recordDownload()
    {
        $this->increment('used_downloads');
        
        if ($this->used_downloads >= $this->max_downloads) {
            $this->update(['status' => 'expired']);
        }
    }
}
