<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;
    protected $guarded = [];
    // protected $fillable = ['influencer_id', 'gift_coins'];

    function influencer() {
        return $this->belongsTo(Influencer::class);
    }

    function group() {
        return $this->belongsTo(Group::class);
    }

    function story() {
        return $this->belongsTo(Story::class);
    }

    function statusVideo() {
        return $this->belongsTo(StatusVideo::class);
    }

    function category() {
        return $this->belongsTo(Category::class);
    }

    function file()
    {
        return $this->belongsTo(File::class);
    }
}
