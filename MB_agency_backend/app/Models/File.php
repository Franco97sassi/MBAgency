<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'name_file', 'url', 'uuid', 'user_id'];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function fields()
    {
        return $this->hasMany(Field::class);
    }
}
