<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    protected $guarded = [];

    function influencer() {
        return $this->belongsTo(Influencer::class);
    }

    function file()
    {
        return $this->belongsTo(File::class);
    }
}
