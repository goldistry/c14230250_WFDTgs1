<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasUuids;
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'drama_genres', 'genre_id', 'drama_id');
    }
}
