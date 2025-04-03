<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
    ];

    protected $hidden = [
        'updated_at'
    ];

    protected $casts = [
        'image' => 'array',
    ];

    public static function validationRules()
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|array',
            'image.*' => 'image|mimes:jpg,png',
        ];
    }

    public static function validationMessages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a valid string.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a valid string.',
            'image.required' => 'The image field is required.',
            'image.array' => 'The image must be an array of files.',
            'image.*.image' => 'Each file must be an image.',
            'image.*.mimes' => 'Each image must be of type: jpg, png.',
        ];
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'drama_genres', 'drama_id', 'genre_id');
    }

}
