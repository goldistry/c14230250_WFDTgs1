<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $dramas = [
            [
                'title' => 'Love Between Fairy and Devil',
                'description' => 'A forbidden love story between a fairy and a devil.',
                'image' => ['and_devilP.jpg', 'and_devilL.jpg'],
            ],
           
            [
                'title' => 'Eternal Love',
                'description' => 'A goddess and a prince fall in love across lifetimes.',
                'image' => ['eternal_loveP.jpg', 'eternal_loveL.jpg'],
            ],
            [
                'title' => 'Ashes of Love',
                'description' => 'A flower goddess and a fire god struggle with fate.',
                'image' => ['of_loveP.jpg', 'of_loveL.jpg'],
            ],
            [
                'title' => 'Go Ahead',
                'description' => 'Three unrelated kids grow up as a family despite hardships.',
                'image' => ['go_aheadP.jpg', 'go_aheadL.jpg'],
            ],
            [
                'title' => 'Reset',
                'description' => 'Two strangers trapped in a time loop try to stop a bus explosion.',
                'image' => ['resetP.jpg', 'resetL.jpg'],
            ],
            [
                'title' => 'The Long Ballad',
                'description' => 'A princess seeks revenge after her family is massacred.',
                'image' => ['long_balladP.jpg', 'long_balladL.jpg'],
            ],
            [
                'title' => 'You Are My Glory',
                'description' => 'A gamer and a space engineer rekindle love.',
                'image' => ['my_gloryP.jpg', 'my_gloryL.jpg'],
            ],
            [
                'title' => 'Word of Honor',
                'description' => 'Martial heroes and hidden secrets unfold in a Wuxia world.',
                'image' => ['of_honorP.jpg', 'of_honorL.jpg'],
            ],
            [
                'title' => 'The Untamed',
                'description' => 'Two cultivators uncover a dark mystery that threatens the world.',
                'image' => ['the_untamedP.jpg', 'the_untamedL.jpg'],
            ],
        ];

        foreach ($dramas as $dramaData) {
            $drama = Promotion::create([
                'id' => Str::uuid(),
                'title' => $dramaData['title'],
                'description' => $dramaData['description'],
                'image' => $dramaData['image'],
            ]);

            $randomGenreIds = Genre::inRandomOrder()->limit(rand(2, 4))->pluck('id')->toArray();
            $drama->genres()->attach($randomGenreIds);
        }
    }
}
