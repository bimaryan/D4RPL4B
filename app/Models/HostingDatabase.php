<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingDatabase extends Model
{
    protected $fillable = ['hosting_id', 'db_name', 'db_user', 'db_password', 'status'];

    public function hosting()
    {
        return $this->belongsTo(Hosting::class);
    }
}
