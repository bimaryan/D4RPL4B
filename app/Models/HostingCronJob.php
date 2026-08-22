<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingCronJob extends Model
{
    protected $fillable = ['hosting_id', 'url', 'interval', 'last_run', 'status'];
    
    protected $casts = [
        'last_run' => 'datetime'
    ];

    public function hosting()
    {
        return $this->belongsTo(Hosting::class);
    }
}
