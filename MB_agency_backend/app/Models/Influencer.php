<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Influencer extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

   
    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function fieldsMore($start,$end)
    {
        return $this->hasMany(Field::class)->whereDate('created_at', '>=', $start)
        ->whereDate('created_at', '<=', $end);
    }

    // 
    public function days()
    {
        return $this->hasMany(Day::class);
    }

    public function weeks()
    {
        return $this->hasMany(Week::class);
    }
}
