<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasHashId;

    protected $fillable = ['title', 'content', 'category', 'event_date'];
}
