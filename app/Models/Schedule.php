<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasHashId;

    protected $fillable = ['day', 'time_start', 'time_end', 'course', 'lecturer', 'room', 'sort_order'];
}
