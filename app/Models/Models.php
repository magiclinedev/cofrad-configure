<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'image',
        'tags',
    ];
    public function tags()
    {
        return $this->hasMany(Tags::class);
    }
}
